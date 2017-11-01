<?php
/**
 * CRED-940 : Sync Tasks Behaviour with Calls ( Fields & Filters )
 */
$dictionary['Call']['fields']['user_id_c'] = array (
      'required' => false,
      'name' => 'user_id_c',
      'vname' => 'LBL_USER_APPROVAL_USER_ID',
      'type' => 'id',
      'massupdate' => false,
      'default' => NULL,
      'no_default' => false,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'enabled',
      'duplicate_merge_dom_value' => '1',
      'audited' => false,
      'reportable' => false,
      'unified_search' => false,
      'merge_filter' => 'disabled',
      'calculated' => false,
      'len' => '36',
      'size' => '20',
);
 ?>