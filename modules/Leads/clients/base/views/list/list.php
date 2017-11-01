<?php
$viewdefs['Leads'] = 
array (
  'base' => 
  array (
    'view' => 
    array (
      'list' => 
      array (
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
                'name' => 'date_entered',
                'label' => 'LBL_DATE_ENTERED',
                'enabled' => true,
                'default' => true,
              ),
              1 => 
              array (
                'name' => 'first_name',
                'label' => 'LBL_FIRST_NAME',
                'enabled' => true,
                'default' => true,
              ),
              2 => 
              array (
                'name' => 'last_name',
                'label' => 'LBL_LAST_NAME',
                'enabled' => true,
                'default' => true,
              ),  
              3 => 
              array (
                'name' => 'cstm_last_name_c',
                'label' => 'LBL_CSTM_LAST_NAME_C',
                'enabled' => true,
                'default' => true,
              ),
              4 => 
              array (
                'name' => 'credit_request_status_id_c',
                'label' => 'LBL_CREDIT_REQUEST_STATUS_ID',
                'enabled' => true,
                'default' => true,
              ),
              5 => 
              array (
                'name' => 'email',
                'label' => 'LBL_LIST_EMAIL_ADDRESS',
                'enabled' => true,
                'default' => true,
              ),
              6 => 
              array (
                'name' => 'account_name',
                'label' => 'LBL_LIST_ACCOUNT_NAME',
                'enabled' => true,
                'default' => true,
                'related_fields' => 
                array (
                  0 => 'account_id',
                  1 => 'converted',
                ),
              ),
              7 => 
              array (
                'name' => 'phone_work',
                'label' => 'LBL_LIST_PHONE',
                'enabled' => true,
                'default' => true,
              ),
              8 => 
              array (
                'name' => 'input_process_type_id_c',
                'label' => 'LBL_INPUT_PROCESS_TYPE_ID',
                'enabled' => true,
                'default' => true,
              ),
              9 => 
              array (
                'name' => 'assigned_user_name',
                'label' => 'LBL_LIST_ASSIGNED_USER',
                'enabled' => true,
                'default' => true,
              ),
              10 => 
              array (
                'name' => 'date_modified',
                'enabled' => true,
                'default' => true,
              ),
              11 => 
              array (
                'name' => 'dotb_correspondence_language_c',
                'enabled' => true,
                'default' => true,
              ),
            ),
          ),
        ),
      ),
    ),
  ),
);
