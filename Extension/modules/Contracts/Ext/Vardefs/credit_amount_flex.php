<?php

$dictionary['Contract']['fields']['credit_amount_flex'] =
    array (
      'labelValue' => 'Limit BANK-now FLEX',
      'default' => NULL,
      'precision' => 2,
      'enforced' => '',
      'dependency' => 'equal($provider_id_c,"bank_now_flex")',
      'type' => 'currency',
      'related_fields' => 
      array (
        0 => 'currency_id',
        1 => 'base_rate',
      ),
      'required' => false,
      'name' => 'credit_amount_flex',
      'vname' => 'LBL_CREDIT_AMOUNT_FLEX',
      'massupdate' => false,
      'no_default' => false,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'enabled',
      'duplicate_merge_dom_value' => 1,
      'audited' => true,
      'reportable' => true,
      'unified_search' => false,
      'merge_filter' => 'disabled',
      'calculated' => false,
      'len' => 18,
      'size' => '20',
      'enable_range_search' => false,
    );