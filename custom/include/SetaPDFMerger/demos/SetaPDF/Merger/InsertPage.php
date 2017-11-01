<?php
/**
 * This demo will import the first page of one document, append another page
 * from another document and import the rest of the first document.
 *
 * The outline of the first document will be imported.
 */
date_default_timezone_set('Europe/Berlin');
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

// load and register the autoload function
require_once('../../../library/SetaPDF/Autoload.php');

$fileA = '../Core/_files/Example-PDF-1.pdf';
$fileB = '../_files/pdfs/tektown/Logo.pdf';

// create a merger instance
$merger = new SetaPDF_Merger();

// Add the first page and define to copy the bookmark outline
$merger->addFile(
    $fileA,
    SetaPDF_Merger::PAGES_FIRST,
    null,
    array(
        SetaPDF_Merger::OUTLINES_COPY => SetaPDF_Merger::COPY_OUTLINES_TO_ROOT
    )
);

// Add snd document
$merger->addFile($fileB);
// Add all pages after the first page from the first document
$merger->addFile($fileA, '2-');

// merger
$merger->merge();

// get the resulting document instance
$document = $merger->getDocument();

// copy the metadata from the first document to the new one
$documentA = $merger->getDocumentByFileName($fileA);
$document->getInfo()->setAll($documentA->getInfo()->getAll());

$document->setWriter(new SetaPDF_Core_Writer_Http('InsertPage.pdf', true));
$document->save()->finish();
