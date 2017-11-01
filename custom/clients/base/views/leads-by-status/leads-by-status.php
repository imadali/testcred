<?php

global $app_list_strings;
$list = array();
if (isset($app_list_strings['dotb_credit_request_status_list'])) {
    $list = $app_list_strings['dotb_credit_request_status_list'];
}
$tabs = array();
foreach ($list as $key => $value) {
    $capital = strtoupper(str_replace(" ", "_", $value));

    if ($value != "") {
        $tabs[] = array(
            'active' => true,
            'filters' => array(
                'credit_request_status_id_c' => array('$in' => array($key))
            ),
            'label' => $value,
            'link' => 'leads',
            'module' => 'Leads',
            'order_by' => 'date_entered:asc',
            'record_date' => 'date_entered',
            'fields' => array(
                'name',
                'assigned_user_name',
                'assigned_user_id',
                'date_entered',
                'credit_amount_c',
            ),
        );
    }
}

$viewdefs['base']['view']['leads-by-status'] = array(
    'dashlets' => array(
        array(
            'label' => 'LBL_LEADS_BY_STATUS_DASHLET',
            'description' => 'LBL_LEADS_BY_STATUS_DASHLET_DESCRIPTION',
            'config' => array(
                'limit' => 50,
                'visibility' => 'user',
            ),
            'preview' => array(
                'limit' => 50,
                'visibility' => 'user',
            ),
            'filter' => array(
                'module' => array(
                    'Home',
                ),
                'view' => 'record',
            ),
        ),
    ),
    'custom_toolbar' => array(
        'buttons' => array(
            array(
                'type' => 'actiondropdown',
                'no_default_action' => true,
                'icon' => 'fa-plus',
                'buttons' => array(
                ),
            ),
            array(
                'dropdown_buttons' => array(
                    array(
                        'type' => 'dashletaction',
                        'action' => 'editClicked',
                        'label' => 'LBL_DASHLET_CONFIG_EDIT_LABEL',
                    ),
                    array(
                        'type' => 'dashletaction',
                        'action' => 'refreshClicked',
                        'label' => 'LBL_DASHLET_REFRESH_LABEL',
                    ),
                    array(
                        'type' => 'dashletaction',
                        'action' => 'toggleClicked',
                        'label' => 'LBL_DASHLET_MINIMIZE',
                        'event' => 'minimize',
                    ),
                    array(
                        'type' => 'dashletaction',
                        'action' => 'removeClicked',
                        'label' => 'LBL_DASHLET_REMOVE_LABEL',
                    ),
                ),
            ),
        ),
    ),
    'panels' => array(
        array(
            'name' => 'panel_body',
            'columns' => 2,
            'labelsOnTop' => true,
            'placeholders' => true,
            'fields' => array(
                array(
                    'name' => 'visibility',
                    'label' => 'LBL_DASHLET_CONFIGURE_MY_ITEMS_ONLY',
                    'type' => 'enum',
                    'options' => 'tasks_visibility_options',
                ),
            ),
        ),
    ),
    'tabs' =>
    $tabs,
);
