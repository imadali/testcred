<?php
global $current_user;
$user_id = $current_user->id;
$objTeams = new Team();
$teams = $objTeams->get_teams_for_user($user_id);
$teams_id = array();
foreach ($teams as $team) {
    $teams_id[] = $team->id;
}
$teams_id = join("', '", $teams_id);
$teams_id = "'" . $teams_id . "'";
$viewdefs['base']['view']['user-task-report'] = array(
    'dashlets' => array(
        array(
            'label' => 'LBL_USER_TASKS_DASHLET',
            'description' => 'LBL_USER_TASKS_DASHLET_DESCRIPTION',
            'config' => array(
                'limit' => 10,
            ),
            'preview' => array(
                'limit' => 10,
            ),
            'filter' => array(
                'module' => array(
                    'Home',
                ),
                'view' => 'record' 
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
                    array(
                        'type' => 'dashletaction',
                        'action' => 'createRecord',
                        'params' => array(
                            'module' => 'Tasks',
                            'link' => 'tasks',
                        ),
                        'label' => 'LBL_CREATE_TASK',
                        'acl_action' => 'create',
                        'acl_module' => 'Tasks',
                    ),
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
                array(
                    'name' => 'limit',
                    'label' => 'LBL_DASHLET_CONFIGURE_DISPLAY_ROWS',
                    'type' => 'enum',
                    'options' => 'tasks_limit_options',
                ),
            ),
        ),
    ),
    'tabs' => array(
        array(
            'active' => true,
            'filters' => array(
                'status' => array('$in' => array('', 'open', 'closed')),
                //'assigned_user_id' => "$current_user->id",
            ),
            'label' => 'LBL_TASKS_ASSIGNED',
            'link' => 'tasks',
            'module' => 'Tasks',
            'order_by' => 'assigned_date_c:asc',
            'record_date' => 'assigned_date_c',
            'row_actions' => array(
                array(
                    'type' => 'rowaction',
                    'icon' => 'fa-times-circle',
                    'css_class' => 'btn btn-mini',
                    'event' => 'active-tasks:close-task:fire',
                    'target' => 'view',
                    'tooltip' => 'LBL_ACTIVE_TASKS_DASHLET_COMPLETE_TASK',
                    'acl_action' => 'edit',
                ),
                array(
                    'type' => 'unlink-action',
                    'icon' => 'fa-chain-broken',
                    'css_class' => 'btn btn-mini',
                    'event' => 'tabbed-dashlet:unlink-record:fire',
                    'target' => 'view',
                    'tooltip' => 'LBL_UNLINK_BUTTON',
                    'acl_action' => 'edit',
                ),
            ),
            'overdue_badge' => array(
                'name' => 'date_due',
                'type' => 'overdue-badge',
                'css_class' => 'pull-right',
            ),
            'fields' => array(
                'name',
                'assigned_user_name',
                'assigned_user_id',
                'date_due',
            ),
        ),
        array(
            'filters' => array(
               // 'team_id' => array('$in' => array('closed')),
                'status' => array('$in' => array('closed')),
                //'assigned_user_id' => "$current_user->id",
            ),
            'label' => 'LBL_TASKS_RESOLVED',
            'link' => 'tasks',
            'module' => 'Tasks',
            'order_by' => 'assigned_date_c:asc',
            'record_date' => 'assigned_date_c',
            'row_actions' => array(
                array(
                    'type' => 'rowaction',
                    'icon' => 'fa-times-circle',
                    'css_class' => 'btn btn-mini',
                    'event' => 'active-tasks:close-task:fire',
                    'target' => 'view',
                    'tooltip' => 'LBL_ACTIVE_TASKS_DASHLET_COMPLETE_TASK',
                    'acl_action' => 'edit',
                ),
                array(
                    'type' => 'unlink-action',
                    'icon' => 'fa-chain-broken',
                    'css_class' => 'btn btn-mini',
                    'event' => 'tabbed-dashlet:unlink-record:fire',
                    'target' => 'view',
                    'tooltip' => 'LBL_UNLINK_BUTTON',
                    'acl_action' => 'edit',
                ),
            ),
            'fields' => array(
                'name',
                'assigned_user_name',
                'assigned_user_id',
                'date_due',
            ),
        ),
        array(
            'filters' => array(
                'status' => array('$in' => array('', 'open')),
                //'assigned_user_id' => "$current_user->id",
            ),
            'label' => 'LBL_TASKS_OPEN',
            'link' => 'tasks',
            'module' => 'Tasks',
            'order_by' => 'assigned_date_c:asc',
            'row_actions' => array(
                array(
                    'type' => 'rowaction',
                    'icon' => 'fa-times-circle',
                    'css_class' => 'btn btn-mini',
                    'event' => 'active-tasks:close-task:fire',
                    'target' => 'view',
                    'tooltip' => 'LBL_ACTIVE_TASKS_DASHLET_COMPLETE_TASK',
                    'acl_action' => 'edit',
                ),
                array(
                    'type' => 'unlink-action',
                    'icon' => 'fa-chain-broken',
                    'css_class' => 'btn btn-mini',
                    'event' => 'tabbed-dashlet:unlink-record:fire',
                    'target' => 'view',
                    'tooltip' => 'LBL_UNLINK_BUTTON',
                    'acl_action' => 'edit',
                ),
            ),
            'fields' => array(
                'name',
                'assigned_user_name',
                'assigned_user_id',
                'date_due',
            ),
        ),
    ),
    'visibility_labels' => array(
        'user' => 'LBL_ACTIVE_TASKS_DASHLET_USER_BUTTON_LABEL',
        'group' => 'LBL_ACTIVE_TASKS_DASHLET_GROUP_BUTTON_LABEL',
    ),
    'durationStatus' =>
    array(
        'name' => 'task_status_duration',
        'vname' => 'LBL_TASK_STATUS_DESCRIPTION',
        'type' => 'enum',
        'options' => 'task_history_duration_list',
    )
);
