<?php

// created: 2016-03-16 06:31:10
$viewdefs['Leads']['base']['view']['subpanel-for-leads-leads_leads_1'] = array(
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
            array(
                'type' => 'lead_no_access',
                'name' => 'edit_button',
                'icon' => 'fa-pencil',
                'label' => 'LBL_EDIT_BUTTON',
                'event' => 'list:editrow:fire',
                'acl_action' => 'edit',
                'allow_bwc' => true,
            ),
//            array(
//                'type' => 'unlink-action',
//                'icon' => 'fa-chain-broken',
//                'label' => 'LBL_UNLINK_BUTTON',
//            ),
        ),
    ),
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
                    'name' => 'full_name',
                    'type' => 'fullname',
                    'fields' =>
                    array(
                        0 => 'salutation',
                        1 => 'first_name',
                        2 => 'last_name',
                    ),
                    'link' => true,
                    'css_class' => 'full-name',
                    'label' => 'LBL_LIST_NAME',
                    'enabled' => true,
                    'default' => true,
                ),
                1 =>
                array(
                    'name' => 'credit_request_status_id_c',
                    'label' => 'LBL_CREDIT_REQUEST_STATUS_ID',
                    'enabled' => true,
                    'default' => true,
                ),
                2 =>
                array(
                    'label' => 'LBL_CREDIT_REQUEST_SUBSTATUS_ID',
                    'enabled' => true,
                    'default' => true,
                    'name' => 'credit_request_substatus_id_c',
                ),
                3 =>
                array(
                    'name' => 'assigned_user_name',
                    'target_record_key' => 'assigned_user_id',
                    'target_module' => 'Employees',
                    'label' => 'LBL_LIST_ASSIGNED_TO_NAME',
                    'enabled' => true,
                    'default' => true,
                ),
                4 =>
                array(
                    'label' => 'LBL_DATE_ENTERED',
                    'enabled' => true,
                    'default' => true,
                    'name' => 'date_entered',
                ),
                5 =>
                array(
                    'label' => 'LBL_INPUT_PROCESS_TYPE_ID',
                    'enabled' => true,
                    'default' => true,
                    'name' => 'input_process_type_id_c',
                ),
                6 =>
                array(
                    'name' => 'contract_bank',
                    'label' => 'LBL_CONTRACT_BANK',
                    'enabled' => true,
                    'default' => true,
                    'readonly' => true,
                ),
                7 =>
                array(
                    'name' => 'contract_paying_date',
                    'label' => 'LBL_CONTRACT_PAYING_DATE',
                    'enabled' => true,
                    'default' => true,
                    'readonly' => true,
                ),
                
            ),
        ),
    ),
    'type' => 'subpanel-list',
);
