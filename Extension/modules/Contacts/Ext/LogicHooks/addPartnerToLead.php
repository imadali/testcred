<?php
$hook_array['after_relationship_add'][] = Array(
        100,
        'When a partner is linked to the contact it should also be linked to the related lead(not closed)',
    
        'custom/modules/Contacts/addPartnerToLead.php',
    
        'addPartnerToLead',
    
        'add'
    
    );


?>