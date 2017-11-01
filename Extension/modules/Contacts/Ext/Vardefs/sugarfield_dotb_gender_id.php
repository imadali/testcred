<?php

$module = 'Contact';
$fieldPrefix = 'dotb_';
$fieldName = 'gender_id';
$length = 100;

$fieldName = $fieldPrefix . $fieldName;

$dictionary[$module]['fields'][$fieldName]['type'] = 'enum';
$dictionary[$module]['fields'][$fieldName]['size'] = 255;
$dictionary[$module]['fields'][$fieldName]['name'] = strtolower($fieldName);
$dictionary[$module]['fields'][$fieldName]['vname'] = 'LBL_' . strtoupper($fieldName);
$dictionary[$module]['fields'][$fieldName]['len'] = $length;
$dictionary[$module]['fields'][$fieldName]['duplicate_merge'] = 'enabled';
$dictionary[$module]['fields'][$fieldName]['merge_filter'] = 'enabled';
$dictionary[$module]['fields'][$fieldName]['importable'] = '';
$dictionary[$module]['fields'][$fieldName]['studio'] = 'visible';
$dictionary[$module]['fields'][$fieldName]['massupdate'] = 1;
$dictionary[$module]['fields'][$fieldName]['options'] = 'dotb_gender_list';
$dictionary[$module]['fields'][$fieldName]['required'] = true;
$dictionary[$module]['fields'][$fieldName]['audited'] = true;
