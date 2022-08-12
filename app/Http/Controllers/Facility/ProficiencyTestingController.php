<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Models\ProficiencyTesting;
use App\Models\ProficiencyTestingApplication;
use Illuminate\Http\Request;

class ProficiencyTestingController extends Controller
{
    public function index()
    {
        $pts = ProficiencyTesting::all();
        return view('pt.index', compact('pts'));
    }
    public function apply($id)
    {
        $pt = ProficiencyTesting::find($id);
        $application = \request()->user()->ptApplications()->where('proficiency_testing_id', $id)->first();
        // dd($application);
        return view('pt.apply', compact('pt', 'application'));
    }
    public function saveApplication($id)
    {

        // dd(\request()->user()->id);
        $test_method = [['immunoassay_brand' => \request()->immunoassay_brand], ['instrument_type' => \request()->instrument_type, 'instrument_brand' => \request()->instrument_brand,]];
        // dd(json_encode($test_method));
        ProficiencyTestingApplication::create([
            'user_id' => \request()->user()->id,
            'proficiency_testing_id' => $id,
            'test_method_used' => json_encode($test_method),
            'cutoff_value' => \request()->cutoff_value,
            'methamphetamine' => \request()->methamphetamine,
            'tetrahydrocannabinol' => \request()->tetrahydrocannabinol,
        ]);
        return redirect(route('proficiency-testing.facility.index', $id))->with(['message' => 'Application successfully sent', 'classname' => 'alert-success']);
    }
}
