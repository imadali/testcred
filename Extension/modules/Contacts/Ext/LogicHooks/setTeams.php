<?php
    $hook_array['before_save'][] = Array(
        120,
       
        'Checking if assigned Used is changed',
       
        'custom/modules/Leads/setTeams.php',
       
        'setTeams',
       
        'checkContactIsNew'
    );
    
        $hook_array['after_save'][] = Array(
        120,
       
        'Remove global team if exist ',
       
        'custom/modules/Leads/setTeams.php',
       
        'setTeams',
       
        'setTeamsInContact'
    );
        
        
    $hook_array['after_relationship_add'][] = Array(
        1,
       
        'If a pertner record is linked to Lead/Contact',
       
        'custom/modules/Leads/setTeams.php',
       
        'setTeams',
       
        'setTeamsInPartner'
    );
    
    
?>