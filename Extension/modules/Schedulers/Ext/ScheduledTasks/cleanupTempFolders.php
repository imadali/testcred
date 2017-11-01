<?php

require_once 'custom/include/PDFConverter/PDFHelper.php';
array_push($job_strings, 'cleanupTempFolders');

function cleanupTempFolders(){
    $open_pdf_in_new_tab = rtrim($GLOBALS['sugar_config']['new_tab'],'/').'/';
    
    if(!empty($open_pdf_in_new_tab)){
        PDFHelper::recursiveRemoveDirectory($open_pdf_in_new_tab);
    }

    return true;
}
