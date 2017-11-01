<?php 
/**
 * This demo append 2 documents page by page.
 * For each page a named destination will be generated.
 */
date_default_timezone_set('Europe/Paris');
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

$files = array(
    '../_files/pdfs/Brand-Guide.pdf',
    '../_files/pdfs/tektown/products/Boombastic-Box.pdf',
    '../_files/pdfs/tektown/products/Fantastic-Speaker.pdf',
    '../_files/pdfs/tektown/products/Noisy-Tube.pdf',
);

// load and register the autoload function
require_once('../../../library/SetaPDF/Autoload.php');

// initiate a merger instance
$merger = new SetaPDF_Merger();

// merge the doucments page by page
$newPageNo = 1;
foreach ($files AS $filename) {
    
    // Get the page count
    $pageCount = $merger->getPageCount($filename);
    
    // define the outlines configuration
    $outlinesConfig = array(
        SetaPDF_Merger::OUTLINES_TITLE => basename($filename),
        SetaPDF_Merger::OUTLINES_COPY => SetaPDF_Merger::COPY_OUTLINES_AS_CHILDS,
    );
    
    // add the documents page by page
    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
        $namedDestination = 'Page.' . $newPageNo;
        $merger->addFile($filename, $pageNo, $namedDestination, $outlinesConfig);
        
        if (!isset($_GET['n']))
            echo '<a href="MergeAndAddNamedDestinations.php?n=1#nameddest=' . $namedDestination . '">' . $namedDestination . '</a><br />';
        
        $newPageNo++;
        $outlinesConfig = null; // import only the outline when the first page is added
    }
}

if (!isset($_GET['n'])) {
    die();
}

// now merge the documents
$merger->merge();

// Get the resulting document instance
$document = $merger->getDocument();

// Create a http writer object
$writer = new SetaPDF_Core_Writer_Http('MergeAndAddNamedDestinations.pdf', true);

// Pass the writer to the document object
$document->setWriter($writer);

// Save and finish the resulting document
$document->save()->finish();