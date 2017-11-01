<?php 
/**
 * Merge 2 encrypted documents
 */
date_default_timezone_set('Europe/Berlin');
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

// load and register the autoload function
require_once('../../../library/SetaPDF/Autoload.php');

// initiate a merger instance
$merger = new SetaPDF_Merger();

/* Solution A */

    /* Get the document instance through the Merger. 
     * The document will already be cached in the merger instance
     */
    $filename = '../Core/_files/Example-PDF-1-encrypted.pdf';
    $encryptedDoc = $merger->getDocumentByFilename($filename);
    if ($encryptedDoc->hasSecHandler()) {
        $secHandler = $encryptedDoc->getSecHandler();
        $secHandler->authByOwnerPassword('setapdf'); // authenticate with a password (in that case the owner password)
    }
    
    // Add the filename
    $merger->addFile($filename);
    

/* Solution B */
    
    /* Create a separate document instance 
     */
    $filename = '../Core/_files/Example-PDF-2-encrypted.pdf';
    $encryptedDoc = SetaPDF_Core_Document::loadByFilename($filename);
    if ($encryptedDoc->hasSecHandler()) {
    	$secHandler = $encryptedDoc->getSecHandler();
    	$secHandler->authByOwnerPassword('setasign'); // authenticate with a password (in that case the owner password)
    }
    
    // Add the document instance
    $merger->addDocument($encryptedDoc);

/* if the document is encrypted but the rights are granted for a user it is not 
 * needed to authenitcate against the security handler.
 */ 
$merger->addFile('../Core/_files/Example-PDF-1-encrypted2.pdf');
    
// now merge the documents
$merger->merge();

// Get the resulting document instance
$document = $merger->getDocument();

// Create a http writer object
$writer = new SetaPDF_Core_Writer_Http('MergeEncryptedDocuments.pdf', true);

// Pass the writer to the document object
$document->setWriter($writer);

// Save and finish the resulting document
$document->save()->finish();