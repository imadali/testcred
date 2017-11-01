<?php
    $hook_array['before_save'][] = Array(
        120,
       
        'Checking if assigned Used is changed',
       
        'custom/modules/Tasks/setActivitiesTeams.php',
       
        'setActivitiesTeams',
       
        'checkParentChanged'
    );
    
    $hook_array['after_save'][] = Array(
        120,
       
        'Remove global team if exist ',
       
        'custom/modules/Tasks/setActivitiesTeams.php',
       
        'setActivitiesTeams',
       
        'setTeamsInActivities'
    );
    
    
?>