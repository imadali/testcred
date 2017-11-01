<?php

$dictionary['Contact']['fields']['credit_potential_amount'] = array (
    'precision' => '2',
    'enforced' => '',
    'dependency' => '',
    'related_fields' => 
    array (
        0 => 'currency_id',
        1 => 'base_rate',
    ),
    'required' => false,
    'name' => 'credit_potential_amount',
    'vname' => 'LBL_CREDIT_POTENTIAL_AMOUNT',
    'type' => 'currency',
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
    'len' => '26',
    'size' => '20',
    'enable_range_search' => false,
);

?>