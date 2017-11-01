<?php
$hook_array['after_relationship_add'][] = Array(
        1,
        'When a partner is linked to the lead it should also be linked to th erelated contact',
    
        'custom/modules/Leads/addPartner.php',
    
        'addPartner',
    
        'addPartnerToContact'
    
    );


?>