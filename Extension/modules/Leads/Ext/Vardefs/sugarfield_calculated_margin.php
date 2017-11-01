<?php
/**
* CRED-961: Additional fields "Freibetrag KREMO" and "Freibetrag tatsächlich"
*/
$dictionary['Lead']['fields']['calculated_margin'] = array(
    'labelValue' => 'Calculated Margin',
    'enforced' => 'false',
    'dependency' => '',
    'required' => false,
    'name' => 'calculated_margin',
    'vname' => 'LBL_KREMO_MARGIN',
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
);

?>