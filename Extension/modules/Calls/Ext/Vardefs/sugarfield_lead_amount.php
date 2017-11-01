<?php

/**
 * CRED-940 : Sync Tasks Behaviour with Calls ( Fields & Filters )
 */
$dictionary['Call']['fields']['lead_amount_c'] = array(
    'labelValue' => 'Amount',
    'dependency' => '',
    'related_fields' => 
    array (
        0 => 'currency_id',
        1 => 'base_rate',
	),
    'required' => false,
    'name' => 'lead_amount_c',
    'vname' => 'LBL_LEAD_AMOUNT',
    'type' => 'currency',
    'massupdate' => false,
    'default' => NULL,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => '1',
    'audited' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'calculated' => true,
    'len' => '26',
    'size' => '20',
    'enable_range_search' => false,
    'precision' => 2,
);

$dictionary['Call']['fields']['currency_id'] = array (
    'required' => false,
    'name' => 'currency_id',
    'vname' => 'LBL_CURRENCY_ID',
    'type' => 'currency_id',
    'massupdate' => false,
    'default' => NULL,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => '1',
    'audited' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'calculated' => false,
    'len' => '36',
    'size' => '20',
    'dbType' => 'id',
    'studio' => 'visible',
    'function' => 'getCurrencies',
    'function_bean' => 'Currencies',
);