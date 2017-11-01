<?php 
/**
 * This demo will simply append 2 document.
 * 
 * The outlines of both documents will be imported under individual
 * outline items.
 */
date_default_timezone_set('Europe/Berlin');
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

$files = array(
    '../Core/_files/Example-PDF-1.pdf',
    '../Core/_files/Example-PDF-2.pdf',
);

// load and register the autoload function
require_once('../../../library/SetaPDF/Autoload.php');

// initiate a merger instance
$merger = new SetaPDF_Merger();

// merge the doucments page by page
foreach ($files AS $filename) {
    $outlinesConfig = array(
        SetaPDF_Merger::OUTLINES_TITLE => basename($filename),
        SetaPDF_Merger::OUTLINES_COPY => SetaPDF_Merger::COPY_OUTLINES_AS_CHILDS,
    );
    
    $merger->addFile($filename, null, null, $outlinesConfig);
}

// now merge the documents
$merger->merge();

// Get the resulting document instance
$document = $merger->getDocument();

// Create a http writer object
$writer = new SetaPDF_Core_Writer_Http('MergeWithOutlines2.pdf', true);

// Pass the writer to the document object
$document->setWriter($writer);

// Save and finish the resulting document
$document->save()->finish();