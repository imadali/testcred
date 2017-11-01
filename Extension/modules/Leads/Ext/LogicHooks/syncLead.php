<?php

$hook_array['after_save'][] = Array(
       
        120,
        'Sync All Lead field to Related Contact',
    
        'custom/modules/Leads/syncLead.php',
    
        'syncLead',
    
        'syncLeadToContact'
    
    );
?>