<?php

$dictionary["Lead"]["fields"]["calc_contract_date_c"] = array (
  'name' => 'calc_contract_date_c',
  'type' => 'date',
  'vname' => 'LBL_CALC_CONTRACT_DATE_C',
);

$dictionary["Lead"]["fields"]["contract_paying_date_c"] = array (
  'name' => 'contract_paying_date_c',
  'type' => 'date',
  'vname' => 'LBL_CONTRACT_PAYING_DATE_C',
);

$dictionary["Lead"]["fields"]['date_entered_date_c'] =
    array (
      'labelValue' => 'Date entered date',
      'enforced' => '',
      'dependency' => '',
      'required' => false,
      'source' => 'custom_fields',
      'name' => 'date_entered_date_c',
      'vname' => 'LBL_DATE_ENTERED_DATE',
      'type' => 'varchar',
      'massupdate' => true,
      'default' => NULL,
      'no_default' => false,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '1',
      'audited' => false,
      'reportable' => true,
      'unified_search' => false,
      'merge_filter' => 'disabled',
      'calculated' => false,
      'enable_range_search' => false,
    );

?>