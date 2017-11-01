<?php
$hook_array['after_relationship_add'][] = Array(
        1,
        'when a note is cretaed related to contracts duplicate it in related lead',
    
        'custom/modules/Contracts/addNotesInLead.php',
    
        'addNotesInLead',
    
        'addNotesInLeadActivities'
    
    );

?>