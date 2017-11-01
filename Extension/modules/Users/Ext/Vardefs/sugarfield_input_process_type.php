<?php
/**
 * CRED-991 : Lead-Assignment-Rule
 */
$dictionary['User']['fields']['input_process_type'] = array (
    'dependency' => '',
    'visibility_grid' => '',
    'required' => false,
    'duplicate_merge' => 'enabled',
    'merge_filter' => 'enabled',
    'name' => 'input_process_type',
    'vname' => 'LBL_INPUT_PROCESS_TYPE',
    'type' => 'multienum',
    'dbType' => 'varchar',
    'isMultiSelect' => true,
    'massupdate' => false,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge_dom_value' => 1,
    'audited' => false,
    'reportable' => true,
    'unified_search' => false,
    'default' => '',
    'calculated' => false,
    'options' => 'dotb_input_type_list',
);

 ?>