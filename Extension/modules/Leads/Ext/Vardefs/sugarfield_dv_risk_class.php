<?php
/**
* CRED-767: DV-Score additional field for Risk Class
*/
$dictionary['Lead']['fields']['dv_risk_class'] = array(
    'labelValue' => 'Deltavista Risk Class',
    'full_text_search' => 
    array (
        'boost' => '0',
        'enabled' => false,
    ),
    'enforced' => 'false',
    'dependency' => '',
    'required' => false,
    'name' => 'dv_risk_class',
    'vname' => 'LBL_DV_RISK_CLASS',
    'type' => 'varchar',
    'massupdate' => false,
    'default' => '',
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'false',
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'unified_search' => true,
    'merge_filter' => 'enabled',
    'calculated' => false,
    'len' => '255',
    'size' => '20',
    'dbType' => 'varchar',
);

?>