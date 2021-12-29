<?php

namespace App\Http\Controllers\Verifier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Certificate;
use App\Models\Region;
use App\Models\Setting;
use Carbon\Carbon;
use \setasign\Fpdi\Fpdi;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PDF;
use Illuminate\Support\Facades\Mail;


class FacilityController extends Controller
{
    function index(Request $request){
        $facilities = Facility::with('region_details')->with(['certificate' => function($q){
            $q->max('created_at');
        }])->get();
        $regions = Region::all();
        return view('verifiers.facilities.index', compact('facilities', 'regions'));
        // dd('test');
        if($request->filter == 'R'){
            $facilities = Facility::with('region_details')->with(['certificate' => function($q){
                $q->max('created_at');
            }])->where('region_id', $request->region)->get();
            return view('verifiers.facilities.index', compact('facilities', 'regions'));
        }else if($request->filter == 'A'){
            $facilities =  Facility::with('region_details')->with(['certificate' => function($q){
                $q->max('created_at');
            }])->where('name', 'like', $request->alphabet.'%')->get();
            return view('verifiers.facilities.index', compact('facilities', 'regions'));
            
        }else{
            $facilities = Facility::with('region_details')->with(['certificate' => function($q){
                $q->max('created_at');
            }])->get();
            return view('verifiers.facilities.index', compact('facilities', 'regions'));
        }
    }

    function show($id){
        $facility = Facility::with(['certificate' => function($q){
            $q->max('created_at');
        }])->find($id);
        $certificates = Certificate::with('prepared_by_details')->with('verified_by_details')->with('approved_by_details')->where('facility_id', $id)->where('facility_verified_at', '!=', null)->get();
        // dd($certificates);
        return view('verifiers.facilities.show', compact('facility', 'certificates'));
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
        $settings = Setting::find(1);
        $given = \Carbon\Carbon::parse($settings->certificate_given_at);
        \Carbon\Carbon::setToStringFormat('jS \d\a\y \o\f F Y');
        // Image method signature:
        // Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
        $certificate = Certificate::where('key',$key)->first();
        // dd($certificate->prepared_by_details->signature);
        $name = $certificate->facility->name;
        $performace  = '';
        switch ($certificate->performance) {
            case 'A':
                $performace = 'ACCEPTABLE';
                break;
            case 'E':
                $performace = 'EXCELLENT';
                break;
            case 'HS':
                $performace = 'HIGHLY SATISFACTORY';
                break;
                
            default:
                $certificate->performace;
                break;
        }
        $pdf = new PDF();
        $qr = QrCode::format('png')->size(300)->merge('http://1.bp.blogspot.com/-YSy27PtRhnM/TVOIUvjmCsI/AAAAAAAAAKE/dyvKbvJxN4M/s1600/nrl+eamc+logo.jpg', .2, true)->generate(\route('verifyCertificate', ['key' => $key]));
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
        $img_file = asset('images/cert_bg.png');
        $pdf::Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $pdf::SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $pdf::setPageMark();
        

        $tagvs = array(
            'p' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)),
            'h2' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)),
            'img' => array(0 => array('h' => -2, 'n' => -2), 1 => array('h' => -2, 'n'=> -2)),
        );
        $pdf::setHtmlVSpace($tagvs);
        $pdf::setImageScale ( PDF_IMAGE_SCALE_RATIO );

        $pdf::setJPEGQuality ( 90 );

        // $pdf::Image ( $img_file );
        // Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
        if($certificate->approved_by_details){
            $pdf::Image(asset('storage/'.$certificate->approved_by_details->signature), 35, 255, 40, 10, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
            $pdf::Image(asset('images/lutero.png'), 137, 253, 40, 10, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
        }
        // $pdf::SetCellPadding(0);
        // $pdf::SetFont('helvetica', '', 12);
        $head_html = '
            <table>
            <tr style="text-align:center;">
                <td style="width:50%">
                    <p class="name">JENNIFER D. MERCADO, MD, MMHoA, FPSP</p>
                    <p class="designation">Head, National Reference Laboratory
                    </p>
                    <p class="designation">East Avenue Medical Center 
                    </p>
                    </td>
                <td style="width:50%">
                    <p class="name">ATTY. NICOLAS B. LUTERO III, CESO III</p>
                    <p class="designation">Director IV</p>
                    <p class="designation">Health Facilities and Services Regulatory Bureau</p>
                </td>
            </tr>            
        </table>            
        ';
        $doh_logo_html = 
        '
        <style>
            table{
                font-family: Times New Roman;
            }
            table tr {
                text-align:center; 
            }
            p.header{
                font-size: 16pt;

            }
            p.cursive{
                font-family:Verdana, sans-serif;
                font-size: 14pt;
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
                        <p class="header">Republic of the Philippines Department of Health <br>Manila </p>
                    </td>
                    <td style="width:30%">    
                        <img src="http://1.bp.blogspot.com/-YSy27PtRhnM/TVOIUvjmCsI/AAAAAAAAAKE/dyvKbvJxN4M/s1600/nrl+eamc+logo.jpg" width="100"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                </tr>
                <tr style="text-align:center; ">
                    <td colspan="3">
                        <br>
                        <br>
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
                    <td style="width:10%"></td>
                    <td style="width:80%">
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
                    </td>
                    <td style="width:10%"></td>
                </tr>
                <tr style="text-align:center;" >
                    <td style="width:5%"></td>
                    <td style="width:90%">
                        <p class="cursive">performance in the</p>
                        <p style="font-size: 20pt; font-weight: bold;">"'.$settings->certificate_theme.'" </p>
                        <br>
                        <p class="cursive">
                            Given this '.$given.'
                        </p>
                    </td>
                    <td style="width:5%"></td>
                </tr>
                <tr>
                    <td style="width:40%">    
                    </td>
                    <td style="width:40%">  
                    </td>
                    <td style="width:20%">   
                         
                        <img src="@'.$imgdata.'" />
                    </td>
                </tr>
            </table>

            
        ';
        $pdf::writeHTML($doh_logo_html, true, false, true, false, '');
        $pdf::writeHTMLCell(0, 0, 10, 260, $head_html, 0, 1, 0, true, '', true);
        
        
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

    function search(Request $request){
        $regions = Region::all();
        $req = $request->all();
        if($request->filter == 'R'){
            $facilities = Facility::with('region_details')->where('region_id', $request->region)->get();
            return view('verifiers.facilities.index', compact('facilities', 'regions', 'req'));
        }else if($request->filter == 'A'){
            return Facility::with('region_details')->where('name', 'like', $request->alphabet.'%')->get();
            return view('verifiers.facilities.index', compact('facilities', 'regions', 'req'));
            
        }else{
            $facilities = Facility::with('region_details')->get();
            return view('verifiers.facilities.index', compact('facilities', 'regions'));

        }
    }


    

    
}
