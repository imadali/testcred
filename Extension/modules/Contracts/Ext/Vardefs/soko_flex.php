<?php

$dictionary['Contract']['fields']['soko_flex'] =
    array (
      'labelValue' => 'SOKO BANK-now FLEX',
      'enforced' => '',
      'dependency' => 'equal($provider_id_c,"bank_now_flex")',
      'required' => false,
      'name' => 'soko_flex',
      'vname' => 'LBL_SOKO_FLEX',
      'type' => 'bool',
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
      'default' => false,
      'calculated' => false,
      'len' => 255,
    );