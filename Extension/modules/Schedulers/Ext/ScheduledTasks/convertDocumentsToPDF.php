<?php

array_push($job_strings, 'convertDocumentsToPDF');

require_once 'custom/include/PDFConverter/PDFHelper.php';

function convertDocumentsToPDF() {
    $helper = new PDFHelper();
    return $helper->convertDocumentsToPDF();
}

?>