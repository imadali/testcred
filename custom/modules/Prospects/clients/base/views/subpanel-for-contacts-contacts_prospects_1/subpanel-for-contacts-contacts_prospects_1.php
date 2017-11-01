<?php
// created: 2017-05-09 21:29:41
$viewdefs['Prospects']['base']['view']['subpanel-for-contacts-contacts_prospects_1'] = array (
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
          'name' => 'full_name',
          'type' => 'fullname',
          'fields' => 
          array (
            0 => 'salutation',
            1 => 'first_name',
            2 => 'last_name',
          ),
          'label' => 'LBL_LIST_NAME_TL',
          'enabled' => true,
          'sortable' => false,
          'default' => true,
        ),
        1 => 
        array (
          'name' => 'description',
          'label' => 'LBL_DESCRIPTION',
          'enabled' => true,
          'sortable' => false,
          'default' => true,
        ),
        2 => 
        array (
          'name' => 'title',
          'label' => 'LBL_TITLE_TL',
          'enabled' => true,
          'default' => true,
          'sortable' => false,
        ),
        3 => 
        array (
          'name' => 'phone_fax',
          'label' => 'LBL_FAX_PHONE_TL',
          'enabled' => true,
          'default' => true,
          'sortable' => false,
        ),
        4 => 
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