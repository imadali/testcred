<?php
$module = 'Contact';
$fieldPrefix = 'dotb_';
$fieldName = 'employer_npa';
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
$dictionary[$module]['fields'][$fieldName]['audited']                     = true;
