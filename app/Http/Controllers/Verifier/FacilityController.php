<?php

namespace App\Http\Controllers\Verifier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Facility;
use Carbon\Carbon;

class FacilityController extends Controller
{
    function index(){
        $facilities = Facility::all();
        return view('verifiers.facilities.index', compact('facilities'));
    }

    function show($id){
        $facility = Facility::find($id);
        return view('verifiers.facilities.show', compact('facility'));
    }
    
    function updateVerified($id){
        $facility = Facility::find($id);
        $facility->update([
            'verified_by' => auth()->id(),
            'verified_at' => Carbon::now(),
        ]);
        return redirect()->route('verifiers.facilities.show', $id)->with('message', 'Facility verified successfully.')->with('classname', 'alert-success');

    }
    function updateEndorse($id){
        $facility = Facility::find($id);
        $facility->update([
            'endorsed_by' => auth()->id(),
            'endorsed_at' => Carbon::now(),
        ]);
        return redirect()->route('verifiers.facilities.show', $id)->with('message', 'Facility endorsed successfully.')->with('classname', 'alert-success');

    }

    function updateApproved($id){
        $facility = Facility::find($id);
        $facility->update([
            'approved_by' => auth()->id(),
            'approved_at' => Carbon::now(),
        ]);
        return redirect()->route('verifiers.facilities.show', $id)->with('message', 'Facility approved successfully.')->with('classname', 'alert-success');

    }

    
}
