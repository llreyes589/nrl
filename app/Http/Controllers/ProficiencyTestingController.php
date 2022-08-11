<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProficiencyTesting;
use Exception;
use Illuminate\Database\QueryException;

class ProficiencyTestingController extends Controller
{
    public function index()
    {
        $pts = ProficiencyTesting::all();
        return view('pt.index', compact('pts'));
    }

    public function create(ProficiencyTesting $pt)
    {

        return view('pt.form', compact('pt'));
    }

    public function store(Request $request)
    {
        $pt = ProficiencyTesting::where('sdtl', $request->sdtl)->where('cycle', $request->cycle)->first();
        if ($pt) return redirect(\route('proficiency-testing.create'))->with(['message' => 'PT already exists', 'classname' => 'alert-danger'])->withInput();
        $validated = $request->validate([
            'sdtl' => 'required | numeric ',
            'cycle' => 'required | numeric ',
        ]);
        ProficiencyTesting::create($request->except('_token'));
        return redirect(\route('proficiency-testing.index'))->with(['message' => 'PT sucessfully saved', 'classname' => 'alert-success']);
    }

    public function edit($id)
    {
        $pt = ProficiencyTesting::find($id);
        return view('pt.form', compact('pt'));
    }

    public function update($id)
    {
        $pt = ProficiencyTesting::find($id);
        try {

            $pt->update(\request()->except('_token'));
        } catch (Exception $e) {
            return redirect(\route('proficiency-testing.edit', $id))->with(['message' => $e->errorInfo[1] === 1062 ? 'PT already exists' : $e->errorInfo[2], 'classname' => 'alert-danger']);
        }
        return redirect(\route('proficiency-testing.index'))->with(['message' => 'PT sucessfully updated', 'classname' => 'alert-success']);
    }
}
