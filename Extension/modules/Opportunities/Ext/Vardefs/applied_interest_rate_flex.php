<?php

$dictionary['Opportunity']['fields']['applied_interest_rate_flex'] = 
    array(
      'labelValue' => 'Interest Rate BANK-now FLEX',
      'enforced' => '',
      'required' => true,
      'name' => 'applied_interest_rate_flex',
      'vname' => 'LBL_INTEREST_RATE_FLEX',
      'type' => 'decimal',
      'massupdate' => false,
      'default' => NULL,
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
      'len' => '18',
      'enable_range_search' => false,
      'precision' => 2,
      'dependency' => 'equal($provider_id_c,"bank_now_flex")',
    );