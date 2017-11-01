<?php
/**
* CRED-767: DV-Score additional field for PDF link
*/
$dictionary['Lead']['fields']['credit_consumer_check'] = array(
    'labelValue' => 'Credit Consumer Check',
    'full_text_search' => 
    array (
        'boost' => '0',
        'enabled' => false,
    ),
    'enforced' => 'false',
    'dependency' => '',
    'required' => false,
    'name' => 'credit_consumer_check',
    'vname' => 'LBL_CREDIT_CONSUMER_CHECK',
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
    'source' => 'non-db',
);

?>