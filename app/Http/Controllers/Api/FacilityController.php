<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Region;

class FacilityController extends Controller
{
    function facilities(Request $request){
        $facilities = '';
        $regions = Region::all();
        if(\request()->filter == 'R'){
            $facilities = Facility::with('region_details')->with(['certificate' => function($q){
                $q->max('created_at');
            }])->where('region_id', \request()->region)->get();
            return response()->json(['facilities' => $facilities, 'regions' => Region::all()]);
        }else if(\request()->filter == 'A'){
            $facilities =  Facility::with('region_details')->with(['certificate' => function($q){
                $q->max('created_at');
            }])->where('name', 'like', \request()->alphabet.'%')->get();
            return response()->json(['facilities' => $facilities, 'regions' => Region::all()]);
            
        }else{
            $facilities = Facility::with('region_details')->with(['certificate' => function($q){
                $q->max('created_at');
            }])->get();
            return response()->json(['facilities' => $facilities, 'regions' => Region::all()]);
        }
        // return response()->json(['facilities' => Facility::with('region_details')->get(), 'regions' => Region::all()]);
    }
    function filterFacilities(){
        if(\request()->filter == 'R'){
            $facilities = Facility::with('region_details')->where('region_id', \request()->region)->get();
            return response()->json(['facilities' => $facilities, 'regions' => Region::all()]);
        }else if(\request()->filter == 'A'){
            $facilities =  Facility::with('region_details')->where('name', 'like', \request()->alphabet.'%')->get();
            return response()->json(['facilities' => $facilities, 'regions' => Region::all()]);
            
        }else{
            $facilities = Facility::with('region_details')->get();
            return response()->json(['facilities' => $facilities, 'regions' => Region::all()]);
        }
    }
    
    function show($id){
        $facility = Facility::with('region_details')->find($id);
        return $facility;
        
    }
}
