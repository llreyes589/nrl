<?php

namespace App\Http\Controllers\Verifier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Facility;
use Carbon\Carbon;
use \setasign\Fpdi\Fpdi;

class FacilityController extends Controller
{
    function index(){
        $facilities = Facility::all();
        return view('verifiers.facilities.index', compact('facilities'));
    }

    function show($id){
        $facility = Facility::find($id);
        return view('verifiers.facilities.show', compact('facility'));
    }
    
    function updateVerified($id){
        $facility = Facility::find($id);
        $facility->update([
            'verified_by' => auth()->id(),
            'verified_at' => Carbon::now(),
        ]);
        return redirect()->route('verifiers.facilities.show', $id)->with('message', 'Facility verified successfully.')->with('classname', 'alert-success');

    }
    function updateEndorse($id){
        $facility = Facility::find($id);
        $facility->update([
            'endorsed_by' => auth()->id(),
            'endorsed_at' => Carbon::now(),
        ]);
        return redirect()->route('verifiers.facilities.show', $id)->with('message', 'Facility endorsed successfully.')->with('classname', 'alert-success');

    }

    function updateApproved($id){
        $facility = Facility::find($id);
        $facility->update([
            'approved_by' => auth()->id(),
            'approved_at' => Carbon::now(),
        ]);
        return redirect()->route('verifiers.facilities.show', $id)->with('message', 'Facility approved successfully.')->with('classname', 'alert-success');

    }

    function certificate($id){
        // Create new Landscape PDF
        $facility = Facility::find($id);
        $name = $facility->name;
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

        // render PDF to browser
        $pdf->Output();
    }
    

    
}
