<?php

$viewdefs['base']['view']['subpanel-list'] = array(
    'template' => 'recordlist',
    'favorite' => true,
    'rowactions' => array(
        'actions' => array(
            array(
                'type' => 'rowaction',
                'css_class' => 'btn',
                'tooltip' => 'LBL_PREVIEW',
                'event' => 'list:preview:fire',
                'icon' => 'fa-eye',
                'acl_action' => 'view',
                'allow_bwc' => false,
            ),
//             array(
//                 'type' => 'rowaction',
//                 'name' => 'quickedit_button',
//                 'icon' => 'fa-pencil',
//                 'label' => 'LBL_QUICKEDIT_BUTTON',
//                 'event' => 'list:quickedit:fire',
//                 'acl_action' => 'edit',
//                 'allow_bwc' => false
//             ),
            array(
                'type' => 'rowaction',
                'name' => 'edit_button',
                'icon' => 'fa-pencil',
                'label' => 'LBL_EDIT_IN_LINE_BUTTON',
                'event' => 'list:editrow:fire',
                'acl_action' => 'edit',
                'allow_bwc' => true,
            ),
            array(
                'type' => 'unlink-action',
                'icon' => 'fa-chain-broken',
                'label' => 'LBL_UNLINK_BUTTON',
            ),
        ),
    ),
    'last_state' => array(
        'id' => 'subpanel-list-quickedit',
    ),
);
    
