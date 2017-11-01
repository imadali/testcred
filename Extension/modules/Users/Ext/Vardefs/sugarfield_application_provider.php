<?php

$dictionary['User']['fields']['application_provider'] = array (
    'dependency' => '',
    'visibility_grid' => '',
    'full_text_search' => 
    array (
        'boost' => 1,
    ),
    'required' => false,
    'name' => 'application_provider',
    'vname' => 'LBL_APPLICATION_PROVIDER',
    'type' => 'multienum',
    'dbType' => 'varchar',
    'isMultiSelect' => true,
    'massupdate' => true,
    'default' => NULL,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => '1',
    'audited' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'calculated' => false,
    'options' => 'dotb_credit_provider_list',
);

 ?>