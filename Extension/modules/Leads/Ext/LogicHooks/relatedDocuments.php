<?php

$hook_array['after_save'][] = Array(
       
        1,
        'Logic Hook for saving getting document status',
    
        'custom/modules/Leads/getRelatedDocument.php',
    
        'getRelatedDocument',
    
        'getRelatedDocumentStatus'
    
    );

$hook_array['before_save'][] = Array(
       
        1,
        'Logic Hook for saving getting document status',
    
        'custom/modules/Leads/getRelatedDocument.php',
    
        'getRelatedDocument',
    
        'saveFetchedRow'
    
    );

?>