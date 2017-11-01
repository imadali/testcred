<?php

$dictionary['Opportunity']['fields']['approved_ppi_flex'] = 
    array (
      'labelValue' => 'PPI BANK-now FLEX',
      'calculated' => '1',
      'formula' => '',
      'enforced' => '',
      'dependency' => 'equal($provider_id_c,"bank_now_flex")',
      'required' => false,
      'name' => 'approved_ppi_flex',
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
      'default' => false,
      'len' => 255,
    );