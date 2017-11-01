<?php
/**
 * This demo append 2 documents page by page.
 * For each page a named destination will be generated.
 */
date_default_timezone_set('Europe/Paris');
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

$files = array(
    '../_files/pdfs/tektown/products/Boombastic-Box.pdf',
    '../_files/pdfs/tektown/products/Fantastic-Speaker.pdf',
    '../_files/pdfs/tektown/products/Noisy-Tube.pdf',
);

// load and register the autoload function
require_once('../../../library/SetaPDF/Autoload.php');

// initiate a merger instance
$merger = new SetaPDF_Merger();

// let's add the documents and prepare named destinations
$merger->addFile($files[0], SetaPDF_Merger::PAGES_ALL, 'Level 1');
$merger->addFile($files[1], SetaPDF_Merger::PAGES_ALL, 'Level 1.1');
$merger->addFile($files[2], SetaPDF_Merger::PAGES_ALL, 'Level 1.2');
$merger->merge();

$document = $merger->getDocument();

// Create the outline
$outlines = $document->getCatalog()->getOutlines();
// create a root node
$level_1 = SetaPDF_Core_Document_OutlinesItem::create($document, 'Level 1',
    array(SetaPDF_Core_Document_OutlinesItem::DEST => 'Level 1')
);
// Attach it to the outlines
$outlines[] = $level_1;

// attach an subnode below "Level 1"
$level_1[] = SetaPDF_Core_Document_OutlinesItem::create($document, 'Level 1.1',
    array(SetaPDF_Core_Document_OutlinesItem::DEST => 'Level 1.1')
);

// attach an additional subnode below "Level 1"
$level_1[] = SetaPDF_Core_Document_OutlinesItem::create($document, 'Level 1.2',
    array(SetaPDF_Core_Document_OutlinesItem::DEST => 'Level 1.2')
);

// Create a http writer object
$writer = new SetaPDF_Core_Writer_Http('MergeWithIndividualOutline.pdf', true);

// Pass the writer to the document object
$document->setWriter($writer);

// Save and finish the resulting document
$document->save()->finish();