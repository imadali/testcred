<?php

    $hook_array['before_save'][] = Array(
        1,
        'Create Contact when lead is closed',
        'custom/modules/Leads/CreateContact.php',
        'CreateContact',
        'create'
    );

?>