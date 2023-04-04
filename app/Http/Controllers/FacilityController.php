<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Region;

class FacilityController extends Controller
{
    function index(Request $request)
    {
        $facilities = Facility::with('region_details')->get();
        $regions = Region::all();
        if ($request->filter == 'R') {
            $facilities = Facility::with('region_details')->where('region_id', $request->region)->get();
            return view('facilities.index', compact('facilities', 'regions'));
        } else if ($request->filter == 'A') {
            $facilities =  Facility::with('region_details')->where('name', 'like', $request->alphabet . '%')->get();
            return view('facilities.index', compact('facilities', 'regions'));
        } else {
            $facilities = Facility::with('region_details')->get();
            return view('facilities.index', compact('facilities', 'regions'));
        }
    }

    function create()
    {
    }

    function edit(Facility $facility)
    {
    }

    function destroy(Facility $facility)
    {
        Facility::destroy($facility->id);
        $facility->deleted_by = \auth()->id();
        $facility->save();
        return redirect()->route('facilities.index')->with('message', 'Facility deleted successfully.')->with('classname', 'alert-danger');
    }

    function createOrUpdate(Facility $facility, Request $request)
    {
        $req = isset($request->id) ? $request->merge(['updated_by' => auth()->id()])->except('id') : $request->merge(['created_by' => auth()->id()])->except('id');
        // dd($request->accreditation_no);
        // $validated = $request->validate(['accreditation_no' => 'required|unique:facilities,accreditation_no,' . $request->id], ['accreditation_no.unique' => 'The accreditation number has already been taken.']);
        $facility = Facility::updateOrCreate(
            [
                'id' => $request->id,
            ],
            $req
        );
        return redirect()->route('facilities.index')->with('message', 'Facility updated successfully.')->with('classname', 'alert-success');
    }
}
