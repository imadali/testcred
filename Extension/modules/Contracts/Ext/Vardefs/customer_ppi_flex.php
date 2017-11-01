<?php
 // created: 2016-10-05 14:30:55
$dictionary['Contract']['fields']['customer_ppi_flex'] = 
    array (
      'labelValue' => 'LBL_PPI_FLEX',
      'enforced' => '',
      'dependency' => 'and(not(equal($provider_id_c,"rci")),not(equal($provider_id_c,"")),equal($provider_id_c,"bank_now_flex"))',
      'required' => false,
      'name' => 'customer_ppi_flex',
      'vname' => 'LBL_PPI_FLEX',
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

