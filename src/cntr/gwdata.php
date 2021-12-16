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
    $res = ''; $files = [];
    try {
        if(isset($r['fname'])) {
            //$fn = $r['fname'];
//            file_put_contents('../saved/s', json_encode($r));
            $fn = save_pdf($r);
            $files = getpdflist(10);
            $res = ['result' => $fn, 'req' => $r, 'files' => $files];
        }
        if(isset($r['init'])) {
            $res = ['result' => trim(file_get_contents('pass'))];
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

function DrawTable($header, $data, $t)
{
    // Column widths
    //$w = array(40, 35, 40, 45);
    
    // Header
        $t->SetFont('Arial','B', 9);

    foreach($header as $h) {
        $t->Cell($h[1],7,$h[0],1,0,'C');
    }
    $t->Ln();
            $t->SetFont('Arial','', 9);

    // Data
    foreach($data as $row)
    {
        if($row[0] == '_last') {
            $t->Cell($header[0][1],0,'','T');
            $t->Cell($header[1][1],0,'','T');
            $t->Cell($header[2][1],0,'','T');
            $t->Cell($header[3][1],0,'','T');
        }
        else {
            $t->Cell($header[0][1],6,$row[0], $row[4]? 'LTR':'LR');
            $t->Cell($header[1][1],6,$row[1],$row[4]? 'LTR':'LR');
            $t->Cell($header[2][1],6,$row[2],$row[4]? 'LTR':'LR');
            $t->Cell($header[3][1],6,$row[3],$row[4]? 'LTR':'LR');
            $t->Ln();
        }
    }
}

function rspd($x, $g, $gn) {
    if ($gn== $g && $x['answer']=='yes')
        return 'X';
    else return ' ';
}

function save_pdf($r) {
    $fname = $r['fname'];
    $lastname = $r['lastname'];
    $fdate = $r['fdate'];
    $data = $r['data'];
    $gender = $r['gender'];
    $dur =   intval($r['dur']);
    $min = floor($dur / 60);
    $sec = $dur % 60;
    $score = $r['score'];
    
    $sdur = "$min min $sec sec";
    
    $dd = date_create();
    $fdate = date_format($dd, "Y-m-d");
    $ddate = date_format($dd, "Ymd-His");
    $filename = '../saved/'.$lastname."_" .$fname."_".$ddate;
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
    $pdf -> Cell(0, 0, 'Patient: ' . $fname . " " . $lastname, 0, 1, 'L');
    $pdf->SetXY(130, 36);
    $pdf -> Cell(0, 0, 'Provider: RES Physical Medicine and Rehab', 0, 1, 'L');

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

    $pdf->SetFont('Arial','B',7);
    $pdf -> Cell(0, 4, '1) Please clearly state the medical need for administering this assessment:', 0, 1, 'L');
    $pdf->SetFont('Arial','',7);
    $pdf -> Cell(0, 4, 'See patient record for medical necessity', 0, 1, 'L');
    $pdf -> Ln();
    
    $pdf->SetFont('Arial','B',7);
    
    $pdf -> Cell(0, 4, '2) Describe the pertinent findings of of the assessment', 0, 1, 'L');
    $pdf->SetFont('Arial','',7);
    $pdf -> Cell(0, 4, 'Total: ' . $score, 0, 1, 'L');
    
    $pdf -> Ln();
    $pdf -> Cell(0, 4, 'The ORT may identify patients at high risk for misuse, '
            . 'and who might benefit more from other modalities of plan control'
            . ' besides narcotics (prinarily applicable with chronic pain', 0, 1, 'L');

    $pdf -> Ln();
    $pdf -> SetX(40);
    $pdf -> Cell(0, 4, 'Score of 0-3 Low Risk of Opioid Addiction', 0, 1, 'L');
    $pdf -> SetX(40);
    $pdf -> Cell(0, 4, 'Score of 4-7 Moderate Risk of Opioid Addiction', 0, 1, 'L');
    $pdf -> SetX(40);
    $pdf -> Cell(0, 4, 'Score of >= 8 High Risk of Opioid Addiction', 0, 1, 'L');
    
    $pdf -> Ln();
    
    $pdf->SetFont('Arial','B',7);
    
    $pdf -> Cell(0, 4, '3) Please state the plan of treatment as a result of the assessment:', 0, 1, 'L');
    $pdf->SetFont('Arial','',7);
    $pdf -> Cell(0, 4, 'See patient record for medical findings', 0, 1, 'L');
    $pdf -> Ln();
    
    $pdf->SetFont('Arial','B',14);
    $pdf -> Cell(0, 10, 'QUESTIONS', 0, 1, 'L');
    
    $pdf->SetFont('Arial','', 9);
    foreach($data as $d) {
        $pdf -> Cell(0, 4, $d['text'], 0, 1, 'L');
        $pdf -> Cell(0, 8, $d['answer'], 0, 1, 'L');
        //$pdf -> Ln();
    }
    
    $s = $pdf->Output('S', $filename . '', true);
    file_put_contents($filename . '_ort_grading.pdf', $s);
 /*   
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);
    $pdf -> Cell(0, 10, 'Opioid Risk Tool (' . $gender . ')', 0, 1, 'C');
    $pdf -> Cell(0, 10, '[ORT'.$g.']', 0, 1, 'C');
    
    $pdf->SetFont('Arial','',13);
    $pdf -> Cell(64, 8, 'Name: ' . $fname , 0, 0, 'L');
    $pdf -> Cell(40, 8, 'Date: '. $fdate , 0, 1, 'L');
    

    $h = [
       ['Mark each box that applies', 60],
       ['', 64],
       ['Female', 14],
       ['Male', 14]
    ];
    
    $d = [
        ['1) Family history of substance abuse','Alcohol','['. 
            rspd($data[0],'Female', $gender) .']',
                '['. rspd($data[0],'Male', $gender) .']', false],
        ['','Illegal drugs','['. rspd($data[1],'Female', $gender) .']','['. 
            rspd($data[1],'Male', $gender) .']', false],
        ['','Prescription drugs','['. rspd($data[2],'Female', $gender) .']','['. 
            rspd($data[2],'Male', $gender) .']', false],

        ['2) Personal history of substance abuse','Alcohol','['. 
            rspd($data[3],'Female', $gender) .']',
                '['. rspd($data[3],'Male', $gender) .']', true],
        ['','Illegal drugs','['. rspd($data[4],'Female', $gender) .']','['. 
            rspd($data[4],'Male', $gender) .']', false],
        ['','Prescription drugs','['. rspd($data[5],'Female', $gender) .']','['. 
            rspd($data[5],'Male', $gender) .']', FALSE],

        ['3) Age (mark box if 16-45 year','','['. 
            rspd($data[6],'Female', $gender) .']','['. rspd($data[6],'Male', $gender) .']', true],
        ['4) History of preadolescent sexual abuse','','['. 
            rspd($data[7],'Female', $gender) .']','['. rspd($data[7],'Male', $gender) .']', true],

        ['5) Psychologic Desease',"Attention deficit/hyperactivity disorder",'['. 
            rspd($data[8],'Female', $gender) .']','['. rspd($data[8],'Male', $gender) .']', true],
        ['',"Obsessive-compulsive disorder",'['. 
            rspd($data[8],'Female', $gender) .']','['. rspd($data[8],'Male', $gender) .']', false],
        ['',"Bipolar disorder",'['. 
            rspd($data[8],'Female', $gender) .']','['. rspd($data[8],'Male', $gender) .']', false],
        ['',"Schizophrenia",'['. 
            rspd($data[8],'Female', $gender) .']','['. rspd($data[8],'Male', $gender) .']', false],
        ['',"Depression",'['. 
            rspd($data[9],'Female', $gender) .']','['. rspd($data[9],'Male', $gender) .']', FALSE],
        ['_last','','','', TRUE]
    ];
        
    DrawTable($h, $d, $pdf);
    
    $s = $pdf->Output('S', $filename . '', true);
    file_put_contents($filename . '_ort.pdf', $s);
 */   
    return $filename;
    
}


gwdata($_REQUEST);

