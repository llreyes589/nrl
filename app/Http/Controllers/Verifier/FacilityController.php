<?php

namespace App\Http\Controllers\Verifier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Certificate;
use Carbon\Carbon;
use \setasign\Fpdi\Fpdi;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PDF;
use Illuminate\Support\Facades\Mail;


class FacilityController extends Controller
{
    function index(){
        $facilities = Facility::all();
        return view('verifiers.facilities.index', compact('facilities'));
    }

    function show($id){
        $facility = Facility::with(['certificate' => function($q){
            $q->max('created_at');
        }])->find($id);
        // dd($facility);
        return view('verifiers.facilities.show', compact('facility'));
    }
    
    function updateVerified($id, $cert_id){
        $certificate = Certificate::find($cert_id);
        $certificate->update([
            'verified_by' => auth()->id(),
            'verified_at' => Carbon::now(),
        ]);
        return redirect()->route('verifiers.facilities.show', $certificate->facility_id)->with('message', 'Facility verified successfully.')->with('classname', 'alert-success');

    }
    function updateEndorse($id, $cert_id){
        $certificate = Certificate::find($cert_id);
        if(!isset($certificate->verified_by)){
            return redirect()->route('verifiers.facilities.show', $certificate->facility_id)->with('message', 'Invalid action.')->with('classname', 'alert-danger');
        }
        $certificate->update([
            'endorsed_by' => auth()->id(),
            'endorsed_at' => Carbon::now(),
        ]);
        return redirect()->route('verifiers.facilities.show', $certificate->facility_id)->with('message', 'Facility endorsed successfully.')->with('classname', 'alert-success');

    }

    function updateApproved($id, $cert_id){
        $certificate = Certificate::find($cert_id);
        if(!isset($certificate->endorsed_by)){
            return redirect()->route('verifiers.facilities.show', $certificate->facility_id)->with('message', 'Invalid action.')->with('classname', 'alert-danger');
        }
        $certificate->update([
            'approved_by' => auth()->id(),
            'approved_at' => Carbon::now(),
            // 'qrcode' => '/storage/'.$file,
        ]);
        return redirect()->route('verifiers.facilities.show', $certificate->facility_id)->with('message', 'Facility approved successfully.')->with('classname', 'alert-success');
        
    }
    
    
    function certificate(Request $request, $id, $key){
        $certificate = Certificate::where('key',$key)->first();
        $name = $certificate->facility->name;
        
        $qr = QrCode::format('png')->size(400)->generate(\route('verifyCertificate', ['key' => $key]));
        PDF::SetTitle($name.' Certificate');
        PDF::AddPage();
        
        // Example of Image from data stream ('PHP rules')
        $imgdata = base64_decode(base64_encode($qr));

        // The '@' character is used to indicate that follows an image data stream and not an image file name
        PDF::Image('@'.$imgdata, 150, 180, 50, 50);

        PDF::SetFont('Helvetica', '', 20);
        
        // set some text for example
        $txt = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';

        PDF::MultiCell(100, 40, $certificate->facility->name, 0, 'C', 0, 0, '55', '120', true, 0, false, true, 40, 'M');
        PDF::SetFont('Helvetica', '', 12);
        PDF::MultiCell(100, 40, $certificate->facility->address, 0, 'C', 0, 0, '55', '150', true, 0, false, true, 40, 'M');
        
        
        PDF::MultiCell(100, 40, $certificate->facility->accreditation_no, 0, 'C', 0, 0, '55', '135', true, 0, false, true, 40, 'M');
        
        PDF::Output($certificate->or_no.'.pdf', $request->pub ? $request->pub : "I");
        
    }

    function create_certificate(Request $request, $id){
        $facility = Facility::find($id);
        $request->merge([
            'facility_id' => $id,
            'created_at' => \Carbon\Carbon::now(), 
            'updated_at' => \Carbon\Carbon::now(),
            'key' => md5(microtime())
            ]);
        $facility->certificate()->insert($request->except('_token'));
        return redirect()->route('verifiers.facilities.show', $id)->with('message', 'Facility certificate successfully created.')->with('classname', 'alert-success');
    }

    function emailFacility(Request $request, $id, $cert_id){
        $certificate = Certificate::find($cert_id);
        $to_name = $certificate->facility->name;
        $to_email = $certificate->facility->email ? $certificate->facility->email : $certificate->facility->lab_email;
        $pdf = \route("certificate", ["id" => $id, "key" => $certificate->key, 'pub' => 'D']);
        // dd(\route("verifiers.facilities.certificate", ["id" => $id, "cert_id" => $cert_id]));
        $data = array('name'=> $to_name , 'pdf' => $pdf);
        Mail::send('emails.mail', $data, function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
            ->subject('Certificate Issuance');
            // ->attach(\route("certificate", ["id" => $id, "cert_id" => $cert_id]));
        $message->from('mail.nrldoh@gmail.com','Certificate Issuance');
        });
        $request->merge(['issued_by' => auth()->id(), 'issued_at' =>\Carbon\Carbon::now()]);
        $certificate->update($request->all());
        return redirect()->route('verifiers.facilities.index')->with('message', 'Certificate successfully issued.')->with('classname', 'alert-success');

    }

    function emailPrev(){
        $name = 'test';
        $pdf = 'lorem';
        return view('emails.mail', compact('name', 'pdf'));
    }


    

    
}
