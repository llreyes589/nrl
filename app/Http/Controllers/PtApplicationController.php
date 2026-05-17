<?php

namespace App\Http\Controllers;

use App\Models\ProficiencyTestingApplication;
use App\Models\User;
use Illuminate\Http\Request;
use \App\Library\Scoring;
use App\Models\Certificate;
use App\Notifications\AppActionAlert;

class PtApplicationController extends Controller
{
    function create_certificate($id, $application_id)
    {
        $application = ProficiencyTestingApplication::with('user')->find($application_id);
        // dd($application->user->id);
        if ($application->score > 8)
            return redirect()->route('proficiency-testing.applicants', ['id' => $id, 'application_id' => $application_id])->with('message', 'Invalid action.')->with('classname', 'alert-danger');

        \request()->merge([
            'facility_id' => $application->user->id,
            'proficiency_testing_application_id' => $application_id,
            'prepared_by' => auth()->user()->id,
            'prepared_at' => \Carbon\Carbon::now(),
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
            'key' => md5(microtime())
        ]);
        try {
            $score = new Scoring($application->score);
            \request()->merge(['performance' => $score->get_performance()[0], 'validity' => '']);
            $application->certificate()->insert(\request()->except('_token'));
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }

        // user notification
        $application->user->notify(new AppActionAlert(
            __('alerts.certificate.created.title'),
            __('alerts.certificate.created.message'),
            \route('proficiency-testing.facility.apply', $id)
        ));         

        // verifier notif
        $verifier = User::role('verifier')->first();
        $verifier->notify(new AppActionAlert(
            __('alerts.certificate.created.title'),
            __('alerts.certificate.created.verifier.message', ['facility_name' => $application->user->name]),
            \route('proficiency-testing.applicants', $id)
        )); 

        return redirect()->route('proficiency-testing.applicants', ['id' => $id, 'application_id' => $application_id])->with('message', 'Certificate successfully created.')->with('classname', 'alert-success');
    }
    function update_certificate($id, $application_id, $cert_id)
    {
        $cert = Certificate::find($cert_id);
        try {
            $cert->update(['or_no' => \request()->edit_or_no, 'certificate_no' => \request()->edit_certificate_no]);
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
        return redirect()->route('proficiency-testing.applicants', ['id' => $id, 'application_id' => $application_id])->with('message', 'Certificate successfully updated.')->with('classname', 'alert-success');
    }

    function verify_certificate($id, $application_id)
    {
        $application = ProficiencyTestingApplication::with('user')->find($application_id);
        if (!isset($application->certificate->prepared_by)) {
            return redirect()->route('proficiency-testing.applicants.showApplication', ['id' => $id, 'application_id' => $application_id])->with('message', 'Invalid action.')->with('classname', 'alert-danger');
        }
        $application->certificate->update([
            'verified_by' => auth()->id(),
            'verified_at' => \Carbon\Carbon::now(),
        ]);

        // user notification
        $application->user->notify(new AppActionAlert(
            __('alerts.certificate.verified.title'),
            __('alerts.certificate.verified.message'),
            \route('proficiency-testing.facility.apply', $id)
        ));         

        // head notif
        $head = User::role('head')->first();
        $head->notify(new AppActionAlert(
            __('alerts.certificate.verified.title'),
            __('alerts.certificate.verified.head.message', ['facility_name' => $application->user->name]),
            \route('proficiency-testing.applicants', $id)
        ));         
        return redirect()->route('proficiency-testing.applicants', ['id' => $id, 'application_id' => $application_id])->with('message', 'Certificate verified successfully.')->with('classname', 'alert-success');
    }

    function approve_certificate($id, $application_id)
    {
        $application = ProficiencyTestingApplication::with('user')->find($application_id);

        if (!isset($application->certificate->prepared_by)) {
            return redirect()->route('proficiency-testing.applicants', ['id' => $id, 'application_id' => $application_id])->with('message', 'Invalid action.')->with('classname', 'alert-danger');
        }
        $application->certificate->update([
            'approved_by' => auth()->id(),
            'approved_at' => \Carbon\Carbon::now(),
        ]);

        // user notification
        $application->user->notify(new AppActionAlert(
            __('alerts.certificate.approved.title'),
            __('alerts.certificate.approved.message'),
            \route('proficiency-testing.facility.apply', $id)
        ));        
        return redirect()->route('proficiency-testing.applicants', ['id' => $id, 'application_id' => $application_id])->with('message', 'Certificate approved successfully.')->with('classname', 'alert-success');
    }

    function delete_application($id, $application_id)
    {
        $application = ProficiencyTestingApplication::find($application_id);
        $application->delete();
        return redirect()->route('proficiency-testing.applicants', ['id' => $id])->with('message', 'Application successfully deleted.')->with('classname', 'alert-danger');
    }
}
