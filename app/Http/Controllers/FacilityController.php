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

    function store(Request $request){
        $facility = Facility::create($request->all());
        return redirect()->route('facilities.index')->with('message', 'success')->with('alert-className', 'alert-success');

    }

    function edit(Facility $facility){

    }

    function destroy(Facility $facility){

    }

}
