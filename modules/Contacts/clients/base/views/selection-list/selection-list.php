<?php
$viewdefs['Contacts'] = 
array (
  'base' => 
  array (
    'view' => 
    array (
      'selection-list' => 
      array (
        'panels' => 
        array (
          0 => 
          array (
            'label' => 'LBL_PANEL_1',
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'title',
                'enabled' => true,
                'default' => true,
              ),
              1 => 
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
              2 => 
              array (
                'name' => 'email',
                'enabled' => true,
                'default' => true,
              ),
              3 => 
              array (
                'name' => 'preferred_language',
                'label' => 'LBL_PREFERRED_LANGUAGE',
                'enabled' => true,
                'default' => true,
              ),
              4 => 
              array (
                'name' => 'phone_mobile',
                'label' => 'LBL_MOBILE_PHONE',
                'enabled' => true,
                'default' => true,
              ),
              5 => 
              array (
                'name' => 'assigned_user_name',
                'label' => 'LBL_LIST_ASSIGNED_USER',
                'id' => 'ASSIGNED_USER_ID',
                'enabled' => true,
                'default' => true,
              ),
              6 => 
              array (
                'name' => 'phone_work',
                'enabled' => true,
                'default' => false,
              ),
              7 => 
              array (
                'name' => 'date_entered',
                'enabled' => true,
                'default' => false,
                'readonly' => true,
              ),
              8 => 
              array (
                'name' => 'name',
                'label' => 'LBL_NAME',
                'enabled' => true,
                'default' => false,
              ),
              9 => 
              array (
                'name' => 'phone_home',
                'label' => 'LBL_HOME_PHONE',
                'enabled' => true,
                'default' => false,
              ),
              10 => 
              array (
                'name' => 'department',
                'label' => 'LBL_DEPARTMENT',
                'enabled' => true,
                'default' => false,
              ),
              11 => 
              array (
                'name' => 'phone_other',
                'label' => 'LBL_OTHER_PHONE',
                'enabled' => true,
                'default' => false,
              ),
              12 => 
              array (
                'name' => 'account_name',
                'enabled' => true,
                'default' => false,
              ),
            ),
          ),
        ),
      ),
    ),
  ),
);
