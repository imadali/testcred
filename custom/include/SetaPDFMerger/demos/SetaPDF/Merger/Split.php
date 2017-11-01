<?php
/**
 * This demo splits a document into single pages and add it to a zip file
 */
date_default_timezone_set('Europe/Berlin');
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

if (!class_exists('ZipArchive')) {
    echo 'Sorry, this demo requires the Zip extension -> http://www.php.net/zip';
    die();
}

// load and register the autoload function
require_once('../../../library/SetaPDF/Autoload.php');

// The main PDF document
$pdfPath = '../_files/pdfs/Brand-Guide.pdf';

// Let's prepare a ZipArchive instance
$zip = new ZipArchive();
$zipName = tempnam(sys_get_temp_dir(), 'zip');
$zip->open($zipName, ZIPARCHIVE::CREATE);

// Load the "in"-document
$inDocument = SetaPDF_Core_Document::loadByFilename($pdfPath);
// To prevent multiple object resolving set this to true
$inDocument->setCacheReferencedObjects(true);
// keep read objects for reusage for other pages
$inDocument->setCleanUpObjects(false);
// We want to work with the pages
$pages = $inDocument->getCatalog()->getPages();
// We will touch them all, so pre-read them all (will speed things up)
$pages->ensureAllPageObjects();

// Now extract page by page
for ($pageNumber = 1, $pageCount = $pages->count(); $pageNumber <= $pageCount; $pageNumber++) {

    // We create a new merger instance
    $merger = new SetaPDF_Merger();
    // Add the individual page of the "in"-document to the merger
    $merger->addDocument($inDocument, $pageNumber);
    // ...and merge
    $merger->merge();

    // Create a writer which we can pass to the ZipArchive instance
    $writer = new SetaPDF_Core_Writer_String();

    // Get the resulting document instance
    $resDocument = $merger->getDocument();
    /* Define that written objects should not be cleaned-up (we need this,
     * because we are going to re-use them for coming pages of the "in"-document
     */
    $resDocument->setCleanUpObjects(false);
    // Set the writer
    $resDocument->setWriter($writer);
    // Save and finish the extracted page/document
    $resDocument->save()->finish();
    // Free some memory
    $resDocument->cleanUp();

    // let's create a sortable filename
    $pdfName = sprintf('%0' . strlen($pageCount). 's', $pageNumber) . '.pdf';
    // Add the file to the zip archive
    $zip->addFromString($pdfName, $writer);
}

// Close the zip file and send the zip-file to the client
$zip->close();

header('Content-Type: application/zip');
header('Content-Length: ' . filesize($zipName));
header('Content-Disposition: attachment; filename="' . basename($pdfPath, '.pdf') . '.zip"');
readfile($zipName);

unlink($zipName);