<?php
$hook_array['before_save'][] = Array(
    1,
    'When the status of the Lead is changed the lead status of related task is synced with Lead' ,
    'custom/modules/Leads/SetRelatedTasks.php',
    'SetRelatedTasks',
    'setStatus'
);


?>