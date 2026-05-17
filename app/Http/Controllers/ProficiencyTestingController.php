<?php

namespace App\Http\Controllers;

use App\Library\Scoring;
use App\Models\Director;
use Illuminate\Http\Request;
use App\Models\ProficiencyTesting;
use App\Models\ProficiencyTestingApplication;
use App\Models\Receipt;
use Exception;
use App\Models\Specimen;
use App\Notifications\AppActionAlert;
use Illuminate\Database\QueryException;

class ProficiencyTestingController extends Controller
{
    public function __construct()
    {

        //Multiple exclude
        // $this->middleware('log')->except(['index','privacy']);
    }
    public function index()
    {
        $pts = ProficiencyTesting::all();
        return view('pt.index', compact('pts'));
    }

    public function create(ProficiencyTesting $pt)
    {
        $directors = Director::all();
        return view('pt.form', compact('pt', 'directors'));
    }

    public function store(Request $request)
    {

        if (\request()->file('instruction_file')) {

            $path = \request()->file('instruction_file')->store('instruction_files');
        } else {
            $path = null;
        }
        $pt = ProficiencyTesting::where('sdtl', $request->sdtl)->where('cycle', $request->cycle)->first();
        if ($pt) return redirect(\route('proficiency-testing.create'))->with(['message' => 'PT already exists', 'classname' => 'alert-danger'])->withInput();
        $validated = $request->validate([
            'sdtl' => 'required | numeric ',
            'cycle' => 'required | numeric ',
            'total_amount' => 'required | numeric ',
            'application_limit' => 'numeric ',
        ]);
        $request->merge(['instruction_file_path' => $path]);
        $pt = ProficiencyTesting::create($request->except('_token'));
        $pt->cert_setting()->create(['certificate_theme' => \request()->certificate_theme, 'certificate_given_at' => \request()->certificate_given_at, 'updated_by' => auth()->id(), 'pt_id' => $pt->id, 'director_id' => \request()->director_id]);

        return redirect(\route('proficiency-testing.index'))->with(['message' => 'PT sucessfully saved', 'classname' => 'alert-success']);
    }

    public function edit($id)
    {
        $pt = ProficiencyTesting::find($id);
        $directors = Director::all();

        return view('pt.form', compact('pt', 'directors'));
    }

    public function update($id)
    {
        if (\request()->file('instruction_file')) {

            $path = \request()->file('instruction_file')->store('instruction_files');
        } else {
            $path = null;
        }
        \request()->merge(['instruction_file_path' => $path]);
        $pt = ProficiencyTesting::find($id);
        if (\request()->application_limit < $pt->application_limit) {
            return redirect(\route('proficiency-testing.edit', $id))->with(['message' => 'Application limit must be greater than current limit.', 'classname' => 'alert-danger']);
        }
        try {

            $pt->update(\request()->except('_token'));
        } catch (Exception $e) {
            return redirect(\route('proficiency-testing.edit', $id))->with(['message' => $e->errorInfo[1] === 1062 ? 'PT already exists' : $e->errorInfo[2], 'classname' => 'alert-danger']);
        }
        return redirect(\route('proficiency-testing.index'))->with(['message' => 'PT sucessfully updated', 'classname' => 'alert-success']);
    }

    public function applicants($id)
    {
        $pt = ProficiencyTesting::find($id);
        $applications = $pt->applications;
        foreach ($applications as $key => $value) {
            $score = new Scoring($value->score);
            $applications[$key]->scoring = $score->get_performance();
            $applications[$key]->receipts = Receipt::where('proficiency_testing_application_id', $value->id)->where('status_id', '!=', 4)->first();
        }
        // dd($applications);
        return view('pt.applicantsList', compact('applications'));
    }

    public function sendSpecimen($id, $application_id)
    {
        $application = ProficiencyTestingApplication::with('user')->find($application_id);

        $application->specimens()->create(['sent_by' => auth()->id(), 'courier' => \request()->courier, 'tracking_number' => \request()->tracking_number]);
        // $application->update(['specimen_sent' => 1]);
        $application->user->notify(new AppActionAlert(
            __('alerts.specimen.sent.title'), 
            __('alerts.specimen.sent.message'), 
            \route('proficiency-testing.facility.apply', $id)
        ));                 
        return redirect(\route('proficiency-testing.applicants', $id))->with(['message' => 'Specimen sent successfully', 'classname' => 'alert-success']);
    }
    public function verifyPayment($id, $application_id)
    {
        $receipt = Receipt::with('userDetails')->where('proficiency_testing_application_id', $application_id)->where('status_id', '!=', 4)->first();
        $receipt->update(['status_id' => 1, 'updated_at' => \Carbon\Carbon::now()]);

        $receipt->userDetails->notify(new AppActionAlert(
            __('alerts.receipt.verified.title'),
            __('alerts.receipt.verified.message'),
            \route('proficiency-testing.facility.apply', $id)
        ));            
        return redirect(\route('proficiency-testing.applicants', $id))->with(['message' => 'Payment verified successfully', 'classname' => 'alert-success']);
    }
    public function rejectPayment($id, $application_id)
    {
        $receipt = Receipt::with(['userDetails', 'applicationDetails'])->where('proficiency_testing_application_id', $application_id)->where('status_id', '!=', 4)->first();
        // dd($receipt->userDetails);
        $receipt->update(['status_id' => 4, 'reject_reason' => \request()->reject_reason,  'updated_at' => \Carbon\Carbon::now()]);

        $receipt->userDetails->notify(new AppActionAlert(
            __('alerts.receipt.rejected.title'),
            __('alerts.receipt.rejected.message'),
            \route('proficiency-testing.facility.apply', $id)
        ));         

        return redirect(\route('proficiency-testing.applicants', $id))->with(['message' => 'Payment was rejected', 'classname' => 'alert-danger']);
    }

    public function saveScore($id, $application_id)
    {
        $application = ProficiencyTestingApplication::with('user')->find($application_id);
        $validated = \request()->validate([
            'score' => 'required | numeric | between: 0,20',
        ]);

        $application->update(['score' => \request()->score, 'scored_by' => \auth()->id(), 'scored_at' => \Carbon\Carbon::now()]);

        $application->user->notify(new AppActionAlert(
            __('alerts.score.saved.title'),
            __('alerts.score.saved.message'),
            \route('proficiency-testing.facility.apply', $id)
        ));         
        return redirect(route('proficiency-testing.applicants', $id))->with(['message' => 'Score saved successfully ', 'classname' => 'alert-success']);
    }
    public function showApplication($id, $application_id)
    {
        $pt = ProficiencyTesting::find($id);
        $application = ProficiencyTestingApplication::find($application_id);
        // set to false if no facility role
        $limit_reached = false;
        return view('pt.apply', compact('pt', 'application', 'limit_reached'));
        // $application->update(['score' => \request()->score, 'scored_by' => \auth()->id(), 'scored_at' => \Carbon\Carbon::now()]);
        // return redirect(route('proficiency-testing.applicants', $id))->with(['message' => 'Score saved successfully ', 'classname' => 'alert-success']);
    }

    function proceed_specimen(Request $request, $id, $application_id)
    {
        $application = ProficiencyTestingApplication::find($application_id);
        $application->specimens()->latest('created_at')->first()->update(['proceed_text' => $request->proceed_text, 'unboxing_video_path' => 'accepted', 'proceed_at' => \Carbon\Carbon::now()]);
        return redirect()->route('proficiency-testing.applicants', ['id' => $id])->with('message', 'Specimen accepted.')->with('classname', 'alert-success');
    }
}
