<?php
$viewdefs['Opportunities'] = 
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
                'name' => 'name',
                'link' => true,
                'label' => 'LBL_LIST_OPPORTUNITY_NAME',
                'enabled' => true,
                'default' => true,
                'related_fields' => 
                array (
                  0 => 'total_revenue_line_items',
                  1 => 'closed_revenue_line_items',
                ),
              ),
              1 => 
              array (
                'name' => 'leads_opportunities_1_name',
                'label' => 'LBL_LEADS_OPPORTUNITIES_1_FROM_LEADS_TITLE',
                'enabled' => true,
                'link' => true,
                'sortable' => false,
                'default' => true,
              ),
              2 => 
              array (
                'name' => 'provider_id_c',
                'label' => 'LBL_PROVIDER_ID',
                'enabled' => true,
                'default' => true,
              ),
              3 => 
              array (
                'name' => 'provider_status_id_c',
                'label' => 'LBL_PROVIDER_STATUS_ID',
                'enabled' => true,
                'default' => true,
              ),
              4 => 
              array (
                'name' => 'expiry_date_c',
                'label' => 'LBL_EXPIRY_DATE',
                'enabled' => true,
                'default' => true,
              ),
              5 => 
              array (
                'name' => 'provider_contract_no',
                'label' => 'LBL_PROVIDER_CONTRACT_NUMBER',
                'enabled' => true,
                'default' => true,
              ),
              6 => 
              array (
                'name' => 'dotb_user_approval_c',
                'label' => 'LBL_USER_APPROVAL',
                'enabled' => true,
                'id' => 'USER_ID_C',
                'link' => true,
                'sortable' => false,
                'default' => true,
              ),
              7 => 
              array (
                'name' => 'date_modified',
                'label' => 'LBL_DATE_MODIFIED',
                'enabled' => true,
                'readonly' => true,
                'default' => true,
              ),
              8 => 
              array (
                'name' => 'team_name',
                'type' => 'teamset',
                'label' => 'LBL_LIST_TEAM',
                'enabled' => true,
                'default' => false,
              ),
              9 => 
              array (
                'name' => 'date_entered',
                'label' => 'LBL_DATE_ENTERED',
                'enabled' => true,
                'default' => false,
                'readonly' => true,
              ),
              10 => 
              array (
                'name' => 'account_name',
                'link' => true,
                'label' => 'LBL_LIST_ACCOUNT_NAME',
                'enabled' => true,
                'default' => false,
                'sortable' => true,
              ),
              11 => 
              array (
                'name' => 'sales_stage',
                'label' => 'LBL_LIST_SALES_STAGE',
                'enabled' => true,
                'default' => false,
              ),
              12 => 
              array (
                'name' => 'provider_application_no_c',
                'label' => 'LBL_PROVIDER_APPLICATION_NO',
                'enabled' => true,
                'default' => false,
              ),
              13 => 
              array (
                'name' => 'amount',
                'type' => 'currency',
                'label' => 'LBL_LIKELY',
                'related_fields' => 
                array (
                  0 => 'amount',
                  1 => 'currency_id',
                  2 => 'base_rate',
                ),
                'currency_field' => 'currency_id',
                'base_rate_field' => 'base_rate',
                'enabled' => true,
                'default' => false,
              ),
              14 => 
              array (
                'name' => 'date_closed',
                'label' => 'LBL_DATE_CLOSED',
                'enabled' => true,
                'default' => false,
              ),
              15 => 
              array (
                'name' => 'assigned_user_name',
                'label' => 'LBL_LIST_ASSIGNED_USER',
                'id' => 'ASSIGNED_USER_ID',
                'enabled' => true,
                'default' => false,
                'sortable' => true,
              ),
              16 => 
              array (
                'name' => 'probability',
                'label' => 'LBL_PROBABILITY',
                'enabled' => true,
                'default' => false,
              ),
              17 => 
              array (
                'name' => 'lead_source',
                'label' => 'LBL_LEAD_SOURCE',
                'enabled' => true,
                'default' => false,
              ),
              18 => 
              array (
                'name' => 'next_step',
                'label' => 'LBL_NEXT_STEP',
                'enabled' => true,
                'default' => false,
              ),
              19 => 
              array (
                'name' => 'opportunity_type',
                'label' => 'LBL_TYPE',
                'enabled' => true,
                'default' => false,
              ),
              20 => 
              array (
                'name' => 'modified_by_name',
                'label' => 'LBL_MODIFIED',
                'enabled' => true,
                'default' => false,
                'readonly' => true,
                'sortable' => true,
              ),
              21 => 
              array (
                'name' => 'created_by_name',
                'label' => 'LBL_CREATED',
                'enabled' => true,
                'default' => false,
                'readonly' => true,
                'sortable' => true,
              ),
            ),
          ),
        ),
      ),
    ),
  ),
);
