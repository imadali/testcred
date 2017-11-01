<?php

$dictionary['Opportunity']['fields']['applied_credit_duration_flex'] = 
    array (
      'labelValue' => 'Duration BANK-now FLEX',
      'full_text_search' => 
      array (
        'enabled' => true,
        'searchable' => false,
        'boost' => 1,
      ),
      'enforced' => '',
      'required' => true,
      'name' => 'applied_credit_duration_flex',
      'vname' => 'LBL_CREDIT_DURATION_FLEX',
      'type' => 'int',
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
      'len' => '255',
      'size' => '20',
      'enable_range_search' => false,
      'disable_num_format' => NULL,
      'min' => false,
      'max' => false,
      'dependency' => 'equal($provider_id_c,"bank_now_flex")',
    );