<?php

$dictionary['Contract']['fields']['interest_rate_flex'] =
    array (
      'labelValue' => 'Interest Rate Bank-now FLEX',
      'enforced' => '',
      'dependency' => 'equal($provider_id_c,"bank_now_flex")',
      'required' => false,
      'name' => 'interest_rate_flex',
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
      'calculated' => false,
      'len' => 18,
      'size' => '20',
      'enable_range_search' => false,
      'precision' => 2,
    );