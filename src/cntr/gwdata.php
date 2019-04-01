<?php

require('fpdf/fpdf.php');
require('slogger.php');

function returnResult ($answer) {
    
        ob_start();
        ob_end_clean();
        Header('Cache-Control: no-cache');
        Header('Pragma: no-cache');
        header('Content-type: application/json; charset=UTF-8');
        echo json_encode($answer);
}


function gwdata($r) {
    $res = '';
    try {
        if(isset($r['fname'])) {
            //$fn = $r['fname'];
            //file_put_contents('../saved/s', print_r($r,  true));
            $fn = save_pdf($r);
            $res = ['result' => $fn, 'req' => $r];
        } else {
            $res = ['result' => "bad file name", 'req' => $r];
        }
    }
    catch(Exception $e)
    {
        $res = ['result' => $e, 'req' => $r];
    }
    returnResult($res);
}

function save_pdf($r) {
    $fname = $r['fname'];
    $fdate = $r['fdate'];
    $data = $r['data'];
    $gender = $r['gender'];
    
    $filename = '../saved/'.$fname."_" .$fdate;
    file_put_contents($filename, print_r($data,  true));
    
    
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);
    $pdf -> Cell(0, 18, 'Opioid Risk Tool (' . $gender . ')', 0, 1, 'C');
    $pdf -> Cell(0, 18, '[ORTF]', 0, 1, 'C');
    //$pdf->Output('F', $filename . '.pdf', true);
    $pdf->SetFont('Arial','',11);
    $pdf -> Cell(0, 14, 'Patient: ' . $fname, 0, 1, 'L');
    //$pdf -> Ln();
    $pdf -> Cell(0, 14, 'Started: ' . $fdate, 0, 1, 'L');
    //$pdf -> Ln();
    
    $pdf->SetFont('Arial','B',16);
    $pdf -> Cell(0, 18, 'QUESTIONS', 0, 1, 'L');
    
    $pdf->SetFont('Arial','',11);
    $pdf -> Ln();
    
    foreach($data as $d) {
        $pdf -> Cell(0, 12, $d['text'], 0, 1, 'L');
        $pdf -> Cell(0, 12, $d['answer'], 0, 1, 'L');
        //$pdf -> Ln();
    }
    
    $s = $pdf->Output('S', $filename . '.pdf', true);
    file_put_contents($filename . '.pdf', $s);
}

gwdata($_REQUEST);

