<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Director;
use Illuminate\Http\Request;

class DirectorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $directors = Director::all();
        return view('directors.index', compact('directors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate inputs
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string'],
            'designation' => ['required', 'string',],
            // 'signature' => ['required',  'image', 'size:2048'],
        ]);
        if ($request->file('signature')) {

            $path = $request->file('signature')->store('signatures');
        } else {
            if (isset($request->id)) {
                $director = Director::find($request->id);
                $path = $director->signature;
            }
        }
        try {
            Director::updateOrCreate(['id' => $request->id], [
                'name' => $request->name,
                'position' => $request->position,
                'designation' => $request->designation,
                'signature' => $path,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }

        return redirect()->route('directors.index')->with('message', 'Director added successfully.')->with('classname', 'alert-success');
        //

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $directors = Director::all();
        $director = Director::findOrFail($id);
        return view('directors.index', compact('director', 'directors'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
