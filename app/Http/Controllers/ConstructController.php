<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConstructRequest;
use App\Models\Construct;
use App\Models\Statement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

class ConstructController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view constructs');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $constructs = Construct::with('statements')->with('questionnaires')->get();
        return Inertia::render('Constructs/Index', ['constructs' => $constructs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return Inertia::render('Constructs/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreConstructRequest $request
     * @return RedirectResponse
     */
    public function store(StoreConstructRequest $request)
    {

        $validated = $request->validated();

        $construct = Construct::create([
            'name' => $validated['name'],
            'objective' => $request['objective'],
            'creator_id' => $request->user()->id,
        ]);

        self::saveStatements($construct, $request['statements']);

        return Redirect::route('constructs.index');

    }

    public function saveStatements($construct, $statements_info) {
        if (empty($statements_info)) {
            return false;
        }
        $position = 1;
        foreach ($statements_info as $index=>$statement_info) {

            if (isset($statement_info['id'])) {
                // update existing statement
                $statement = Statement::find($statement_info['id']);
                if (empty($statement_info['text'])) {
                    $statement->delete();
                }
                if (isset($statement_info['text'])) {
                    $statement->text = $statement_info['text'];
                    $statement->position = $position++;
                    $statement->save();
                }
            } else {
                // create new statement
                if (isset($statement_info['text'])) {
                    $statement = Statement::create([
                        'text' => $statement_info['text'],
                        'construct_id' => $construct->id,
                        'position' => $position++,
                    ]);
                }
            }

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Construct  $construct
     * @return \Illuminate\Http\Response
     */
    public function show(Construct $construct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Construct  $construct
     * @return Response
     */
    public function edit(Construct $construct)
    {
        $construct = Construct::where('id', $construct->id)
            ->with('statements')
            ->first();
        return Inertia::render('Constructs/Edit', [
            'construct' => $construct
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Construct  $construct
     * @return RedirectResponse
     */
    public function update(StoreConstructRequest $request, Construct $construct)
    {
        $validated = $request->validated();

        // if construct not used for questionnaires, update
        if (!$construct->isUsed()) {
            $construct->update([
                'name' => $validated['name'],
                'objective' => $request['objective'],
            ]);
            self::saveStatements($construct, $request['statements']);
        }

        return Redirect::route('constructs.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Construct  $construct
     * @return \Illuminate\Http\Response
     */
    public function destroy(Construct $construct)
    {
        //
    }
}
