<?php

$viewdefs['base']['view']['consolidated-list'] = array(
    'template'=> 'recordlist',
    'last_state' => array(
        'id' => 'consolidated-list',
    ),
    'rowactions' => array(
        'actions' => array(
            array(
                'type' => 'rowaction',
                'css_class' => 'btn',
                'tooltip' => 'LBL_PREVIEW',
                'event' => 'list:consolidated-preview:fire',
                'icon' => 'fa-eye',
                'acl_action' => 'view',
            ), 
        ),
    ),
    'panels' =>
    array(
        0 =>
        array(
            'label' => 'LBL_PANEL_1',
            'fields' =>
            array(
                0 =>
                array(
                    'name' => 'name',
                    'link' => true,
                    'label' => 'LBL_LIST_SUBJECT',
                    'enabled' => true,
                    'default' => true,
                    'sortable' => true,
                    'type' => 'consolidated-text'
                ),
                1 =>
                array(
                    'name' => 'moduleName',
                    'label' => 'LBL_RECORD_TYPE',
                    'enabled' => true,
                    'default' => true,
                    'sortable' => false,
                ),
                2 =>
                array(
                    'name' => 'mixed_date_due',
                    'label' => 'LBL_LIST_DUE_DATE',
                    'type' => 'consolidated-datetimecombo-colorcoded',
                    'css_class' => 'overflow-visible',
                    'completed_status_value' => array('Held','closed'),
                    'enabled' => true,
                    'default' => true,
                    'sortable' => true,
                ),
                3 =>
                array(
                    'name' => 'parent_name',
                    'label' => 'LBL_LIST_RELATED_TO',
                    'dynamic_module' => 'PARENT_TYPE',
                    'id' => 'PARENT_ID',
                    'link' => true,
                    'enabled' => true,
                    'default' => true,
                    'sortable' => false,
                    'ACLTag' => 'PARENT',
                    'type' => 'parent',
                    
                ),
                4 =>
                array(
                    'name' => 'assigned_user_name',
                    'label' => 'LBL_LIST_ASSIGNED_TO_NAME',
                    'id' => 'ASSIGNED_USER_ID',
                    'enabled' => true,
                    'default' => true,
                    'related_fields' => array('assigned_user_id'),
                    'type' => 'relate',
                ),
                5 =>
                array(
                    'name' => 'status',
                    'label' => 'LBL_LIST_STATUS',
                    'link' => false,
                    'enabled' => true,
                    'default' => false,
                    'type' => 'consolidated-status',
                ),
                6 => 
                array(
                    'name' => 'date_modified',
                    'label' => 'LBL_DATE_MODIFIED',
                    'type' => 'datetimecombo',
                    'enabled' => true,
                    'default' => true,
                    'readonly' => true,
                ),
                7 => 
                array(
                    'name' => 'date_entered',
                    'label' => 'LBL_DATE_ENTERED',
                    'type' => 'datetimecombo',
                    'enabled' => true,
                    'default' => true,
                    'readonly' => true,
                ),
            ),
        ),
    ),
);
