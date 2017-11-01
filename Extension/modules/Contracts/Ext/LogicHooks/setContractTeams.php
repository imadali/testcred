<?php
    $hook_array['before_save'][] = Array(
        120,
       
        'Checking if assigned Used is changed',
       
        'custom/modules/Contracts/setContractTeams.php',
       
        'setContractTeams',
       
        'checkAssignedUser'
    );
    
    $hook_array['after_save'][] = Array(
        120,
       
        'Remove global team if exist ',
       
        'custom/modules/Contracts/setContractTeams.php',
       
        'setContractTeams',
       
        'setTeamsInContract'
    );
    
    
?>