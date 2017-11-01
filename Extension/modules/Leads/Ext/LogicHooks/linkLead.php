<?php
$hook_array['before_save'][] = Array(
        1,
        'check Is Lead New',
    
        'custom/modules/Leads/linkLead.php',
    
        'linkLead',
    
        'checkIsLeadNew'
    
    );
$hook_array['after_save'][] = Array(
        1,
        'When a lead is added in the system then this lead must be linked to all the leads related to its contact',
    
        'custom/modules/Leads/linkLead.php',
    
        'linkLead',
    
        'linkLeadToContactLeads'
    
    );

$hook_array['after_relationship_delete'][] = Array(
        1,
        'When a lead is unlinked from Contact the it should be unlinked from all contact related leads',
    
        'custom/modules/Leads/linkLead.php',
    
        'linkLead',
    
        'unLinkLeadFromContact'
    
    );
?>