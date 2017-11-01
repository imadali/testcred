<?php

/**
 * CRED-940 : Sync Tasks Behaviour with Calls ( Fields & Filters )
 */
$dictionary['Call']['fields']['application_name_c'] = array(
    'labelValue' => 'Application',
    'dependency' => '',
    'visibility_grid' => '',
    'required' => false,
    'name' => 'application_name_c',
    'vname' => 'LBL_APPLICATION_NAME',
    'type' => 'enum',
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
    'len' => 100,
    'size' => '20',
    'options' => 'related_application_list',
);
?>