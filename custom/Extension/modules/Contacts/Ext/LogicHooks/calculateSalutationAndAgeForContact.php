<?php

/**
**
**/
$hook_array['before_save'][] = Array(
    1,
    'Calculate salutation and age for Contact coming from API',
    'custom/modules/Contacts/calculateSalutationAndAgeForContact.php',
    'calculateSalutationAndAgeForContact',
    'updateSalutationAndAgeForContact'
);

?>