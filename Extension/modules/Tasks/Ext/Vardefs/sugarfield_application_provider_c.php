<?php

// created: 2017-04-11 14:02:52
$dictionary['Task']['fields']['application_provider_c'] = array(
    'labelValue' => 'Provider',
    'full_text_search' =>
    array(
        'boost' => '1',
    ),
    'enforced' => '',
    'dependency' => '',
    'required' => false,
    'source' => 'custom_fields',
    'name' => 'application_provider_c',
    'vname' => 'LBL_APPLICATION_PROVIDER',
    'type' => 'enum',
    'massupdate' => false,
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
    'len' => 100,
    'size' => '20',
    'options' => 'dotb_credit_provider_list',
);
?>