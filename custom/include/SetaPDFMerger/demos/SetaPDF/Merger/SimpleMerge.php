<?php 
/**
 * This demo will simply append 3 documents.
 */
date_default_timezone_set('Europe/Berlin');
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

// load and register the autoload function
require_once('../../../library/SetaPDF/Autoload.php');

// initiate a merger instance
$merger = new SetaPDF_Merger();

// Add a complete document
$merger->addFile('../_files/pdfs/camtown/products/Boombastic-Box.pdf');

// Add another complete document
$merger->addFile('../_files/pdfs/camtown/products/Fantastic-Speaker.pdf');

// Add another complete document
$merger->addFile('../_files/pdfs/camtown/products/Noisy-Tube.pdf');

// now merge the documents
$merger->merge();

// Get the resulting document instance
$document = $merger->getDocument();

// Create a http writer object
$writer = new SetaPDF_Core_Writer_Http('SimpleMerge.pdf', true);

// Pass the writer to the document object
$document->setWriter($writer);

// Save and finish the resulting document
$document->save()->finish();