<?php
$viewdefs['dotb5_credit_history'] = 
array (
  'base' => 
  array (
    'view' => 
    array (
      'record' => 
      array (
        'panels' => 
        array (
          0 => 
          array (
            'name' => 'panel_header',
            'label' => 'LBL_RECORD_HEADER',
            'header' => true,
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'picture',
                'type' => 'avatar',
                'width' => 42,
                'height' => 42,
                'dismiss_label' => true,
                'readonly' => true,
              ),
              1 => 'name',
              2 => 
              array (
                'name' => 'favorite',
                'label' => 'LBL_FAVORITE',
                'type' => 'favorite',
                'readonly' => true,
                'dismiss_label' => true,
              ),
              3 => 
              array (
                'name' => 'follow',
                'label' => 'LBL_FOLLOW',
                'type' => 'follow',
                'readonly' => true,
                'dismiss_label' => true,
              ),
            ),
          ),
          1 => 
          array (
            'name' => 'panel_body',
            'label' => 'LBL_RECORD_BODY',
            'columns' => 2,
            'labelsOnTop' => true,
            'placeholders' => true,
            'newTab' => false,
            'panelDefault' => 'expanded',
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'credit_type_id',
                'label' => 'LBL_CREDIT_TYPE_ID',
              ),
              1 => 
              array (
                'name' => 'credit_provider',
                'label' => 'LBL_CREDIT_PROVIDER',
              ),
              2 => 
              array (
                'name' => 'credit_balance',
                'label' => 'LBL_CREDIT_BALANCE',
              ),
              3 => 
              array (
                'name' => 'desired_credit_increase',
                'label' => 'LBL_DESIRED_CREDIT_INCREASE',
              ),
              4 => 
              array (
                'name' => 'monthly_credit_rate',
                'label' => 'LBL_MONTHLY_CREDIT_RATE',
              ),
              5 => 
              array (
                'name' => 'release_credit',
                'label' => 'LBL_RELEASE_CREDIT',
              ),
              6 => 
              array (
                'name' => 'credit_end_date',
                'label' => 'LBL_CREDIT_END_DATE',
              ),
              7 => 
              array (
                'name' => 'contract_name',
                'readonly' => true,
                'comment' => 'The name of the contract',
                'label' => 'LBL_CONTRACT_NAME',
              ),
              8 => 
              array (
                'name' => 'date_entered_by',
                'readonly' => true,
                'inline' => true,
                'type' => 'fieldset',
                'label' => 'LBL_DATE_ENTERED',
                'fields' => 
                array (
                  0 => 
                  array (
                    'name' => 'date_entered',
                  ),
                  1 => 
                  array (
                    'type' => 'label',
                    'default_value' => 'LBL_BY',
                  ),
                  2 => 
                  array (
                    'name' => 'created_by_name',
                  ),
                ),
              ),
              9 => 
              array (
                'name' => 'date_modified_by',
                'readonly' => true,
                'inline' => true,
                'type' => 'fieldset',
                'label' => 'LBL_DATE_MODIFIED',
                'fields' => 
                array (
                  0 => 
                  array (
                    'name' => 'date_modified',
                  ),
                  1 => 
                  array (
                    'type' => 'label',
                    'default_value' => 'LBL_BY',
                  ),
                  2 => 
                  array (
                    'name' => 'modified_by_name',
                  ),
                ),
              ),
              10 => 
              array (
                'name' => 'leads_dotb5_credit_history_1_name',
              ),
              11 => 
              array (
              ),
            ),
          ),
        ),
        'templateMeta' => 
        array (
          'useTabs' => false,
        ),
      ),
    ),
  ),
);
