<?php
// created: 2015-08-13 15:08:14
$viewdefs['Tasks']['base']['view']['subpanel-for-contacts-all_tasks'] = array (
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
          'label' => 'LBL_LIST_SUBJECT',
          'enabled' => true,
          'default' => true,
          'link' => true,
          'name' => 'name',
        ),
        1 => 
        array (
          'label' => 'LBL_LIST_STATUS',
          'enabled' => true,
          'default' => true,
          'name' => 'status',
        ),
        2 => 
        array (
          'name' => 'description',
          'label' => 'LBL_DESCRIPTION',
          'enabled' => true,
          'sortable' => false,
          'default' => true,
        ),
        3 => 
        array (
          'target_record_key' => 'contact_id',
          'target_module' => 'Contacts',
          'label' => 'LBL_LIST_CONTACT',
          'enabled' => true,
          'default' => true,
          'name' => 'contact_name',
        ),
        4 => 
        array (
          'name' => 'date_start',
          'label' => 'LBL_LIST_START_DATE',
          'enabled' => true,
          'default' => true,
        ),
        5 => 
        array (
          'name' => 'date_due',
          'label' => 'LBL_LIST_DUE_DATE',
          'enabled' => true,
          'default' => true,
        ),
        6 => 
        array (
          'name' => 'assigned_user_name',
          'label' => 'LBL_LIST_ASSIGNED_TO_NAME',
          'id' => 'ASSIGNED_USER_ID',
          'enabled' => true,
          'default' => true,
        ),
      ),
    ),
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
        'event' => 'list:preview:fire',
        'icon' => 'fa-eye',
        'acl_action' => 'view',
        'allow_bwc' => false,
      ),
      1 => 
      array (
        'type' => 'rowaction',
        'name' => 'edit_button',
        'icon' => 'fa-pencil',
        'label' => 'LBL_EDIT_BUTTON',
        'event' => 'list:editrow:fire',
        'acl_action' => 'edit',
        'allow_bwc' => false,
      ),
      2 => 
      array (
        'type' => 'unlink-action',
        'icon' => 'fa-trash-o',
        'label' => 'LBL_UNLINK_BUTTON',
      ),
      3 => 
      array (
        'type' => 'closebutton',
        'name' => 'record-close',
        'label' => 'LBL_CLOSE_BUTTON_TITLE',
        'closed_status' => 'closed', //CRED-981 : Status closed
        'acl_action' => 'edit',
      ),
    ),
  ),
  'type' => 'subpanel-list',
);