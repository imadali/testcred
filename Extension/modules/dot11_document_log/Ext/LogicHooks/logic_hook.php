<?php

$hook_version = 1;

$hook_array = array();
$hook_array['before_save'] = array();
$hook_array['before_save'][] = array(
    1,
    'If Convert Document is Checked then Document is avaliable for Conversion again using ConvertApi',
    'custom/modules/dot11_document_log/ConvertAgain.php',
    'ConvertAgain',
    'saveConvertAgain'
);

?>