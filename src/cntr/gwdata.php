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
    
    $filename = '../saved/'.$fname."_" .$fdate;
    file_put_contents($filename, print_r($data,  true));
}

gwdata($_REQUEST);

