<?php

$dictionary['Contact']['fields']['profile_id'] = array (
    'name' => 'profile_id',
    'vname' => 'LBL_PROFILE_ID',
    'type' => 'varchar',
    'len' => '100',
    'duplicate_on_record_copy' => 'always',
    'comment' => 'Profile Id of the contact',
    'required' => false,
    'audited' => true,
    'importable' => 'true',
    'reportable' => true,
    'massupdate' => false,
    'comments' => 'The title of the contact',
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => '1',
    'merge_filter' => 'enabled',
    'full_text_search' => 
    array (
        'enabled' => true,
        'searchable' => false,
        'boost' => 1,
    ),
    'calculated' => false,
);

?>
