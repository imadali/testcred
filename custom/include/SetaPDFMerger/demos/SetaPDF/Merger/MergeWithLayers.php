<?php
/**
 * This demo shows you how to affect copying of layer information
 */
date_default_timezone_set('Europe/Berlin');
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

// load and register the autoload function
require_once('../../../library/SetaPDF/Autoload.php');

$merger = new SetaPDF_Merger();

// Copy layer information from this document
$merger->addFile(array(
    'filename' => '../_files/pdfs/layers/rect+circle+polygon.pdf',
    'copyLayers' => true
));

// Don't copy layer information from this document
$merger->addFile(array(
    'filename' => '../_files/pdfs/layers/rect+circle+triangle.pdf',
    'copyLayers' => false
));

// Copy the same document a 2nd time but also copy layer information (default behavior)
$merger->addDocument(
    SetaPDF_Core_Document::loadByFilename('../_files/pdfs/layers/rect+circle+triangle.pdf')
);

$merger->merge();

$document = $merger->getDocument();

$document->setWriter(new SetaPDF_Core_Writer_Http('layers.pdf'));
$document->save()->finish();