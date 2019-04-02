<?php

require('../../dist/cntr/fpdf/fpdf.php');

function save_pdf($r) {
    $fname = $r['fname'];
    $fdate = $r['fdate'];
    $data = $r['data'];
    $gender = $r['gender'];
    
    $filename = 'tst'.$fname."_" .$fdate;
    //file_put_contents($filename, print_r($data,  true));
    $g = '';
    if($gender === '') {
        $g = '';
    } else {
        $g = substr($gender, 0, 1);
    }
    
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);
    $pdf -> Cell(0, 10, 'Opioid Risk Tool (' . $gender . ')', 0, 1, 'C');
    $pdf -> Cell(0, 10, '[ORT'.$g.']', 0, 1, 'C');
    //$pdf->Output('F', $filename . '.pdf', true);

    $pdf->SetFont('Arial','', 8);

    $t2 =  "\t\t\t\t\t\t\t\t\t\t\t\t\t\t";
    $tabs = $t2.$t2.$t2.$t2;

    $pdf->SetXY(10, 30);
    $pdf -> Cell(0, 0, 'Patient: ' . $fname, 0, 1, 'L');
    $pdf->SetXY(130, 30);
    $pdf -> Cell(0, 0, 'Provider: Local', 0, 1, 'L');

    $pdf->SetXY(10, 34);
    $pdf -> Cell(0, 0, 'Location: Main Office', 0, 1, 'L');
    $pdf->SetXY(130, 34);
    $pdf -> Cell(0, 0, 'Battery ID 0000', 0, 1, 'L');


    $pdf->SetXY(10, 38);
    $pdf -> Cell(0, 0, 'Started: ' . $fdate, 0, 1, 'L');
    $pdf->SetXY(130, 38);
    $pdf -> Cell(0, 0, 'Test Duration: ' . $fdate, 0, 1, 'L');

    $pdf->SetXY(10, 48);


    $pdf->SetFont('Arial','',12);
    $pdf -> Cell(0, 10,'Assessment Results', 0, 1, 'L');

    $pdf->SetFont('Arial','',10);
    $pdf -> Cell(0, 8, '1) Please clearly state...', 0, 1, 'L');
    $pdf -> Cell(0, 8, '2) Describe the pertinent...', 0, 1, 'L');
    $pdf -> Cell(0, 8, '3) Please state...', 0, 1, 'L');

    
    $pdf->SetFont('Arial','B',14);
    $pdf -> Cell(0, 10, 'QUESTIONS', 0, 1, 'L');
    
    $pdf->SetFont('Arial','',10);
    foreach($data as $d) {
        $pdf -> Cell(0, 4, $d['text'], 0, 1, 'L');
        $pdf -> Cell(0, 8, $d['answer'], 0, 1, 'L');
        //$pdf -> Ln();
    }
    
    $s = $pdf->Output('S', $filename . '.pdf', true);
    file_put_contents($filename . '.pdf', $s);
    return $filename;
}
$r = json_decode('{"data":[{"text":"Family history of substance abuse? Alcohol","answer":"yes"},{"text":"Family history of substance abuse? Illegal drugs","answer":"no"},{"text":"Family history of substance abuse? Prescription drugs","answer":"yes"},{"text":"Personal history of substance abuse? Alcohol","answer":"no"},{"text":"Personal history of substance abuse? Illegal drugs","answer":"yes"},{"text":"Personal history of substance abuse? Prescription drugs","answer":"no"},{"text":"Family history of substance abuse between 16 years old and 45 years old?","answer":"no"},{"text":"History of preadolescent sexual abuse?","answer":"yes"},{"text":"Psychologic Desease: ADD, OCD, bipolar, schizophrenia","answer":"yes"},{"text":"Psychologic Desease: Depression","answer":"no"}],"fname":"fname_lname","fdate":"2019-04-01","gender":"Female"}', true);

save_pdf($r);

