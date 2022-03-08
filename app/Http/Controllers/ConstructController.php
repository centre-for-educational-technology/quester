<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConstructRequest;
use App\Models\Construct;
use App\Models\Statement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

class ConstructController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $constructs = Construct::with('statements')->get();
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
        foreach ($statements_info as $index=>$statement_info) {
            if (isset($statement_info['text'])) {
                $statement = Statement::create([
                    'text' => $statement_info['text'],
                    'construct_id' => $construct->id,
                    'position' => $index + 1,
                ]);
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
     * @return \Illuminate\Http\Response
     */
    public function edit(Construct $construct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Construct  $construct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Construct $construct)
    {
        //
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
