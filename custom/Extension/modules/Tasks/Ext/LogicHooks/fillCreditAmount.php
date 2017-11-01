<?php


    $hook_array['before_save'][] = Array(
        3,
        'Fill credit amount field',
    
        'custom/modules/Tasks/fillCreditAmount.php',
    
        'fillCreditAmount',
    
        'creditAmount'
    
    );

?>