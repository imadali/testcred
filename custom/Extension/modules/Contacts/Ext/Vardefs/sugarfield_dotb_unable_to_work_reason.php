<?php
$module = 'Contact';
$fieldPrefix = 'dotb_';
$fieldName = 'unable_to_work_reason';
$length = 255;

$fieldName = $fieldPrefix.$fieldName;

$dictionary[$module]['fields'][$fieldName]['type']                        = 'varchar';
$dictionary[$module]['fields'][$fieldName]['name']                        = strtolower($fieldName);
$dictionary[$module]['fields'][$fieldName]['vname']                       = 'LBL_'.strtoupper($fieldName);
$dictionary[$module]['fields'][$fieldName]['len']                         = $length;
$dictionary[$module]['fields'][$fieldName]['duplicate_merge']                = 'enabled';
$dictionary[$module]['fields'][$fieldName]['merge_filter']                = 'enabled';
$dictionary[$module]['fields'][$fieldName]['importable']                  = '';
$dictionary[$module]['fields'][$fieldName]['studio']                      = 'visible';
