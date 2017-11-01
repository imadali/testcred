<?php

$dictionary['Lead']['fields']['app_approval_user'] = array(
    'name' => 'app_approval_user',
    'vname' => 'LBL_APP_APPROVAL_USER',
    'type' => 'varchar',
    'massupdate' => false,
    'comments' => 'Full text of the note',
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => '1',
    'merge_filter' => 'disabled',
    'calculated' => false,
    'required' => false,
    'len'=>50,
    'size'=>50,
);
$dictionary['Lead']['fields']['cc_id'] = array (
    'name' => 'cc_id',
    'vname' => 'LBL_CC_ID',
    'type' => 'varchar',
    'len' => '36',
    'reportable' => true,
    'massupdate' => false,
    'audited' => true,
 );
