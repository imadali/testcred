<?php
$viewdefs['Tasks'] = 
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
            'label' => 'LBL_PANEL_1',
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'name',
                'link' => true,
                'label' => 'LBL_LIST_SUBJECT',
                'enabled' => true,
                'default' => true,
              ),
              1 => 
              array (
                'name' => 'parent_name',
                'label' => 'LBL_LIST_RELATED_TO',
                'dynamic_module' => 'PARENT_TYPE',
                'id' => 'PARENT_ID',
                'link' => true,
                'enabled' => true,
                'default' => true,
                'sortable' => true,
                'ACLTag' => 'PARENT',
                'related_fields' => 
                array (
                  0 => 'parent_id',
                  1 => 'parent_type',
                ),
              ),
              2 => 
              array (
                'name' => 'date_due',
                'label' => 'LBL_LIST_DUE_DATE',
                'type' => 'datetimecombo-colorcoded',
                'completed_status_value' => 'Completed',
                'link' => false,
                'enabled' => true,
                'default' => true,
              ),
              3 => 
              array (
                'name' => 'assigned_user_name',
                'label' => 'LBL_LIST_ASSIGNED_TO_NAME',
                'id' => 'ASSIGNED_USER_ID',
                'enabled' => true,
                'default' => true,
              ),
              4 => 
              array (
                'name' => 'date_modified',
                'enabled' => true,
                'default' => true,
              ),
              5 => 
              array (
                'name' => 'date_entered',
                'label' => 'LBL_DATE_ENTERED',
                'enabled' => true,
                'default' => true,
                'readonly' => true,
              ),
              6 => 
              array (
                'name' => 'description',
                'label' => 'LBL_DESCRIPTION',
                'enabled' => true,
                'sortable' => false,
                'default' => true,
              ),
              7 => 
              array (
                'name' => 'input_process_type_id',
                'label' => 'LBL_INPUT_PROCESS_TYPE_ID',
                'enabled' => true,
                'sortable' => false,
                'default' => true,
              ),
              8 => 
              array (
                'name' => 'lead_amount_c',
                'label' => 'LBL_LEAD_AMOUNT',
                'enabled' => true,
                'sortable' => true,
                'default' => true,
              ),
              9 => 
              array (
                'name' => 'application_provider_c',
                'label' => 'LBL_APPLICATION_PROVIDER',
                'enabled' => true,
                'default' => true,
              ),
              10 => 
              array (
                'name' => 'application_user_approval_c',
                'label' => 'LBL_USER_APPROVAL',
                'enabled' => true,
                'id' => 'USER_ID_C',
                'link' => true,
                'sortable' => false,
                'default' => true,
              ),
              11 => 
              array (
                'name' => 'lead_status_c',
                'label' => 'LBL_LEAD_STATUS',
                'enabled' => true,
                'default' => true,
              ),
              12 => 
              array (
                'name' => 'customer_contact_name',
                'label' => 'LBL_CUSTOMER_CONTACT_NAME',
                'enabled' => true,
                'id' => 'CUSTOMER_CONTACT_ID',
                'link' => true,
                'sortable' => false,
                'default' => true,
              ),
              13 => 
              array (
                'name' => 'email_c',
                'label' => 'LBL_EMAIL',
                'enabled' => true,
                'default' => false,
              ),
              14 => 
              array (
                'name' => 'birthdate_c',
                'label' => 'LBL_BIRTHDATE',
                'enabled' => true,
                'default' => false,
              ),
              15 => 
              array (
                'name' => 'lead_date_entered_c',
                'label' => 'LBL_LEAD_DATE_ENTERED',
                'enabled' => true,
                'default' => false,
              ),
              16 => 
              array (
                'name' => 'phone_work_c',
                'label' => 'LBL_PHONE_WORK',
                'enabled' => true,
                'default' => false,
              ),
              17 => 
              array (
                'name' => 'dotb_correspondence_language_c',
                'label' => 'LBL_DOTB_CORRESPONDENCE_LANGUAGE',
                'enabled' => true,
                'default' => false,
              ),
              18 => 
              array (
                'name' => 'team_name',
                'label' => 'LBL_LIST_TEAM',
                'enabled' => true,
                'default' => false,
              ),
              19 => 
              array (
                'name' => 'surname_c',
                'label' => 'LBL_SURNAME',
                'enabled' => true,
                'default' => false,
              ),
              20 => 
              array (
                'name' => 'status',
                'label' => 'LBL_LIST_STATUS',
                'link' => false,
                'enabled' => true,
                'default' => false,
              ),
              21 => 
              array (
                'name' => 'provider_application_no_c',
                'label' => 'LBL_PROVIDER_APPLICATION_NO',
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
