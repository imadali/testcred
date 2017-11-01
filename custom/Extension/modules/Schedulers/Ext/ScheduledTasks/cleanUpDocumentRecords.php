<?php

require_once('include/SugarQuery/SugarQuery.php');

array_push($job_strings, 'cleanUpDocumentRecords');

function cleanUpDocumentRecords() {
    $query = new SugarQuery();
    $query->select(array('id','document_revision_id'));
    $query->from(BeanFactory::getBean('Documents'), array('team_security' => false));
    $query->where()->equals('send_document', 1);
    $query->where()->equals('deleted', 0);
    $results = $query->execute();
    $GLOBALS['log']->debug('Sugar Query Compile SQL :: '.$query->compileSql());
    
    $document_id = array();
    $document_revision_id = array();
    $counter = 0;
    
    // Deleting the attachments in Chunks
    foreach ($results as $row1) {
        if($counter == 200){
            
            deleteDocuments($document_id);;
            unlinkDocumentAttachment($document_revision_id);
            
            $document_id = array();
            $document_revision_id = array();
            
            $counter = 0;
        }
        
        array_push($document_id,$row1['id']);
        array_push($document_revision_id,$row1['document_revision_id']);

        $counter++;
    }

    /*Check if anything is there in
      array to be deleted     
     */
    if(!empty($document_id)){
        deleteDocuments($document_id);
    }
    
    if(!empty($document_revision_id)){
        unlinkDocumentAttachment($document_revision_id);
    }
    
    return true;
}

function deleteDocuments($docId){
    $GLOBALS['log']->debug('=== Document IDs to be deleted ===='.print_r($docId,1));
    $doc_ids = implode(',', array_map('add_quotes', $docId));
    $sql_doc = 'UPDATE documents SET deleted = 1 WHERE id IN ('.$doc_ids.')';
    $GLOBALS['db']->query($sql_doc);
}

function unlinkDocumentAttachment($id){
    $GLOBALS['log']->debug('======= Document Revision ID to be removed ======='.print_r($id,1));
    $filePath = rtrim($GLOBALS['sugar_config']['upload_dir'],'/').'/';
    foreach($id as $doc_id){
        if(file_exists($filePath.$doc_id)){
            unlink($filePath.$doc_id);
        }
    }
}