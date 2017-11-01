<?php
 // created: 2016-10-05 14:30:55
$dictionary['Contract']['fields']['customer_credit_amount_flex'] = 
    array (
      'label' => 'LBL_CUSTOMER_CREDIT_AMOUNT_FLEX',
      'enforced' => '',
      'dependency' => 'equal($provider_id_c,"bank_now_flex")',
      'related_fields' => 
      array (
        0 => 'currency_id',
        1 => 'base_rate',
      ),
      'precision' => 2,
      'required' => false,
      'name' => 'customer_credit_amount_flex',
      'vname' => 'LBL_CUSTOMER_CREDIT_AMOUNT_FLEX',
      'type' => 'currency',
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
      'default' => '',
      'calculated' => false,
      'len' => 26,
    );

