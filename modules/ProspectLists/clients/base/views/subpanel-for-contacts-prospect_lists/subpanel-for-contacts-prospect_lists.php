<?php
// created: 2017-07-04 17:53:54
$viewdefs['ProspectLists']['base']['view']['subpanel-for-contacts-prospect_lists'] = array (
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
          'label' => 'LBL_LIST_PROSPECT_LIST_NAME',
          'enabled' => true,
          'default' => true,
          'name' => 'name',
          'link' => true,
        ),
        1 => 
        array (
          'label' => 'LBL_LIST_DESCRIPTION',
          'enabled' => true,
          'default' => true,
          'name' => 'description',
        ),
        2 => 
        array (
          'label' => 'LBL_LIST_TYPE_NO',
          'enabled' => true,
          'default' => true,
          'name' => 'list_type',
        ),
        3 => 
        array (
          'name' => 'assigned_user_name',
          'label' => 'LBL_ASSIGNED_TO',
          'enabled' => true,
          'id' => 'ASSIGNED_USER_ID',
          'link' => true,
          'default' => true,
        ),
      ),
    ),
  ),
  'type' => 'subpanel-list',
);