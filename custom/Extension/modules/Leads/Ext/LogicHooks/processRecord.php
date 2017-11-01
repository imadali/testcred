<?php
    $hook_array['process_record'][] = Array(
        //Processing index. For sorting the array.
        1,
       
        //Label. A string value to identify the hook.
        'process_record example',
       
        //The PHP file where your class is located.
        'custom/modules/Leads/processRecord.php',
       
        //The class the method is in.
        'processRecord',
       
        //The method to call.
        'processContract'
    );

    
    
?>