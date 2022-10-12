<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function profile()
    {
        $user = auth()->user();
        return view('facility.profile', compact('user'));
    }
    public function profileUpdate()
    {
        $user = auth()->user();

        if (\request()->file('certificate_file')) {

            $analyst_certificate_file = \request()->file('certificate_file')->store('analyst_certificate_files');
        } else {
            $analyst_certificate_file = $user->profile->analyst_certificate_file;
        }
        if (\request()->file('lto')) {

            $lto_file = \request()->file('lto')->store('lto_files');
        } else {
            $lto_file = $user->profile->lto_file;
        }

        request()->merge(['analyst_certificate_file' => $analyst_certificate_file, 'lto_file' => $lto_file]);
        $user->profile->update(\request()->except('_token'));
        return redirect()->back()->with(['message' => 'Profile updated.', 'classname' => 'alert-success']);
        // dd(request()->all());
        // return view('facility.profile', compact('user'));
    }
}
