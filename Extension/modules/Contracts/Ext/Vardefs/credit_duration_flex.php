<?php

$dictionary['Contract']['fields']['credit_duration_flex'] =
    array(
        'labelValue' => 'Duration BANK-now FLEX',
        'enforced' => '',
        'dependency' => 'equal($provider_id_c,"bank_now_flex")',
        'required' => false,
        'name' => 'credit_duration_flex',
        'vname' => 'LBL_CREDIT_DURATION_FLEX',
        'type' => 'int',
        'massupdate' => false,
        'no_default' => false,
        'comments' => '',
        'help' => '',
        'importable' => 'true',
        'duplicate_merge' => 'enabled',
        'duplicate_merge_dom_value' => 1,
        'audited' => true,
        'reportable' => true,
        'unified_search' => false,
        'merge_filter' => 'disabled',
        'calculated' => false,
        'len' => 255,
        'size' => '20',
    );
