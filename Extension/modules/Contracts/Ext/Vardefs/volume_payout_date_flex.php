<?php

$dictionary['Contract']['fields']['volume_payout_date_flex'] =
    array (
      'name' => 'volume_payout_date_flex',
      'label' => 'LBL_VOLUME_PAYOUT_DATE_FLEX',
      'vname' => 'LBL_VOLUME_PAYOUT_DATE_FLEX',
      'type' => 'date',
      'module' => 'Contract',
      'default_value' => '',
      'help' => '',
      'comment' => '',
      'mass_update' => false,
      'required' => false,
      'reportable' => true,
      'audited' => true,
      'duplicate_merge' => 'enabled',
      'importable' => true,
      'exportable' => true,
      'merge_filter' => 'selected',
      'duplicate_merge_dom_value' => '1',
      'dependency' => 'equal($provider_id_c,"bank_now_flex")',
    );