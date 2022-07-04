<?php
#header('Content-Type: application/octet-stream');
session_start();
#echo var_dump($_SESSION);
$file_name = $_SESSION['filetodownload'];
#$output_result = $_SESSION['csvline'];
#$output_result = iconv('UTF8','Windows-1251',$output_result);
#header('Content-Type: application/csv; charset=windows-1251'); 
#header('Content-Disposition: attachment; filename="sample.csv"');
#header('Content-Length: ' . strlen($output_result));
#header('Connection: close');

$url = '/home/econsk/web/hinfo/utils/bx_to_rk/' . $file_name;
#$file_name = 'res_ikp.csv';

header("Content-Description: File Transfer"); 
    header("Content-Type: application/octet-stream"); 
    header(
    "Content-Disposition: attachment; filename=\""
    . $file_name . "\""); 
    #echo "File downloaded successfully";
    readfile ($url);

    session_unset();

#readfile($url)

//$output_result = iconv('UTF8','Windows-1251',$output_result);
#echo $output_result;
# Don't use application/force-download - it's not a real MIME type, and the Content-Disposition header is sufficient
?>