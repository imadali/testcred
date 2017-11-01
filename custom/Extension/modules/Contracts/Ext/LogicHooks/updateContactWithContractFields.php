<?php

$hook_version = 1;
$hook_array = array();
$hook_array['after_save'] = array();
$hook_array['after_save'][] = Array(
    2,
    'Update the contact Linked to Lead with contract values',
    'custom/modules/Contracts/updateContactWithContractFields.php',
    'updateContactWithContractFields',
    'updateContact'
);
?>