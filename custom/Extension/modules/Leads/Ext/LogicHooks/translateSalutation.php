<?php
$hook_array['before_save'][] = Array(
        10,
        'Save translated salutation base on correspondence language',
        'custom/modules/Leads/TransSalutation.php',    
        'TransSalutation',    
        'translate'    
    );

?>