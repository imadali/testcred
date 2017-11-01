<?php

$dictionary['Contract']['fields']['ppi_flex'] =
    array (
      'labelValue' => 'PPI BANK-now FLEX',
      'default' => false,
      'calculated' => '1',
      'formula' => 'equal($provider_id_c,"bank_now_flex")',
      'enforced' => '',
      'dependency' => 'equal($provider_id_c,"bank_now_flex")',
      'required' => false,
      'name' => 'ppi_flex',
      'vname' => 'LBL_PPI_FLEX',
      'type' => 'bool',
      'massupdate' => false,
      'no_default' => false,
      'comments' => '',
      'help' => '',
      'importable' => 'false',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => 0,
      'audited' => true,
      'reportable' => true,
      'unified_search' => false,
      'merge_filter' => 'disabled',
      'len' => 255,
    );