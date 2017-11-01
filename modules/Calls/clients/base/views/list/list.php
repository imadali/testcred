<?php
$viewdefs['Calls'] = 
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
                'label' => 'LBL_LIST_SUBJECT',
                'enabled' => true,
                'default' => true,
                'link' => true,
                'name' => 'name',
                'related_fields' => 
                array (
                  0 => 'repeat_type',
                ),
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
                'sortable' => false,
                'ACLTag' => 'PARENT',
                'related_fields' => 
                array (
                  0 => 'parent_id',
                  1 => 'parent_type',
                ),
              ),
              2 => 
              array (
                'name' => 'date_start',
                'label' => 'LBL_LIST_DATE',
                'type' => 'datetimecombo-colorcoded',
                'css_class' => 'overflow-visible',
                'completed_status_value' => 'Held',
                'enabled' => true,
                'default' => true,
                'readonly' => true,
                'related_fields' => 
                array (
                  0 => 'status',
                ),
              ),
              3 => 
              array (
                'enabled' => true,
                'default' => true,
                'name' => 'status',
                'type' => 'event-status',
                'css_class' => 'full-width',
              ),
              4 => 
              array (
                'enabled' => true,
                'default' => true,
                'name' => 'direction',
              ),
              5 => 
              array (
                'name' => 'lead_amount_c',
                'label' => 'LBL_LEAD_AMOUNT',
                'enabled' => true,
                'sortable' => true,
                'default' => true,
              ),
              6 => 
              array (
                'name' => 'application_provider_c',
                'label' => 'LBL_APPLICATION_PROVIDER',
                'enabled' => true,
                'default' => true,
              ),
              7 => 
              array (
                'name' => 'application_user_approval_c',
                'label' => 'LBL_USER_APPROVAL',
                'enabled' => true,
                'id' => 'USER_ID_C',
                'link' => true,
                'sortable' => false,
                'default' => true,
              ),
              8 => 
              array (
                'name' => 'lead_status_c',
                'label' => 'LBL_LEAD_STATUS',
                'enabled' => true,
                'default' => true,
              ),
              9 => 
              array (
                'name' => 'assigned_user_name',
                'target_record_key' => 'assigned_user_id',
                'target_module' => 'Employees',
                'label' => 'LBL_LIST_ASSIGNED_USER',
                'enabled' => true,
                'default' => false,
                'sortable' => true,
              ),
              10 => 
              array (
                'name' => 'date_entered',
                'enabled' => true,
                'default' => false,
                'readonly' => true,
              ),
              11 => 
              array (
                'name' => 'date_end',
                'link' => false,
                'default' => false,
                'enabled' => true,
              ),
            ),
          ),
        ),
      ),
    ),
  ),
);
