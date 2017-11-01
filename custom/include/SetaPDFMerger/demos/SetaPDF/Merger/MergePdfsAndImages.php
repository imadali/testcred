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

// Add the first page of a PDF file
$merger->addFile('../_files/pdfs/Brand-Guide.pdf', 1);

// Add images through a temporary document instance
$tmpDocument = new SetaPDF_Core_Document();
// Get the pages object
$pages = $tmpDocument->getCatalog()->getPages();

foreach ($files = glob('../_files/images/*/*.{png,jpg,jpeg}', GLOB_BRACE) AS $imagePath) {
    $img = SetaPDF_Core_Image::getByPath($imagePath);
    $xObject = $img->toXObject($tmpDocument);

    $page = $pages->create(
        array($xObject->getWidth(), $xObject->getHeight()),
        SetaPDF_Core_PageFormats::ORIENTATION_AUTO
    );

    $canvas = $page->getCanvas();
    $xObject->draw($canvas);
}

// Add the first image
$merger->addDocument($tmpDocument, 1);

// Add another page from the first document
$merger->addFile('../_files/pdfs/Brand-Guide.pdf', SetaPDF_Merger::PAGES_LAST);

// Add all pages starting at page number 2
$merger->addDocument($tmpDocument, '2-');

// now merge the documents
$merger->merge();

// Get the resulting document instance
$document = $merger->getDocument();

// Create a http writer object
$writer = new SetaPDF_Core_Writer_Http('MergePdfsAndImages.pdf', true);

// Pass the writer to the document object
$document->setWriter($writer);

// Save and finish the resulting document
$document->save()->finish();