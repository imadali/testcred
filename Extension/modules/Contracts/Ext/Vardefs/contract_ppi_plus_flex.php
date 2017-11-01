<?php
 // created: 2016-10-05 14:30:55
$dictionary['Contract']['fields']['contract_ppi_plus_flex'] = 
    array (
      'label' => 'LBL_CONTRACT_PPI_PLUS_FLEX',
      'enforced' => '',
      'required' => false,
      'name' => 'contract_ppi_plus_flex',
      'vname' => 'LBL_CONTRACT_PPI_PLUS_FLEX',
      'type' => 'bool',
      'massupdate' => false,
      'default' => false,
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
      'len' => '255',
      'dependency' => 'equal($provider_id_c,"bank_now_flex")',
    );

