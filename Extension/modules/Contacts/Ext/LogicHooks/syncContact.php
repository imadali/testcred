<?php

$hook_array['before_save'][] = Array(
        1,
        'Check if assigned user is changed',
    
        'custom/modules/Contacts/syncContact.php',
    
        'syncContact',
    
        'checkAssignedUserChanged'
    
    );
$hook_array['after_save'][] = Array(
        1,
        'Sync All Contact field to Related Leads',
    
        'custom/modules/Contacts/syncContact.php',
    
        'syncContact',
    
        'syncContactToLeads'
    
    );
?>