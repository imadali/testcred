<?php
$hook_array['before_save'][] = Array(
    10,
    
    'Checking if assigned Used is changed',
    
    'custom/modules/Calls/WorkflowViaCall.php',
    
    'WorkflowViaCall',
    
    'executeWorkflow'
);