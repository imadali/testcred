<?php

$hook_array['after_save'][] = Array(
       
        1,
        'Logic Hook link bank  with opp according to Provider',
    
        'custom/modules/Opportunities/linkBank.php',
    
        'linkBank',
    
        'setProvierAsBank'
    
    );


?>