<?php

/**
 * CRED-940 : Sync Tasks Behaviour with Calls ( Fields & Filters )
 */
$dictionary['Call']['fields']['provider_contract_no'] = array(
        'name' => 'provider_contract_no',
        'labelValue' => 'Provider Contract Number',
        'vname' => 'LBL_PROVIDER_CONTRACT_NUMBER',
        'dependency' => '',
        'required' => false,
        'type' => 'varchar',
        'massupdate' => true,
        'enforced' => '',
        'default' => '',
        'no_default' => false,
        'comments' => '',
        'help' => '',
        'importable' => 'true',
        'duplicate_merge' => 'enabled',
        'duplicate_merge_dom_value' => '1',
        'audited' => true,
        'reportable' => true,
        'merge_filter' => 'disabled',
        'calculated' => false,
        'len' => 255,
);
?>