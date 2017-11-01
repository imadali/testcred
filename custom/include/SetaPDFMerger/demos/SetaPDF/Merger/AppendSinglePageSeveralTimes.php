<?php 
/**
 * This demo will append a page of the initial document several times
 * to the end of itself.
 */
date_default_timezone_set('Europe/Berlin');
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

// load and register the autoload function
require_once('../../../library/SetaPDF/Autoload.php');

// create a reader
$reader = new SetaPDF_Core_Reader_File('../_files/pdfs/Brand-Guide.pdf');

// Create a http writer object
$writer = new SetaPDF_Core_Writer_Http('AppendSinglePageSeveralTimes.pdf', true);

// Load the inital document
$document = SetaPDF_Core_Document::load($reader, $writer);

// initiate a merger instance with an initial document
$merger = new SetaPDF_Merger($document);

// Append a page several times
for ($i = 0; $i < 100; $i++)
    $merger->addDocument($document, 5);

// now merge the documents
$merger->merge();

// Save and finish the initial document
$document->save()->finish();