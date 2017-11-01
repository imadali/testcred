<?php
    $hook_array['before_save'][] = Array(
        20,
        'Tasks set thru Workflows need to exclude Weekends (SAT and SUN). If a task was set to either a SAT or a SUN, it would get the Date of the following MON as Due Date.',
    
        'custom/modules/Tasks/skipWeekend.php',
    
        'skipWeekend',
    
        'skipSatSun'
    
    );

?>