<?php
/**
* CRED-961: Additional fields "Freibetrag KREMO" and "Freibetrag tatsächlich"
*/
$dictionary['Opportunity']['fields']['actual_margin_flex'] = array(
    'labelValue' => 'Actual Margin BANK-now FLEX',
    'enforced' => 'false',
    'required' => false,
    'name' => 'actual_margin_flex',
    'vname' => 'LBL_KREMO_ACTUAL_MARGIN_FLEX',
    'type' => 'varchar',
    'massupdate' => false,
    'default' => '',
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'false',
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'enabled',
    'calculated' => false,
    'len' => '255',
    'dependency' => 'equal($provider_id_c,"bank_now_flex")',
);

?>