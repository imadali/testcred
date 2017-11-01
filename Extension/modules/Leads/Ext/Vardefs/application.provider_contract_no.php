<?php


$dictionary['Lead']['fields']['leads_opportunities_1.provider_contract_no'] = array(
        'required' => false,
        'source' => 'non-db',
        'name' => 'leads_opportunities_1.provider_contract_no',
        'vname' => 'LBL_PROVIDER_CONTRACT_NUMBER',
        'type' => 'varchar',        
        'default' => NULL,
        'no_default' => false,
        'comments' => '',
        'help' => '',
        'importable' => 'false',
        'audited' => false,
        'reportable' => false,
        'merge_filter' => 'disabled',
        'calculated' => false,
        'len' => 255,
        'quicksearch' => 'enabled',
        'studio' => 'visible',
);

/**
 * CRED-945 : Finding corresponding Lead for Archiving E-Mail
 * Custom Filter for Provider Application Number
 */
$dictionary['Lead']['fields']['leads_opportunities_1.provider_application_no_c'] = array(
        'required' => false,
        'source' => 'non-db',
        'name' => 'leads_opportunities_1.provider_application_no_c',
        'vname' => 'LBL_PROVIDER_APPLICATION_NO',
        'type' => 'varchar',        
        'default' => NULL,
        'no_default' => false,
        'comments' => '',
        'help' => '',
        'importable' => 'false',
        'audited' => false,
        'reportable' => false,
        'merge_filter' => 'disabled',
        'calculated' => false,
        'quicksearch' => 'enabled',
        'studio' => 'visible',
);

