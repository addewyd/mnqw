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

function getpdflist($n) {
    $files = glob('../saved/*.pdf');
    usort($files, function($a, $b) {
        return filemtime($a) < filemtime($b);
    });
    return $files;
}

function gwdata($r) {
    $res = '';
    try {
        if(isset($r['fname'])) {
            //$fn = $r['fname'];
//            file_put_contents('../saved/s', json_encode($r));
            $fn = save_pdf($r);
            $files = getpdflist(10);
            $res = ['result' => $fn, 'req' => $r, 'files' => $files];
        } else {
            $res = ['result' => "bad file name", 'req' => $r, 'files' => $files];
        }
    }
    catch(Exception $e)
    {
        $res = ['result' => $e, 'req' => $r, 'files' => []];
    }
    returnResult($res);
}

function save_pdf($r) {
    $fname = $r['fname'];
    $fdate = $r['fdate'];
    $data = $r['data'];
    $gender = $r['gender'];
    $dur =   intval($r['dur']);
    $min = floor($dur / 60);
    $sec = $dur % 60;
    
    $sdur = "$min min $sec sec";
    
    $filename = '../saved/'.$fname."_" .$fdate;
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

    $pdf->SetXY(10, 36);
    $pdf -> Cell(0, 0, 'Patient: ' . $fname, 0, 1, 'L');
    $pdf->SetXY(130, 36);
    $pdf -> Cell(0, 0, 'Provider: Local', 0, 1, 'L');

    $pdf->SetXY(10, 40);
    $pdf -> Cell(0, 0, 'Location: Main Office', 0, 1, 'L');
    $pdf->SetXY(130, 40);
    $pdf -> Cell(0, 0, 'Battery ID 0000', 0, 1, 'L');


    $pdf->SetXY(10, 44);
    $pdf -> Cell(0, 0, 'Started: ' . $fdate, 0, 1, 'L');
    $pdf->SetXY(130, 44);
    $pdf -> Cell(0, 0, 'Test Duration: ' . $sdur, 0, 1, 'L');

    $pdf->SetXY(10, 50);

    $pdf->SetFont('Arial','',12);
    $pdf -> Cell(0, 10,'Assessment Results', 0, 1, 'L');

    $pdf->SetFont('Arial','',10);
    $pdf -> Cell(0, 6, '1) Please clearly state...', 0, 1, 'L');
    $pdf -> Cell(0, 6, '2) Describe the pertinent...', 0, 1, 'L');
    $pdf -> Cell(0, 6, '3) Please state...', 0, 1, 'L');

    
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


gwdata($_REQUEST);

