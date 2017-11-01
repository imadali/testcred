<?php

$dictionary['Contract']['fields']['volume_commission_agent_flex'] =
    array (
      'enforced' => '',
      'related_fields' => 
      array (
        0 => 'currency_id',
        1 => 'base_rate',
      ),
      'required' => false,
      'name' => 'volume_commission_agent_flex',
      'label' => 'LBL_VOLUME_COMMISSION_AGENT_FLEX',
      'vname' => 'LBL_VOLUME_COMMISSION_AGENT_FLEX',
      'type' => 'currency',
      'massupdate' => false,
      'default' => NULL,
      'no_default' => false,
      'comments' => '',
      'help' => '',
      'importable' => true,
      'duplicate_merge' => 'enabled',
      'duplicate_merge_dom_value' => '1',
      'audited' => true,
      'reportable' => true,
      'unified_search' => false,
      'merge_filter' => 'selected',
      'calculated' => false,
      'len' => '26',
      'enable_range_search' => false,
      'precision' => 2,
      'dependency' => 'equal($provider_id_c,"bank_now_flex")',
    );