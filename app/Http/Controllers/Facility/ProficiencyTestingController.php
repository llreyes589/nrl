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
        $application = \request()->user()->ptApplications()->with('receipts', 'receipts.status_details')->where('proficiency_testing_id', $id)->first();
        $limit_reached = $pt->application_limit <= $pt->applications()->count();
        // dd($application);
        return view('pt.apply', compact('pt', 'application', 'limit_reached'));
    }
    public function saveApplication($id)
    {
        $pt = ProficiencyTesting::find($id);
        $limit_reached = $pt->application_limit <= $pt->applications()->count();
        if ($limit_reached) return 'Invalid Request. Application limit has been reached.';
        // dd(\request()->user()->id);

        $test_method = [['immunoassay_brand' => \request()->test_method_used == 'itk' ? \request()->immunoassay_brand : null], ['instrument_type' => \request()->test_method_used == 'inst' ? \request()->instrument_type : null, 'instrument_brand' => \request()->test_method_used == 'inst' ?  \request()->instrument_brand : null,]];
        // dd(json_encode($test_method));
        ProficiencyTestingApplication::create([
            'user_id' => \request()->user()->id,
            'proficiency_testing_id' => $id,
            'test_method_used' => json_encode($test_method),
            // 'cutoff_value' => \request()->cutoff_value,
            'methamphetamine' => \request()->methamphetamine,
            'tetrahydrocannabinol' => \request()->tetrahydrocannabinol,
            'mode_of_payment' => \request()->mode_of_payment
        ]);
        return redirect(route('proficiency-testing.facility.apply', $id))->with(['message' => 'Application successfully sent', 'classname' => 'alert-success']);
    }

    public function saveReceipt($id)
    {

        $validated = \request()->validate([
                'receipt' => 'required|mimes:jpg,png,pdf',
            ]);

        if (\request()->file('receipt')) {

            $path = \request()->file('receipt')->store('receipts');
        } else {
            $path = '';
        }
        // dd(\request()->user()->ptApplications()->where('proficiency_testing_id', $id)->first()->receipts());
        \request()->user()->ptApplications()->where('proficiency_testing_id', $id)->first()->receipts()->create(['file_path' => $path]);
        return redirect(route('proficiency-testing.facility.apply', $id))->with(['message' => 'Receipt successfully submitted', 'classname' => 'alert-success']);
    }

    public function receiveSpecimen($id)
    {
        if (\request()->status === 'reject') {

            $validated = \request()->validate([
                'reject_description' => 'required',
                'unboxing_video_path' => 'required|mimes:mp4,mov,jpg,png',
            ], [
                'unboxing_video_path.required' => 'Reject file is required.',
                'reject_description.required' => 'Reject description is required.'
            ]);
        } else {
            $validated = \request()->validate([
                'accepted_bottles' => 'numeric|nullable',
            ]);
        }
        if (\request()->file('unboxing_video_path')) {

            $path = \request()->file('unboxing_video_path')->store('unboxing_videos');
        } else {
            $path = 'accepted';
        }
        // dd(\request()->user()->ptApplications()->where('proficiency_testing_id', $id)->first()->specimens);
        \request()->user()->ptApplications()->where('proficiency_testing_id', $id)->first()->specimens()->latest('created_at')->first()->update(['unboxing_video_path' => $path, 'accepted_bottles' => \request()->accepted_bottles ? \request()->accepted_bottles : null, 'reject_description' => \request()->reject_description]);
        return redirect(route('proficiency-testing.facility.apply', $id))->with(['message' => 'Specimen successfully received', 'classname' => 'alert-success']);
    }

    public function saveResult($id)
    {
        $validated = \request()->validate([
                'result' => 'required|mimes:jpg,png,pdf',
            ]);
        if (\request()->file('result')) {

            $path = \request()->file('result')->store('results');
        } else {
            $path = null;
        }

        \request()->user()->ptApplications()->where('proficiency_testing_id', $id)->update(['result_path' => $path, 'result_uploaded_at' => \Carbon\Carbon::now()]);
        return redirect(route('proficiency-testing.facility.apply', $id))->with(['message' => 'Result sent successfully ', 'classname' => 'alert-success']);
    }
}
