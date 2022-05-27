<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestionnaireRequest;
use App\Models\Construct;
use App\Models\Questionnaire;
use App\Models\Respondent;
use App\Models\Response;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class QuestionnaireController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view questionnaires');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $questionnaires = Questionnaire::where('creator_id', Auth::id())->with('constructs')->paginate(20);
        return Inertia::render('Questionnaires/Index', ['questionnaires' => $questionnaires]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        if (Auth::user()) {
            $constructs = Construct::all()->pluck('name', 'id');
            return Inertia::render('Questionnaires/Create', ['constructs' => $constructs]);
        }
        return Inertia::render('Welcome', ['canLogin' => true, 'canRegister' => true]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(StoreQuestionnaireRequest $request)
    {
        $validated = $request->validated();

        $start_time = new DateTime($request['start_time']);
        $end_time = new DateTime($request['end_time']);

        //$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        //$code = substr(str_shuffle($permitted_chars), 0, 10);

        $code = random_int(0, 999999);
        $code = str_pad($code, 6, 0, STR_PAD_LEFT);


        $questionnaire = Questionnaire::create([
            'name' => $validated['name'],
            'subject' => $request['subject'],
            'start_time' => $start_time->format('Y-m-d H:i'),
            'end_time' => $end_time->format('Y-m-d H:i'),
            'code' => $code,
            'creator_id' => $request->user()->id,
            'log_in_required' => $request['log_in_required'],
        ]);

        $constructs = $request->input('constructs');
        if (!empty($constructs)) {
            foreach ($constructs as $construct_id) {
                DB::table('model_has_constructs')->insert(
                    ['construct_id' => $construct_id, 'model_type' => 'App\Models\Questionnaire', 'model_id' => $questionnaire->id]
                );
            }
        }

        return Redirect::route('questionnaires.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Questionnaire  $questionnaire
     * @return \Inertia\Response
     */
    public function show(Questionnaire $questionnaire)
    {
        if (Auth::user()) {
            $construct_ids = DB::table('model_has_constructs')->where('model_id', $questionnaire->id)->pluck('construct_id');
            $constructs = Construct::whereIn('id', $construct_ids)->with('statements')->get();

            $respondents = Respondent::where('questionnaire_id', $questionnaire->id)->with('responses')->get();
            return Inertia::render('Results/Questionnaire', [
                'questionnaire' => $questionnaire,
                'constructs' => $constructs,
                'respondents' => $respondents
            ]);
        }
        return Inertia::render('Welcome', ['canLogin' => true, 'canRegister' => true]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Questionnaire  $questionnaire
     * @return \Illuminate\Http\Response
     */
    public function edit(Questionnaire $questionnaire)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Questionnaire  $questionnaire
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Questionnaire $questionnaire)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Questionnaire  $questionnaire
     * @return \Illuminate\Http\Response
     */
    public function destroy(Questionnaire $questionnaire)
    {
        //
    }

    public function start(Request $request) {   //choose
        $code = $request['questionnaire_code'];
        $questionnaire = DB::table('questionnaires')->where('code', $code)->first();
        if (!$questionnaire) {
            return Inertia::render('Welcome');
        }
        return Inertia::render('Response/QuestionnaireStart', ['questionnaire' => $questionnaire]);
    }

    public function statements(Request $request) {  //start
        $code = $request['questionnaire_code'];
        $questionnaire = DB::table('questionnaires')->where('code', $code)->first();
        if (!$questionnaire) {
            return Inertia::render('Welcome');
        }

        // create respondent
        $respondent  = Respondent::create([
            'questionnaire_id' => $questionnaire->id,
        ]);

        // questionnaire has constructs
        $construct_ids = DB::table('model_has_constructs')->where('model_id', $questionnaire->id)->pluck('construct_id');

        // construct have statements
        $statements = DB::table('statements')->whereIn('construct_id', $construct_ids->all())->orderBy('position')->get();

        return Inertia::render('Response/Statements', [
            'questionnaire' => $questionnaire,
            'respondent' => $respondent,
            'statements' => $statements,
        ]);

    }

    public function finish(Request $request) {

        $code = $request['questionnaire_code'];
        $questionnaire = DB::table('questionnaires')->where('code', $code)->first();
        if (!$questionnaire) {
            return Inertia::render('Welcome');
        }

        $respondent_id = $request['respondent_id'];
        $respondent = DB::table('respondents')->where('id', $respondent_id);
        $respondent->update([
            'end_time' => date('Y-m-d H:i:s'),
        ]);

        $statements = $request['statements'];

        // create reponses
        foreach($statements as $statement) {

            $response = Response::create([
                'respondent_id' => $respondent_id,
                'answer' => $statement['answer'],
                'statement_id' => $statement['id'],
            ]);

        }

        return Inertia::render('Response/Finished');

    }

    public function results() {
        if (Auth::user()) {
            $questionnaires = Questionnaire::where('creator_id', Auth::id())->with('respondents')->get();
            return Inertia::render('Results/Index', ['questionnaires' => $questionnaires]);
        }
        return Inertia::render('Welcome', ['canLogin' => true, 'canRegister' => true]);
    }

}
