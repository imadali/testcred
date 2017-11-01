<?php

// created: 2016-11-16 16:22:22
$viewdefs['Notes']['base']['filter']['default'] = array(
    'default_filter' => 'all_records',
    'filters' =>
    array(
        0 =>
        array(
            'id' => 'created_by_me',
            'name' => 'LBL_CREATED_BY_ME',
            'filter_definition' =>
            array(
                '$creator' => '',
            ),
            'editable' => false,
        ),
    ),
    'fields' =>
    array(
        'name' =>
        array(
        ),
        'contact_name' =>
        array(
        ),
        'parent_name' =>
        array(
        ),
        'date_entered' =>
        array(
        ),
        'date_modified' =>
        array(
        ),
        'tag' =>
        array(
        ),
        'dotb_flag' =>
        array(
            'name' => 'dotb_flag',
            'vname' => 'LBL_DOTB_FLAG',
        ),
        'email_notes' => 
        array (
			'predefined_filter' => true,
			'vname' => 'LBL_EMAIL_NOTES',
        ),
        'team_name' =>
        array(
        ),
        '$owner' =>
        array(
            'predefined_filter' => true,
            'vname' => 'LBL_CURRENT_USER_FILTER',
        ),
        '$favorite' =>
        array(
            'predefined_filter' => true,
            'vname' => 'LBL_FAVORITES_FILTER',
        ),
    ),
);
