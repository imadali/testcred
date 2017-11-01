<?php

$viewdefs['dotb6_contact_activities']['base']['view']['subpanel-for-accounts-historical_summary'] = array(
    'favorite' => true,
    'panels' =>
    array(
        0 =>
        array(
            'name' => 'panel_header',
            'label' => 'LBL_PANEL_1',
            'fields' =>
            array(
				0 =>
                array(
                    'name' => 'date_entered',
                    'sortable' => true,
                    'default' => true,
                    //'type' => 'date_due',
                ),
                1 =>
                array(
                    'name' => 'moduleName',
                    'label' => 'LBL_MODULE_TYPE',
                    'enabled' => true,
                    'default' => true,
                    'width' => 'small',
					'sortable' => false,
                ),
                2 =>
                array(
                    'name' => 'name',
                    'label' => 'LBL_SUBJECT',
                    'enabled' => true,
                    'type' => 'name',
                    'id' => 'ID',
                    'link' => true,
                    'sortable' => false,
                    'default' => true,
                    'width' => 'medium',
                ),
                3 =>
                array(
                    'name' => 'description',
                    'type' => 'textarea',
                    'label' => 'LBL_DESCRIPTION',
                    'enabled' => true,
                    'default' => true,
                    'width' => 'large'
                ),
                4 =>
                array(
                    'name' => 'status',
                    'label' => 'LBL_STATUS',
                    'type' => 'status',
                    'enabled' => true,
                    'default' => true
                ),
                5 => array(
                    'name' => 'date_due',
                    'label' => 'LBL_DATE_DUE',
                    'enabled' => true,
                    'default' => true,
                    'width' => 'small',
                    'type' => 'date_due',
                ),
                6 => array(
                    'name' => 'assigned_user_name',
                    'label' => 'LBL_RESPONSIBLE',
                    'enabled' => true,
                    'id' => 'ASSIGNED_USER_ID',
                    'link' => true,
                    'default' => true,
                    'width' => 'large',
                ),
                7 =>
                array(
                    'name' => 'team_name',
                    'label' => 'LBL_TEAMS',
                    'enabled' => true,
                    'id' => 'TEAM_ID',
                    'link' => true,
                    'sortable' => false,
                    'default' => true,
                ),
            ),
        ),
    ),
    'orderBy' =>
    array(
        'field' => 'date_entered',
        'direction' => 'desc',
    ),
    'rowactions' =>
    array(
        'actions' =>
        array(
            0 =>
            array(
                'type' => 'rowaction',
                'css_class' => 'btn',
                'tooltip' => 'LBL_PREVIEW',
                'event' => 'list:activity-preview:fire',
                'icon' => 'fa-eye',
                'acl_action' => 'view',
                'allow_bwc' => false,
            ),
            1 =>
            array(
                'type' => 'rowaction',
                'name' => 'quickedit_button',
                'icon' => 'fa-pencil',
                'label' => 'LBL_QUICKEDIT_BUTTON',
                'event' => 'list:activity-quickedit:fire',
                'acl_action' => 'edit',
                'allow_bwc' => false,
            ),
            2 =>
            array(
                'type' => 'unlink-action',
                'icon' => 'fa-chain-broken',
                'label' => 'LBL_UNLINK_BUTTON',
            ),
            3 =>
            array(
                'type' => 'closetask',
                'name' => 'close_task',
                'icon' => 'fa-pencil',
                'label' => 'LBL_CLOSE_TASK',
                'event' => 'list:close_task:fire',
            ),
            /*4 =>
            array(
                'type' => 'hidetask',
                'name' => 'hide_task',
                'icon' => 'fa-pencil',
                'label' => 'LBL_HIDE_TASK',
                'event' => 'list:hide_task:fire',
            ),
            5 =>
            array(
                'type' => 'closetask',
                'name' => 'close_and_hide_task',
                'icon' => 'fa-pencil',
                'label' => 'LBL_CLOSE_HIDE_TASK',
                'event' => 'list:close_and_hide_task:fire',
            ),*/
            4 =>
            array(
                'type' => 'closetask',
                'name' => 'share',
                'label' => 'LBL_RECORD_SHARE_BUTTON',
                'event' => 'list:share_task:fire',
            ),
        ),
    ),
    'type' => 'subpanel-list',
);
