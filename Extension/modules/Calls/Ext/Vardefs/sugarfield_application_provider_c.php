<?php

/**
 * CRED-940 : Sync Tasks Behaviour with Calls ( Fields & Filters )
 */
$dictionary['Call']['fields']['application_provider_c'] = array(
    'labelValue' => 'Provider',
    'enforced' => '',
    'dependency' => '',
    'required' => false,
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