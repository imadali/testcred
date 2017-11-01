<?php
// created: 2016-03-16 06:33:20
$viewdefs['Contacts']['base']['view']['subpanel-for-contacts-contacts_contacts_3'] = array (
  'type' => 'subpanel-list',
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
          'link' => true,
          'css_class' => 'full-name',
          'label' => 'LBL_LIST_NAME',
          'enabled' => true,
          'default' => true,
        ),
        1 => 
        array (
          'name' => 'relative_type_c',
          'label' => 'LBL_RELATIVE_TYPE',
          'enabled' => true,
          'default' => true,
        ),
        2 => 
        array (
          'name' => 'birthdate',
          'label' => 'LBL_BIRTHDATE',
          'enabled' => true,
          'default' => true,
        ),
        3 => 
        array (
          'name' => 'no_of_dependent_children_c',
          'label' => 'LBL_NO_OF_DEPENDENT_CHILDREN',
          'enabled' => true,
          'default' => true,
        ),
        4 => 
        array (
          'name' => 'email',
          'label' => 'LBL_LIST_EMAIL',
          'enabled' => true,
          'default' => true,
        ),
        5 => 
        array (
          'name' => 'date_modified',
          'label' => 'LBL_DATE_MODIFIED',
          'enabled' => true,
          'readonly' => true,
          'default' => true,
        ),
      ),
    ),
  ),
);