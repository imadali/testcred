<?php

    $hook_array['before_save'][] = Array(
        
        1,
       
        'Logic Hook for saving Record Link ',
       
        'custom/modules/Leads/recordLinkSave.php',
       
        'RecordLinkSave',
       
        'saveLink'
    );
    
    $hook_array['before_save'][] = Array(
        
        1,
       
        'Logic Hook for saving Previous Status',
       
        'custom/modules/Leads/savePreviousStatus.php',
       
        'savePreviousStatus',
       
        'saveStatus'
    );
    

?>