<?php
$hook_array['after_relationship_add'][] = Array(
        1,
        'Create a copy of related note and related it to related lead',
    
        'custom/modules/Opportunities/addRelatedNote.php',
    
        'addRelatedNote',
    
        'addRelatedNoteToActivities'
    
    );

?>