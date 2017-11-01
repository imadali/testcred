<?php 
/**
 * Concatenate 2 documents and append the bookmark outline of the 2nd document
 * below a previously created outline item.
 */
date_default_timezone_set('Europe/Berlin');
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

// load and register the autoload function
require_once('../../../library/SetaPDF/Autoload.php');

// initiate a merger instance
$merger = new SetaPDF_Merger();

// Add the first document to an entry with the label "Main Entry"
$id = $merger->addFile('../Core/_files/Example-PDF-1.pdf', null, null, array(
    SetaPDF_Merger::OUTLINES_TITLE => basename('Main Entry'),
    SetaPDF_Merger::OUTLINES_COPY => SetaPDF_Merger::COPY_OUTLINES_AS_CHILDS,
));

// Append the outlines of the snd document also below "Main Entry"
$a = $merger->addFile('../Core/_files/Example-PDF-2.pdf', null, null, array(
    SetaPDF_Merger::OUTLINES_PARENT => $id,
    SetaPDF_Merger::OUTLINES_COPY => SetaPDF_Merger::COPY_OUTLINES_AS_CHILDS,
));

// now merge the documents
$merger->merge();

// Get the resulting document instance
$document = $merger->getDocument();

// show outlines when document opens
$document->getCatalog()->setPageMode(SetaPDF_Core_Document_PageMode::USE_OUTLINES);

// Create a http writer object
$writer = new SetaPDF_Core_Writer_Http('MergeWithOutlines3.pdf', false);

// Pass the writer to the document object
$document->setWriter($writer);

// Save and finish the resulting document
$document->save()->finish();