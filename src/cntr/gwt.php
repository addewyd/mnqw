<?php

require('../../dist/cntr/fpdf/fpdf.php');
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
    $fdate = $r['fdate'];
    $data = $r['data'];
    $gender = $r['gender'];
    $dd = date_create();
    $ddate = date_format($dd, "Ymd-His");
    $filename = 'tst'.$fname."_" .$ddate;
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


    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);
    $pdf -> Cell(0, 10, 'Opioid Risk Tool (' . $gender . ')', 0, 1, 'C');
    $pdf -> Cell(0, 10, '[ORT'.$g.']', 0, 1, 'C');

    $pdf->SetFont('Arial','',12);
    $pdf -> Cell(64, 8, 'Name: ' . $fname , 0, 0, 'L');
    $pdf -> Cell(40, 8, 'Date: '. $fdate , 0, 1, 'L');


    $pdf->SetFont('Arial','', 9);
    // table


    // Family history of substance abuse (3)
    //      Alcohol
    //      Illegal drugs
    //      Prescription drugs
    // Personal history of substance abuse (3)
    //      Alcohol
    //      Illegal drugs
    //      Prescription drugs
    // Age (mark box if 16-45 year
    // History of preadolescent sexual abuse
    // Psychologic Desease 1/5
    //      Attention deficit/hyperactivity disorder
    //      Obsessive-compulsive disorder
    //      Bipolar disorder
    //      Schizophrenia
    //      Depression

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

    return $filename;
}

$r = json_decode('{"data":[{"text":"Family history of substance abuse? Alcohol","answer":"yes"},{"text":"Family history of substance abuse? Illegal drugs","answer":"no"},{"text":"Family history of substance abuse? Prescription drugs","answer":"yes"},{"text":"Personal history of substance abuse? Alcohol","answer":"no"},{"text":"Personal history of substance abuse? Illegal drugs","answer":"yes"},{"text":"Personal history of substance abuse? Prescription drugs","answer":"no"},{"text":"Family history of substance abuse between 16 years old and 45 years old?","answer":"no"},{"text":"History of preadolescent sexual abuse?","answer":"yes"},{"text":"Psychologic Desease: ADD, OCD, bipolar, schizophrenia","answer":"yes"},{"text":"Psychologic Desease: Depression","answer":"no"}],"fname":"fname_lname","fdate":"2019-04-01","gender":"Female"}', true);

save_pdf($r);

