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
        $facilities = Facility::with('region_details')->get();
        return view('verifiers.facilities.index', compact('facilities'));
    }

    function show($id){
        $facility = Facility::with(['certificate' => function($q){
            $q->max('created_at');
        }])->find($id);
        // dd($facility);
        return view('verifiers.facilities.show', compact('facility'));
    }
    
    function updatePrepared($id, $cert_id){
        $certificate = Certificate::find($cert_id);
        $certificate->update([
            'prepared_by' => auth()->id(),
            'prepared_at' => Carbon::now(),
        ]);
        return redirect()->route('verifiers.facilities.show', $certificate->facility_id)->with('message', 'Facility certificate prepared successfully.')->with('classname', 'alert-success');

    }
    function updateVerified($id, $cert_id){
        $certificate = Certificate::find($cert_id);
        if(!isset($certificate->prepared_by)){
            return redirect()->route('verifiers.facilities.show', $certificate->facility_id)->with('message', 'Invalid action.')->with('classname', 'alert-danger');
        }
        $certificate->update([
            'verified_by' => auth()->id(),
            'verified_at' => Carbon::now(),
        ]);
        return redirect()->route('verifiers.facilities.show', $certificate->facility_id)->with('message', 'Facility verified successfully.')->with('classname', 'alert-success');

    }

    function updateApproved($id, $cert_id){
        $certificate = Certificate::find($cert_id);
        if(!isset($certificate->prepared_by)){
            return redirect()->route('verifiers.facilities.show', $certificate->facility_id)->with('message', 'Invalid action.')->with('classname', 'alert-danger');
        }
        $certificate->update([
            'approved_by' => auth()->id(),
            'approved_at' => Carbon::now(),
            // 'qrcode' => '/storage/'.$file,
        ]);
        return redirect()->route('verifiers.facilities.show', $certificate->facility_id)->with('message', 'Facility approved successfully.')->with('classname', 'alert-success');
        
    }
    
    
    function certificate(Request $request, $key){
        // Image method signature:
        // Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
        $certificate = Certificate::where('key',$key)->first();
        // dd($certificate->prepared_by_details->signature);
        $name = $certificate->facility->name;
        $performace  = '';
        switch ($certificate->performance) {
            case 'E':
                $performace = 'EXCELLENT';
                break;
            case 'VS':
                $performace = 'VERY SATISFACTORY';
                break;
            case 'HS':
                $performace = 'HIGH SATISFACTORY';
                break;
                
            default:
                $performace = 'SATISFACTORY';
                break;
        }
        $pdf = new PDF();
        $qr = QrCode::format('png')->size(400)->generate(\route('verifyCertificate', ['key' => $key]));
        $pdf::SetTitle($name.' Certificate');
        $pdf::AddPage();
        $imgdata = base64_encode($qr);

        // get the current page break margin
        $bMargin = $pdf::getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $pdf::getAutoPageBreak();
        // disable auto-page-break
        $pdf::SetAutoPageBreak(false, 0);
        // set bacground image
        // $img_file = asset('images/cert_bg.png');
        // $pdf::Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $pdf::SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $pdf::setPageMark();

        $tagvs = array(
            'p' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)),
            'h2' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)),
        );
        $pdf::setHtmlVSpace($tagvs);
        // $pdf::SetCellPadding(0);
        // $pdf::SetFont('helvetica', '', 12);
        
        $doh_logo_html = 
        '
        <style>
            table{
                font-family: Times New Roman;
                background-image: url("'.asset('images/cert_bg.png').'");
            }
            table tr {
                text-align:center; 
            }
            p.header{
                font-size: 18pt;

            }
            p.cursive{
                font-family:Verdana, sans-serif;
                font-size: 16pt;
                font-style: italic;
                
            }
            p.name{
                font-family:Arial;
                font-size:12pt;
            }
            p.name{
                font-family:Arial;
                font-size:11pt;
            }
        </style>
            <table >
                <tr>
                    <td style="width:30%">    
                        <img src="https://upload.wikimedia.org/wikipedia/commons/6/6f/DOH_Logo.png" width="100"/>
                    </td>
                    <td style="width:40%">  
                        <br>  
                        <br>  
                        <br>  
                        <p class="header">Republic of the Philippines Department of Health <br>Manila </p>
                    </td>
                    <td style="width:30%">    
                        <img src="data:image/jpeg;base64,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" width="100"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                </tr>
                <tr style="text-align:center; ">
                    <td colspan="3">
                        <br>
                        <br>
                        <p class="cursive">This</p>
                        <br>
                    </td>
                </tr>
                <tr style="text-align:center; ">
                    <td colspan="3">
                        <p style="font-size: 26pt; font-weight: bold;">CERTIFICATE OF PROFICIENCY</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                </tr>
                <tr style="text-align:center;" >
                    <td style="width:15%"></td>
                    <td style="width:70%">
                        <br>
                        <br>
                        <br>
                        <p class="cursive">is hereby presented to</p>
                        <p style="font-size: 20pt; font-weight: bold;">
                            '.$name.'
                        </p>
                        <p style="font-size: 11pt;">
                            '.$certificate->facility->address.'
                        </p>
                        <br>
                        <p style="font-size: 14pt;">
                            Certificate No. '.$certificate->certificate_no.'
                        </p>
                        <p style="font-size: 14pt;">
                            Accreditation No. '.$certificate->facility->accreditation_no.'
                        </p>
                        <br>
                        <p class="cursive">for successful participation, passing and achieving</p>
                        <p style="font-size: 22pt; font-weight: bold;">
                        '.$performace.'
                        </p>
                        <p class="cursive">performance in the</p>
                        <p style="font-size: 20pt; font-weight: bold;">“CY 2018 PROFICIENCY TESTING SCHEME for SCREENING DRUGS OF ABUSE TESTING” </p>
                        <br>
                        <br>
                        <br>
                        <p class="cursive">Given this 30th day of April 2019</p>
                    </td>
                    <td style="width:15%"></td>
                </tr>
                <tr>
                    <td style="width:40%">    
                    </td>
                    <td style="width:40%">  
                    </td>
                    <td style="width:20%">    
                        <img src="data:image/png;base64,'.$imgdata.'">;
                    </td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                </tr>
                <tr style="text-align:center;">
                    <td style="width:45%">
                        <p class="name">JENNIFER D. MERCADO, MD, MMHoA, FPSP</p>
                        <p class="designation">Head, National Reference Laboratory</p>
                        <p class="designation">East Avenue Medical Center 
                            <span><img src="https://upload.wikimedia.org/wikipedia/commons/6/6f/DOH_Logo.png" width="10"></span>
                        </p>
                        </td>
                    <td style="width:10%"></td>
                    <td style="width:45%">
                        <p class="name">ATTY. NICOLAS B. LUTERO III, CESO III</p>
                        <p class="designation">Director IV</p>
                        <p class="designation">Health Facilities and Services Regulatory Bureau</p>
                    </td>
                </tr>
            </table>
            
        ';
        $pdf::writeHTML($doh_logo_html, true, false, true, false, '');
        
        $pdf::Output($certificate->or_no.'.pdf', $request->pub ? $request->pub : "I");
        
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
