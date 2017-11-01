<?php
 // created: 2016-10-05 14:30:55
$dictionary['Contract']['fields']['customer_interest_rate_flex'] =
    array (
      'label' => 'LBL_INTEREST_RATE_FLEX',
      'enforced' => '',
      'dependency' => 'equal($provider_id_c,"bank_now_flex")',
      'required' => false,
      'name' => 'customer_interest_rate_flex',
      'vname' => 'LBL_INTEREST_RATE_FLEX',
      'type' => 'decimal',
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
      'len' => 18,
      'precision' => 2,
    );

