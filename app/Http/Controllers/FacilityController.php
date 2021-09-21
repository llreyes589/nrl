<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;

class FacilityController extends Controller
{
    function index(){
        $facilities = Facility::all();
        return view('facilities.index', compact('facilities'));
    }

    function create(){

    }

    function edit(Facility $facility){

    }

    function destroy(Facility $facility){
        Facility::destroy($facility->id);
        return redirect()->route('facilities.index')->with('message', 'Facility deleted successfully.')->with('classname', 'alert-danger');
    }

    function createOrUpdate(Facility $facility, Request $request){
        $facility = Facility::updateOrCreate(
            [
                'id' => $request->id,
            ],
            $request->except('id')
        );
        return redirect()->route('facilities.index')->with('message', 'Facility updated successfully.')->with('classname', 'alert-success');
    }

}
