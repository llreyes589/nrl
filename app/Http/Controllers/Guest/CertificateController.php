<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Certificate;

class CertificateController extends Controller
{
    function verify($key){
        $cert_details = Certificate::where('key',$key)->first();
        return view('guest.verify', compact('cert_details'));
    }
    
    function updateVerifyByFacility(Request $request, $key){
        $cert_details = Certificate::where('key',$key)->first();
        $request->merge(['facility_verified_at' => \Carbon\Carbon::now()]);
        $cert_details->update($request->except('_token'));
        return redirect()->route('verifyCertificate', $key)->with('message', 'Certificate successfully verified.')->with('classname', 'alert-success');

    }
}
