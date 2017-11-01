<?php
$hook_array['before_save'][] = Array(
        5,
        'Create a task on creation of app and related to opp',
    
        'custom/modules/Opportunities/addTask.php',
    
        'addTask',
    
        'add'
    
    );


    $hook_array['before_save'][] = Array(
        1,
        'Change Team before Saving Application',
    
        'custom/modules/Opportunities/assignedTeam.php',
    
        'assignedTeam',
    
        'changeAssignedTeam'
    
    );

?>