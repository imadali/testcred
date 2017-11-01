<?php

$dictionary['Opportunity']['fields']['soko_flex'] =
    array(
      'labelValue' => 'SOKO BANK-now FLEX',
      'enforced' => '',
      'required' => false,      
      'name' => 'soko_flex',
      'vname' => 'LBL_SOKO_FLEX',
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

     

