<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestionnaireRequest;
use App\Models\Construct;
use App\Models\Questionnaire;
use App\Models\Respondent;
use App\Models\Response;
use App\Models\Statement;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use phpDocumentor\Reflection\Types\Integer;

class QuestionnaireController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
        //$this->middleware('permission:view questionnaires');
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

        (new StatementController)->saveStatements(null, $questionnaire->id, $request['statements']);

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
            $questionnaire_statements = Statement::where('questionnaire_id', $questionnaire->id)->get();

            $respondents = Respondent::where('questionnaire_id', $questionnaire->id)->whereNotNull('end_time')->with('responses')->with('user')->get();
            return Inertia::render('Results/Questionnaire', [
                'questionnaire' => $questionnaire,
                'constructs' => $constructs,
                'questionnaire_statements' => $questionnaire_statements,
                'respondents' => $respondents
            ]);
        }
        return Inertia::render('Welcome', ['canLogin' => true, 'canRegister' => true]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Questionnaire  $questionnaire
     * @return \Inertia\Response
     */
    public function edit(Questionnaire $questionnaire)
    {
        $questionnaire = Questionnaire::where('id', $questionnaire->id)
            ->with('constructs')
            ->with('statements')
            ->with('respondents')
            ->first();
        $constructs = Construct::all()->pluck('name', 'id');
        $questionnaire_constructs = $questionnaire->constructs->pluck('id');
        return Inertia::render('Questionnaires/Edit', [
            'questionnaire' => $questionnaire,
            'constructs' => $constructs,
            'questionnaire_constructs' => $questionnaire_constructs
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Questionnaire  $questionnaire
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StoreQuestionnaireRequest $request, Questionnaire $questionnaire)
    {
        $validated = $request->validated();

        $start_time = new DateTime($request['start_time']);
        $end_time = new DateTime($request['end_time']);

        $questionnaire->update([
            'name' => $validated['name'],
            'subject' => $request['subject'],
            'start_time' => $start_time->format('Y-m-d H:i'),
            'end_time' => $end_time->format('Y-m-d H:i'),
            'log_in_required' => $request['log_in_required'],
        ]);

        (new StatementController)->saveStatements(null, $questionnaire->id, $request['statements']);

        $this->updateConstructs($questionnaire, $request->input('constructs'));

        return Redirect::route('questionnaires.index');
    }

    public function updateConstructs($questionnaire, $construct_ids) {

        $questionnaire = Questionnaire::where('id', $questionnaire->id)
            ->with('constructs')
            ->first();

        // delete previous relations
        DB::table('model_has_constructs')->where('model_type', 'App\Models\Questionnaire')
            ->where('model_id', $questionnaire->id)->delete();

        // add new relations
        if (!empty($construct_ids)) {
            foreach ($construct_ids as $construct_id) {
                DB::table('model_has_constructs')->insert(
                    ['construct_id' => $construct_id, 'model_type' => 'App\Models\Questionnaire', 'model_id' => $questionnaire->id]
                );
            }
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Questionnaire  $questionnaire
     * @return \Illuminate\Http\Response
     */
     public function destroy(Request $request,Questionnaire $questionnaire)
     {
         $user = $request->user();
         if ($user and $user->id === $questionnaire->creator_id)
         {
             try {
                 if ($questionnaire->creator_id ===  $request->user()->id)
                 {
                   DB::table('statements')->where('questionnaire_id', $questionnaire->id)->delete();
                   $questionnaire = Questionnaire::where('id', $questionnaire->id);

                   $questionnaire->delete();
                   return response()->json("success");
                 }
             }
             catch(Exception $e){
             return response()->json("error");
             }
         }
         else {
             abort(404);
         }
     }

    public function start(Request $request) {   //choose
        $code = $request['questionnaire_code'];
        $questionnaire = DB::table('questionnaires')->where('code', $code)->first();

        if (!$questionnaire) {
            return Inertia::render('Welcome', ['canLogin' => true, 'canRegister' => true]);
        }

        $can_answer = $this->isQuestionnaireOpen($questionnaire);
        if (!$can_answer) {
            return Inertia::render('Welcome', ['canLogin' => true, 'canRegister' => true]);
        }
        return Inertia::render('Response/QuestionnaireStart', ['questionnaire' => $questionnaire]);
    }

    public function isQuestionnaireOpen($questionnaire) {
        $start_time = strtotime($questionnaire->start_time);
        $end_time = strtotime($questionnaire->end_time);
        $current_time = time();
        if ($start_time > $current_time || $current_time > $end_time) {
            return false;
        }
        if ($questionnaire->log_in_required) {
            if (!Auth::user()) {
                return false;
            }
        }
        return true;

    }

    public function statements(Request $request) {  //start
        $code = $request['questionnaire_code'];
        $questionnaire = DB::table('questionnaires')->where('code', $code)->first();
        if (!$questionnaire) {
            return Inertia::render('Welcome');
        }

        $user_id = null;
        if (Auth::user()) {
            $user_id = Auth::user()->id;
        }

        // create respondent
        $respondent  = Respondent::create([
            'user_id' => $user_id,
            'questionnaire_id' => $questionnaire->id,
        ]);

        // questionnaire has constructs
        $construct_ids = DB::table('model_has_constructs')->where('model_id', $questionnaire->id)->pluck('construct_id');

        // construct have statements
        $statements = DB::table('statements')->whereIn('construct_id', $construct_ids->all())->orderBy('position')->get();

        // and questionnaire has statements
        $questionnaire_statements = DB::table('statements')->where('questionnaire_id', $questionnaire->id)->orderBy('position')->get();

        $statements = $statements->merge($questionnaire_statements);

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

            if (key_exists('answer', $statement)) {
                $response = Response::create([
                    'respondent_id' => $respondent_id,
                    'answer' => $statement['answer'],
                    'statement_id' => $statement['id'],
                ]);
            }

        }

        return Inertia::render('Response/Finished');

    }
    public function resultsDestroy(Request $request,Questionnaire $questionnaire)
    {
        if (Auth::user()->isAdmin())
        {
        try {
                Respondent::where('questionnaire_id',$questionnaire->id)->delete();
                return response()->json("success");
            }
            catch(Exception $e){
             return response()->json("error");
            }
        }
        else {
            abort(404);
        }
    }
    public function results() {
        if (Auth::user()) {
            if (Auth::user()->isAdmin()) {
                $questionnaires = Questionnaire::with('respondents')->get();
            }
            else {
                $questionnaires = Questionnaire::where('creator_id', Auth::id())->with('respondents')->get();
            }
            return Inertia::render('Results/Index', ['questionnaires' => $questionnaires, 'admin'=>Auth::user()->isAdmin()]);
        }
        return Inertia::render('Welcome', ['canLogin' => true, 'canRegister' => true]);
    }

    public function getQuestionnaireConstructAverageResult(Request $request) {
        $questionnaire_id = $request['questionnaire_id'];
        $construct_id = $request['construct_id'];
        $statements = Statement::where('construct_id', $construct_id)->get();
        return $this->calculateStatementsAverage($questionnaire_id, $statements);
    }

    public function getQuestionnaireStatementsAverageResult(Request $request) {
        $questionnaire_id = $request['questionnaire_id'];
        $statements = Statement::where('questionnaire_id', $questionnaire_id)->get();
        return $this->calculateStatementsAverage($questionnaire_id, $statements);
    }

    public function calculateStatementsAverage($questionnaire_id, $statements) {
        $statements_result = 0;
        foreach ($statements as $statement) {
            $statement_average_info = $this->getQuestionnaireStatementAverage($questionnaire_id, $statement->id);
            $statements_result += $statement_average_info['average'];
        }
        $average = 0;
        if (count($statements) > 0) {
            $average = $statements_result / count($statements);
            $average = round($average, 1);
        }
        return $average;
    }

    public function getQuestionnaireStatementAverageResult(Request $request) {

        $questionnaire_id = $request['questionnaire_id'];
        $statement_id = $request['statement_id'];

        $average_result = $this->getQuestionnaireStatementAverage($questionnaire_id, $statement_id);

        return response()->json($average_result);

    }

    public function getQuestionnaireStatementAverage($questionnaire_id, $statement_id) {
        $respondents = Respondent::where('questionnaire_id', $questionnaire_id)->with('responses')->with('user')->get();
        $respondents_count = count($respondents);
        $answers_result = 0;
        foreach ($respondents as $respondent) {
            // get respondent answer based on statement
            $response = Response::where('respondent_id', $respondent->id)->where('statement_id', $statement_id)->first();
            if ($response) {
                $answers_result += $response->answer;
            }

        }
        $average = 0;
        if ($respondents_count > 0) {
            $average = $answers_result / $respondents_count;
            $average = round($average, 1);
        }

        return array(
            'average' => $average,
            'respondents_count' => $respondents_count,
        );
    }

    public function getStatementData(Request $request) {

        $questionnaire_id = $request['questionnaire_id'];
        $statement_id = $request['statement_id'];

        $respondents_ids = Respondent::where('questionnaire_id', $questionnaire_id)->pluck('id')->toArray();

        $responses_with_answer_one = Response::whereIn('respondent_id', $respondents_ids)
            ->where('statement_id', $statement_id)
            ->where('answer', 1)
            ->count();
        $responses_with_answer_two = Response::whereIn('respondent_id', $respondents_ids)
            ->where('statement_id', $statement_id)
            ->where('answer', 2)
            ->count();
        $responses_with_answer_three = Response::whereIn('respondent_id', $respondents_ids)
            ->where('statement_id', $statement_id)
            ->where('answer', 3)
            ->count();
        $responses_with_answer_four = Response::whereIn('respondent_id', $respondents_ids)
            ->where('statement_id', $statement_id)
            ->where('answer', 4)
            ->count();
        $responses_with_answer_five = Response::whereIn('respondent_id', $respondents_ids)
            ->where('statement_id', $statement_id)
            ->where('answer', 5)
            ->count();
        $statement_data = array(
            $responses_with_answer_one,
            $responses_with_answer_two,
            $responses_with_answer_three,
            $responses_with_answer_four,
            $responses_with_answer_five,
        );

        $chartData =  array(
            'labels' => [ 'Not very likely', 'Not likely', 'Hard to say', 'Likely', 'Very likely'],
             'datasets' => [
                    array(
                        'label' => 'Respondents',
                        'backgroundColor' => '#7CB4EC',
                        'data' => $statement_data,
                    )
                ]
            );

        return response()->json($chartData);

    }

    public function getQuestionnaireStatementsData(Request $request) {
        $questionnaire_id = $request['questionnaire_id'];
        if ($request['construct_id']) {
            $construct_id = $request['construct_id'];
            $statements = Statement::where('construct_id', $construct_id)->get();
        } else {
            $statements = Statement::where('questionnaire_id', $questionnaire_id)->get();
        }
        return $this->getStatementsData($questionnaire_id, $statements);
    }

    public function getStatementsData($questionnaire_id, $statements) {
        $statement_labels = array();
        $statements_data = array();
        foreach ($statements as $statement) {
            $statement_labels []= $statement->text;
            $average_info = $this->getQuestionnaireStatementAverage($questionnaire_id, $statement->id);
            $statements_data []= $average_info['average'];
        }

        $chartData =  array(
            'labels' => $statement_labels,
            'datasets' => [
                array(
                    'label' => 'Average',
                    'backgroundColor' => '#7CB4EC',
                    'data' => $statements_data,
                )
            ]
        );

        return response()->json($chartData);
    }

}
