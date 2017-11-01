<?php 
/**
 * This demo will simply append 1 document to an exiting document instance.
 * 
 * The outline of the appended document will be imported to the documents
 * outline root.
 */
date_default_timezone_set('Europe/Berlin');
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

// load and register the autoload function
require_once('../../../library/SetaPDF/Autoload.php');

// create a reader
$reader = new SetaPDF_Core_Reader_File('../Core/_files/Example-PDF-1.pdf');
// Create a http writer object
$writer = new SetaPDF_Core_Writer_Http('MergeWithOutlines.pdf', true);

// Load the inital document
$document = SetaPDF_Core_Document::load($reader, $writer);

// initiate a merger instance with an initial document
$merger = new SetaPDF_Merger($document);

// Append another complete document
$merger->addFile(
    '../Core/_files/Example-PDF-2.pdf',
    SetaPDF_Merger::PAGES_ALL,
    null,
    array(
        SetaPDF_Merger::OUTLINES_COPY => SetaPDF_Merger::COPY_OUTLINES_TO_ROOT
    )
);

// now merge the documents
$merger->merge();

// show outlines when document opens
$document->getCatalog()->setPageMode(SetaPDF_Core_Document_PageMode::USE_OUTLINES);

// Add an additional outline item
$outlines = $document->getCatalog()->getOutlines();

// create a simple URI action and append it to the end of the outline
$action = new SetaPDF_Core_Document_Action_Uri('http://www.setasign.com');
$outlines->appendChild(
    SetaPDF_Core_Document_OutlinesItem::create($document, array(
        SetaPDF_Core_Document_OutlinesItem::TITLE => '© Setasign',
        SetaPDF_Core_Document_OutlinesItem::ACTION => $action
    ))
);

// close all open root items
$iterator = $outlines->getIterator();
$iterator->setMaxDepth(0); // we only need to iterate over the root items
foreach ($iterator AS $outlineItem) {
    $outlineItem->close();
}

// Save and finish the initial document
$document->save()->finish();