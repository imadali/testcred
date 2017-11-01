<?php

$dictionary['Contract']['fields']['customer_first_payment_flex'] = 
    array (
      'precision' => '2',
      'label' => 'LBL_FIRST_PAYMENT_FLEX',
      'enforced' => '',
      'related_fields' => 
      array (
        0 => 'currency_id',
        1 => 'base_rate',
      ),
      'required' => false,
      'name' => 'customer_first_payment_flex',
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
      'dependency' => 'equal($provider_id_c,"bank_now_flex")',
    );