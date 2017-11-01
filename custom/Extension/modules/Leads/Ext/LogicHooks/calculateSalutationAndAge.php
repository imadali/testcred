<?php

/**
**
**/
$hook_array['before_save'][] = Array(
    1,
    'Calculate salutation and age for Leads coming from API',
    'custom/modules/Leads/calculateSalutationAndAge.php',
    'calculateSalutationAndAge',
    'updateSalutationAndAge'
);

?>