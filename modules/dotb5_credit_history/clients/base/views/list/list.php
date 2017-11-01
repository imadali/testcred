<?php
$module_name = 'dotb5_credit_history';
$viewdefs[$module_name] = 
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
                'label' => 'LBL_NAME',
                'default' => true,
                'enabled' => true,
                'link' => true,
              ),
              1 => 
              array (
                'name' => 'credit_type_id',
                'label' => 'LBL_CREDIT_TYPE_ID',
                'enabled' => true,
                'default' => true,
              ),
              2 => 
              array (
                'name' => 'dotb5_credit_history_contacts_name',
                'label' => 'LBL_DOTB5_CREDIT_HISTORY_CONTACTS_FROM_CONTACTS_TITLE',
                'enabled' => true,
                'id' => 'DOTB5_CREDIT_HISTORY_CONTACTSCONTACTS_IDA',
                'link' => true,
                'sortable' => false,
                'default' => true,
              ),
              3 => 
              array (
                'name' => 'assigned_user_name',
                'label' => 'LBL_ASSIGNED_TO_NAME',
                'default' => true,
                'enabled' => true,
                'link' => true,
              ),
              4 => 
              array (
                'name' => 'date_entered',
                'enabled' => true,
                'default' => true,
              ),
              5 => 
              array (
                'name' => 'created_by_name',
                'label' => 'LBL_CREATED',
                'enabled' => true,
                'readonly' => true,
                'id' => 'CREATED_BY',
                'link' => true,
                'default' => false,
              ),
              6 => 
              array (
                'name' => 'monthly_credit_rate',
                'label' => 'LBL_MONTHLY_CREDIT_RATE',
                'enabled' => true,
                'default' => false,
              ),
              7 => 
              array (
                'name' => 'release_credit',
                'label' => 'LBL_RELEASE_CREDIT',
                'enabled' => true,
                'default' => false,
              ),
              8 => 
              array (
                'name' => 'credit_balance',
                'label' => 'LBL_CREDIT_BALANCE',
                'enabled' => true,
                'default' => false,
              ),
              9 => 
              array (
                'name' => 'credit_end_date',
                'label' => 'LBL_CREDIT_END_DATE',
                'enabled' => true,
                'default' => false,
              ),
              10 => 
              array (
                'name' => 'desired_credit_increase',
                'label' => 'LBL_DESIRED_CREDIT_INCREASE',
                'enabled' => true,
                'default' => false,
              ),
              11 => 
              array (
                'name' => 'credit_provider',
                'label' => 'LBL_CREDIT_PROVIDER',
                'enabled' => true,
                'default' => false,
              ),
              12 => 
              array (
                'name' => 'team_name',
                'label' => 'LBL_TEAM',
                'default' => false,
                'enabled' => true,
              ),
              13 => 
              array (
                'name' => 'modified_by_name',
                'label' => 'LBL_MODIFIED',
                'enabled' => true,
                'readonly' => true,
                'id' => 'MODIFIED_USER_ID',
                'link' => true,
                'default' => false,
              ),
              14 => 
              array (
                'name' => 'date_modified',
                'enabled' => true,
                'default' => false,
              ),
            ),
          ),
        ),
        'orderBy' => 
        array (
          'field' => 'date_modified',
          'direction' => 'desc',
        ),
      ),
    ),
  ),
);
