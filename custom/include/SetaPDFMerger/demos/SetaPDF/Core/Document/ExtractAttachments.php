<?php
/**
 * This demo lists and extracts attachments in a PDF document
 */
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
date_default_timezone_set('Europe/Berlin');

// load and register the autoload function
require_once('../../../../library/SetaPDF/Autoload.php');

// create a document
$document = SetaPDF_Core_Document::loadByFilename('../../_files/pdfs/tektown/products/All-Portfolio.pdf');

// get names
$names = $document->getCatalog()->getNames();
// get the "embedded files" name tree
$embeddedFilesTree = $names->getTree(SetaPDF_Core_Document_Catalog_Names::EMBEDDED_FILES);
$files = $embeddedFilesTree->getAll(true);

// extract the file
if (isset($_GET['f']) && isset($files[$_GET['f']])) {
    // get the file specification entry
    $file = $embeddedFilesTree->get($files[$_GET['f']])->ensure();
    // resolve the filename
    $filename = SetaPDF_Core_Encoding::convertPdfString($file->offsetGet('F')->ensure()->getValue());
    // resolve the file stream
    $fileContentStream = $file->offsetGet('EF')->ensure()->offsetGet('F')->ensure();
    $fileContentDict = $fileContentStream->getValue();
    // define a default content type
    $contentType = 'application/force-download';
    // if available use the embedded content type
    if ($fileContentDict->offsetExists('Subtype')) {
        $contentType = $fileContentDict['Subtype']->ensure()->getValue();
    }

    // pass the file to the client
    $stream = $fileContentStream->getStream();
    header('Content-Type: ' . $contentType);
    header('Content-Disposition: attachment; filename="' . $filename . '";');
    header('Content-Transfer-Encoding: binary');
    header('Content-Length: ' . strlen($stream));
    echo $stream;
    die();
}

foreach ($files AS $id => $name) {
    $file = $embeddedFilesTree->get($name)->ensure();
    $filename = SetaPDF_Core_Encoding::convertPdfString($file->offsetGet('F')->ensure()->getValue());
    $name = SetaPDF_Core_Encoding::convertPdfString($name);
    echo '<a href="?f=' . $id . '">' . htmlspecialchars($name) . ': ' . htmlspecialchars($filename) . '</a><br />';
}


