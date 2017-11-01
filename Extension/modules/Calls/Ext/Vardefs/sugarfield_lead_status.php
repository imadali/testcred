<?php

/**
 * CRED-940 : Sync Tasks Behaviour with Calls ( Fields & Filters )
 */
$dictionary['Call']['fields']['lead_status_c'] = array(
    'required' => false,
    'name' => 'lead_status_c',
    'vname' => 'LBL_LEAD_STATUS',
    'type' => 'enum',
    'massupdate' => true,
    'default' => '',
    'no_default' => false,
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
    'options' => 'dotb_credit_request_status_list',
);
