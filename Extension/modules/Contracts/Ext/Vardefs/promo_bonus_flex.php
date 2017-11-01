<?php

$dictionary['Contract']['fields']['promo_bonus_flex'] =
    array (
      'labelValue' => 'Promo bonus BANK-now FLEX',
      'enforced' => '',
      'dependency' => 'equal($provider_id_c,"bank_now_flex")',
      'required' => false,
      'name' => 'promo_bonus_flex',
      'vname' => 'LBL_PROMO_BONUS_FLEX',
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
      'precision' => 2,
    );