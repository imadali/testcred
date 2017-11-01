<?php

$module = 'Contact';
$fieldPrefix = 'dotb_';
$fieldName = 'income_include_alimony';
$length = 255;

$fieldName = $fieldPrefix.$fieldName;

$dictionary[$module]['fields'][$fieldName]['type']                = 'bool';
$dictionary[$module]['fields'][$fieldName]['name']                = strtolower($fieldName);
$dictionary[$module]['fields'][$fieldName]['vname']               = 'LBL_'.strtoupper($fieldName);
$dictionary[$module]['fields'][$fieldName]['unified_search']      = 'false';
$dictionary[$module]['fields'][$fieldName]['studio']              = 'visible';
$dictionary[$module]['fields'][$fieldName]['massupdate']          = false;
$dictionary[$module]['fields'][$fieldName]['merge_filter']        = 'enabled';
$dictionary[$module]['fields'][$fieldName]['duplicate_merge']        = 'enabled';

$dictionary[$module]['fields'][$fieldName]['calculated']          = false;
$dictionary[$module]['fields'][$fieldName]['reportable']          = true;
$dictionary[$module]['fields'][$fieldName]['importable']          = 'true';
$dictionary[$module]['fields'][$fieldName]['audited']             = 1;
$dictionary[$module]['fields'][$fieldName]['required']            = 0;