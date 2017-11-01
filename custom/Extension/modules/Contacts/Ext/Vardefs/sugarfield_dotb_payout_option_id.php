<?php
$module = 'Contact';
$fieldPrefix = 'dotb_';
$fieldName = 'payout_option_id';
$length = 100;

$fieldName = $fieldPrefix.$fieldName;

$dictionary[$module]['fields'][$fieldName]['type']                        = 'enum';
$dictionary[$module]['fields'][$fieldName]['name']                        = strtolower($fieldName);
$dictionary[$module]['fields'][$fieldName]['vname']                       = 'LBL_'.strtoupper($fieldName);
$dictionary[$module]['fields'][$fieldName]['len']                         = $length;
$dictionary[$module]['fields'][$fieldName]['size']                         = 255;
$dictionary[$module]['fields'][$fieldName]['merge_filter']                = 'enabled';
$dictionary[$module]['fields'][$fieldName]['duplicate_merge']                = 'enabled';
$dictionary[$module]['fields'][$fieldName]['importable']                  = '';
$dictionary[$module]['fields'][$fieldName]['studio']                      = 'visible';
$dictionary[$module]['fields'][$fieldName]['massupdate']                  = 1;
$dictionary[$module]['fields'][$fieldName]['options']                     = 'dotb_payout_option_type_list';
