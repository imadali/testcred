<?php
// created: 2016-05-30 15:26:45
$viewdefs['dotb6_contact_activities']['base']['view']['subpanel-for-opportunities-opportunities_dotb6_contact_activities'] = array (
  'favorite' => true,
  'panels' => 
  array (
    0 => 
    array (
      'name' => 'panel_header',
      'label' => 'LBL_PANEL_1',
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'parent_type',
          'enabled' => true,
          'default' => true,
          'width' => 'small',
        ),
        1 => 
        array (
          'name' => 'parent_name',
          'label' => 'LBL_PARENT_NAME',
          'enabled' => true,
          'id' => 'PARENT_ID',
          'link' => true,
          'sortable' => false,
          'default' => true,
          'width' => 'medium',
        ),
        2 => 
        array (
          'name' => 'date_entered',
          'sortable' => true,
          'width' => 'medium',
          'type' => 'show_only_date',
          'default' => true,
          'enabled' => true,
        ),
        3 => 
        array (
          'name' => 'description',
          'label' => 'LBL_DESCRIPTION',
          'enabled' => true,
          'sortable' => false,
          'default' => true,
          'width' => 'large',
        ),
        4 => 
        array (
          'name' => 'status',
          'label' => 'LBL_STATUS',
          'enabled' => true,
          'default' => true,
          'width' => 'small',
          'type' => 'status',
        ),
        5 => 
        array (
          'name' => 'date_end',
          'label' => 'LBL_DATE_DUE',
          'enabled' => true,
          'default' => true,
          'width' => 'small',
          'type' => 'date_due',
        ),
        6 => 
        array (
          'name' => 'assigned_user_name',
          'label' => 'LBL_RESPONSIBLE',
          'enabled' => true,
          'id' => 'ASSIGNED_USER_ID',
          'link' => true,
          'default' => true,
          'width' => 'large',
        ),
        7 => 
        array (
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
  array (
    'field' => 'date_entered',
    'direction' => 'desc',
  ),
  'rowactions' => 
  array (
    'actions' => 
    array (
      0 => 
      array (
        'type' => 'rowaction',
        'css_class' => 'btn',
        'tooltip' => 'LBL_PREVIEW',
        'event' => 'list:activity-preview:fire',
        'icon' => 'fa-eye',
        'acl_action' => 'view',
        'allow_bwc' => false,
      ),
      1 => 
      array (
        'type' => 'rowaction',
        'name' => 'quickedit_button',
        'icon' => 'fa-pencil',
        'label' => 'LBL_QUICKEDIT_BUTTON',
        'event' => 'list:activity-quickedit:fire',
        'acl_action' => 'edit',
        'allow_bwc' => false,
      ),
      2 => 
      array (
        'type' => 'unlink-action',
        'icon' => 'fa-chain-broken',
        'label' => 'LBL_UNLINK_BUTTON',
      ),
      3 => 
      array (
        'type' => 'closetask',
        'name' => 'close_task',
        'icon' => 'fa-pencil',
        'label' => 'LBL_CLOSE_TASK',
        'event' => 'list:close_task:fire',
      ),
    ),
  ),
  'type' => 'subpanel-list',
);