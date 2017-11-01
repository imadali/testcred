<?php
    $hook_array['before_save'][] = Array(
        120,
       
        'Checking if assigned Used is changed',
       
        'custom/modules/Opportunities/setAppTeams.php',
       
        'setAppTeams',
       
        'checkAssignedUser'
    );
    
    $hook_array['after_save'][] = Array(
        120,
       
        'Remove global team if exist ',
       
        'custom/modules/Opportunities/setAppTeams.php',
       
        'setAppTeams',
       
        'setTeamsInApp'
    );
    
    
?>