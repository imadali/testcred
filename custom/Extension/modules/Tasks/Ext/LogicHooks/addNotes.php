<?php
$hook_array['after_relationship_add'][] = Array(
        1,
        'Create a task on creation of app and related to opp',
    
        'custom/modules/Tasks/addNotes.php',
    
        'addNotes',
    
        'addNotesToActivities'
    
    );

    $hook_array['before_save'][] = Array(
        1,
        'Saves current time for a recored when its assigned user is changed',
    
        'custom/modules/Tasks/saveCurrentTime.php',
    
        'saveCurrentTime',
    
        'saveTime'
    
    );
    $hook_array['before_save'][] = Array(
        2,
        'If customer contact is chnaged in task then set the same customer contact and assigned user in parent Lead/contact',
    
        'custom/modules/Tasks/setUser.php',
    
        'setUser',
    
        'setCustomerContact'
    
    );


?>