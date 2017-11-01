<?php
 // created: 2016-10-05 14:30:55
$dictionary['Contract']['fields']['contract_transfer_fee_flex'] = 
    array (
      'label' => 'LBL_CONTRACT_TRANSFER_FEE_FLEX',
      'enforced' => '',
      'required' => false,
      'name' => 'contract_transfer_fee_flex',
      'vname' => 'LBL_CONTRACT_TRANSFER_FEE_FLEX',
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

