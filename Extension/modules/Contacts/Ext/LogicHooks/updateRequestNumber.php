<?php

$hook_array['before_save'][] = Array(
    1,
    'Updating Credit Request Number in Leads',
    'custom/modules/Contacts/UpdateRequestNumberContact.php',
    'UpdateRequestNumberContact',
    'autoIncrement'
);
?>