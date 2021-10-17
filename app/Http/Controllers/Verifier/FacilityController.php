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
    
    function updateVerified($id){
        $certificate = Certificate::find($id);
        $certificate->update([
            'verified_by' => auth()->id(),
            'verified_at' => Carbon::now(),
        ]);
        return redirect()->route('verifiers.facilities.show', $certificate->facility_id)->with('message', 'Facility verified successfully.')->with('classname', 'alert-success');

    }
    function updateEndorse($id){
        $certificate = Certificate::find($id);
        $certificate->update([
            'endorsed_by' => auth()->id(),
            'endorsed_at' => Carbon::now(),
        ]);
        return redirect()->route('verifiers.facilities.show', $certificate->facility_id)->with('message', 'Facility endorsed successfully.')->with('classname', 'alert-success');

    }

    function updateApproved($id){
        $certificate = Certificate::find($id);
        $certificate->update([
            'approved_by' => auth()->id(),
            'approved_at' => Carbon::now(),
            // 'qrcode' => '/storage/'.$file,
        ]);
        return redirect()->route('verifiers.facilities.show', $certificate->facility_id)->with('message', 'Facility approved successfully.')->with('classname', 'alert-success');
        
    }
    
    
    function certificate($id){
        $facility = Facility::find($id);
        $name = $facility->name;
        
        $qr = QrCode::format('png')->size(400)->generate(\route('verifiers.facilities.updateVerified', $id));
        // dd(base64_encode($qr));
        PDF::SetTitle('Hello World');
        PDF::AddPage();
        
        // Example of Image from data stream ('PHP rules')
        $imgdata = base64_decode(base64_encode($qr));

        // The '@' character is used to indicate that follows an image data stream and not an image file name
        PDF::Image('@'.$imgdata, 150, 180, 50, 50);

        // PDF::SetFont('Helvetica', 'B', 30);
        // // $pdf->SetTextColor(0,0,0);
        // PDF::SetXY(20, 315); // set the position of the box
        // PDF::Cell(0, 10, utf8_decode($name), 0, 0, 'C'); // add the text, align to Center of cell
        // set some text for example
        // set font
        PDF::SetFont('Helvetica', '', 20);
        
        
        // set cell padding
        // PDF::setCellPaddings(1, 1, 1, 1);
        
        // set cell margins
        // PDF::setCellMargins(1, 1, 1, 1);

        // set color for background
        // PDF::SetFillColor(255, 255, 127);
        
        // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
        
        // set some text for example
        $txt = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
        
        // Multicell test
        // PDF::MultiCell(55, 5, '[LEFT] '.$txt, 1, 'L', 1, 0, '', '', true);
        // PDF::MultiCell(55, 5, $facility->name, 1, 'C', 1, 0, '20', '235', true);
        // Vertical alignment
        // PDF::Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);
        PDF::MultiCell(100, 40, $facility->name, 0, 'C', 0, 0, '55', '120', true, 0, false, true, 40, 'M');
        PDF::SetFont('Helvetica', '', 12);
        PDF::MultiCell(100, 40, $facility->address, 0, 'C', 0, 0, '55', '150', true, 0, false, true, 40, 'M');
        
        // Cert no
        // PDF::SetXY(20, 365);
        PDF::MultiCell(100, 40, $facility->accreditation_no, 0, 'C', 0, 0, '55', '135', true, 0, false, true, 40, 'M');
        // PDF::MultiCell(0, 20, 'Certificate Number: '.$facility->accreditation_no, 0, "C");

        // Accreditation no
        // PDF::SetXY(20, 380);
        // PDF::MultiCell(0, 20, 'Accreditation Number: '.$facility->accreditation_no, 0, "C");
        PDF::Output('hello_world.pdf');
        
        // return base64_encode($qr);
        // echo `<img src="{$qr}">`;
        // return $qr;
        // dd();
        // Create new Landscape PDF
        $pdf = new FPDI('p'  ,'pt', 'Letter');
        
        // Reference the PDF you want to use (use relative path)
        $pagecount = $pdf->setSourceFile( 'docs/test.pdf');

        // Import the first page from the PDF and add to dynamic PDF
        $tpl = $pdf->importPage(1);
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(true,0);

        // Use the imported page as the template
        $pdf->useTemplate($tpl);

        // adding a Cell using:
        // $pdf->Cell( $width, $height, $text, $border, $fill, $align);
        
        // Name
        $pdf->SetFont('Helvetica', 'B', 30);
        // $pdf->SetTextColor(0,0,0);
        $pdf->SetXY(20, 315); // set the position of the box
        $pdf->Cell(0, 10, utf8_decode($name), 0, 0, 'C'); // add the text, align to Center of cell
        
        // Address
        $pdf->SetFont('Helvetica', '', 12);
        // $pdf->SetTextColor(255,255,255);
        $pdf->SetXY(20, 335);
        $pdf->MultiCell(0, 20, $facility->address, 0, "C");

        // Cert no
        $pdf->SetXY(20, 365);
        $pdf->MultiCell(0, 20, 'Certificate Number: '.$facility->accreditation_no, 0, "C");

        // Accreditation no
        $pdf->SetXY(20, 380);
        $pdf->MultiCell(0, 20, 'Accreditation Number: '.$facility->accreditation_no, 0, "C");
        // dd(request()->root().$facility->qrcode);
        // $pdf->Image('http://assets-global.website-files.com/5e78ee1f2f0ca263f9b67c56/5f04a4babe7bb91e10639f9a_ssat-at-home01%402x.png',60,30,90,0, "PNG");
        // $pdf->Image('@'.base64_decode($qr));
        // render PDF to browser
        // $pdf->Output();
    }

    function create_certificate(Request $request, $id){
        $facility = Facility::find($id);
        $request->merge(['facility_id' => $id, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()]);
        $facility->certificate()->insert($request->except('_token'));
        return redirect()->route('verifiers.facilities.show', $id)->with('message', 'Facility certificate successfully created.')->with('classname', 'alert-success');
    }
    

    
}
