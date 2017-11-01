<?php

$dictionary['Opportunity']['fields']['applied_first_payment_flex'] = 
    array (
      'precision' => '2',
      'labelValue' => 'First Payment Amount BANK-now Flex',
      'enforced' => '',
      'related_fields' => 
      array (
        0 => 'currency_id',
        1 => 'base_rate',
      ),
      'required' => false, // CRED-1020 : Application-Handling FLEX (non-mandatory)
      'name' => 'applied_first_payment_flex',
      'vname' => 'LBL_FIRST_PAYMENT_FLEX',
      'type' => 'currency',
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
      'len' => '26',
      'enable_range_search' => false,
      'dependency' => 'equal($provider_id_c,"bank_now_flex")',
    );