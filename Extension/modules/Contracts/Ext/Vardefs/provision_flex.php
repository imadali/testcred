<?php

$dictionary['Contract']['fields']['provision_flex'] =
    array (
      'labelValue' => 'Provision BANK-now FLEX',
      'enforced' => '',
      'dependency' => 'equal($provider_id_c,"bank_now_flex")',
      'required' => false,
      'name' => 'provision_flex',
      'vname' => 'LBL_PROVISION_FLEX',
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
      'enable_range_search' => false,
      'precision' => 2,
    );