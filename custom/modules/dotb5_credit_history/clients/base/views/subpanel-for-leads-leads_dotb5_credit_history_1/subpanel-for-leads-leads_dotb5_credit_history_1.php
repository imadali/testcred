<?php
$viewdefs['dotb5_credit_history']['base']['view']['subpanel-for-leads-leads_dotb5_credit_history_1'] = array (
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
          'label' => 'LBL_NAME',
          'enabled' => true,
          'default' => true,
          'name' => 'name',
          'link' => true,
        ),
	1 =>
	array (
          'name' => 'credit_provider',
          'label' => 'LBL_CREDIT_PROVIDER',
          'enabled' => true,
          'default' => true,
        ),
	2 =>
	array (
          'name' => 'credit_balance',
          'label' => 'LBL_CREDIT_BALANCE',
          'enabled' => true,
          'default' => true,
        ),
	3 =>
	array (
          'name' => 'monthly_credit_rate',
          'label' => 'LBL_MONTHLY_CREDIT_RATE',
          'enabled' => true,
          'default' => true,
        ),
	4 => 
        array (
          'name' => 'release_credit',
          'label' => 'LBL_RELEASE_CREDIT',
          'enabled' => true,
          'default' => true,
        ),
	5 =>
	array (
          'name' => 'credit_end_date',
          'label' => 'LBL_CREDIT_END_DATE',
          'enabled' => true,
          'default' => true,
        ),
	6 =>
	array (
          'name' => 'date_entered',
          'label' => 'LBL_DATE_ENTERED',
          'enabled' => true,
          'readonly' => true,
          'default' => true,
        ),
      ),
    ),
  ),
    
  'orderBy' =>
  array (
    'field' => 'date_modified',
    'direction' => 'desc',
  ),
  'type' => 'subpanel-list',
);