<?php

$dictionary['Contact']['fields']['provider'] = array (
    'dependency' => '',
    'visibility_grid' => '',
    'full_text_search' => 
    array (
        'boost' => 1,
    ),
    'required' => false,
    'name' => 'provider',
    'vname' => 'LBL_PROVIDER',
    'type' => 'enum',
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
    'len' => 100,
    'size' => '20',
    'options' => 'dotb_credit_provider_list',
);

?>
