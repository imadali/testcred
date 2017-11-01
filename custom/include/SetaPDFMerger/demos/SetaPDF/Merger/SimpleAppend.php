<?php 
/**
 * This demo will simply append 1 document to an exiting document instance.
 * 
 * The complete document structure of the initial document will be kept.
 */
date_default_timezone_set('Europe/Berlin');
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

// load and register the autoload function
require_once('../../../library/SetaPDF/Autoload.php');

// create a reader
$reader = new SetaPDF_Core_Reader_File('../_files/pdfs/camtown/Order-Form.pdf');
// Create a http writer object
$writer = new SetaPDF_Core_Writer_Http('SimpleAppend.pdf', true);

// Load the inital document
$document = SetaPDF_Core_Document::load($reader, $writer);

// initiate a merger instance with an initial document
$merger = new SetaPDF_Merger($document);

// Append another complete document
$merger->addFile('../_files/pdfs/camtown/Terms-and-Conditions.pdf');

// now merge the documents
$merger->merge();

// Save and finish the initial document
$document->save()->finish();