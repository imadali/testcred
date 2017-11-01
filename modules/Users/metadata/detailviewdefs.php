<?php
// created: 2017-07-10 11:50:42
$viewdefs['Users']['DetailView'] = array (
  'templateMeta' => 
  array (
    'maxColumns' => '2',
    'widths' => 
    array (
      0 => 
      array (
        'label' => '10',
        'field' => '30',
      ),
      1 => 
      array (
        'label' => '10',
        'field' => '30',
      ),
    ),
    'form' => 
    array (
      'headerTpl' => 'modules/Users/tpls/DetailViewHeader.tpl',
      'footerTpl' => 'modules/Users/tpls/DetailViewFooter.tpl',
    ),
    'useTabs' => false,
    'tabDefs' => 
    array (
      'LBL_USER_INFORMATION' => 
      array (
        'newTab' => false,
        'panelDefault' => 'expanded',
      ),
      'LBL_EMPLOYEE_INFORMATION' => 
      array (
        'newTab' => false,
        'panelDefault' => 'expanded',
      ),
    ),
  ),
  'panels' => 
  array (
    'LBL_USER_INFORMATION' => 
    array (
      0 => 
      array (
        0 => 'full_name',
        1 => 'user_name',
      ),
      1 => 
      array (
        0 => 'status',
        1 => 
        array (
          'name' => 'UserType',
          'customCode' => '{$USER_TYPE_READONLY}',
        ),
      ),
      2 => 
      array (
        0 => 'picture',
      ),
      3 => 
      array (
        0 => 
        array (
          'name' => 'check_employee_timings_c',
          'label' => 'LBL_CHECK_EMPLOYEE_TIMINGS',
        ),
        1 => '',
      ),
      4 => 
      array (
        0 => 
        array (
          'name' => 'employee_start_time_c',
          'label' => 'LBL_EMPLOYEE_START_TIME',
        ),
        1 => 
        array (
          'name' => 'employee_end_time_c',
          'label' => 'LBL_EMPLOYEE_END_TIME',
        ),
      ),
      5 => 
      array (
        0 => 
        array (
          'name' => 'notification_email',
          'label' => 'LBL_NOTIFICATION_EMAIL',
        ),
        1 => '',
      ),
    ),
    'LBL_EMPLOYEE_INFORMATION' => 
    array (
      0 => 
      array (
        0 => 'employee_status',
        1 => 'show_on_employees',
      ),
      1 => 
      array (
        0 => 'title',
        1 => 'phone_work',
      ),
      2 => 
      array (
        0 => 'department',
        1 => 'phone_mobile',
      ),
      3 => 
      array (
        0 => 'reports_to_name',
        1 => 'phone_other',
      ),
      4 => 
      array (
        0 => '',
        1 => 'phone_fax',
      ),
      5 => 
      array (
        0 => '',
        1 => 'phone_home',
      ),
      6 => 
      array (
        0 => 'messenger_type',
        1 => 'messenger_id',
      ),
      7 => 
      array (
        0 => 'address_street',
        1 => 'address_city',
      ),
      8 => 
      array (
        0 => 'address_state',
        1 => 'address_postalcode',
      ),
      9 => 
      array (
        0 => 'address_country',
      ),
      10 => 
      array (
        0 => 'description',
      ),
      11 => 
      array (
        0 => 
        array (
          'name' => 'dotb_is_active',
          'studio' => 'visible',
          'label' => 'LBL_DOTB_IS_ACTIVE',
        ),
        1 => 
        array(
            'name' => 'input_process_type',
            'label' => 'LBL_INPUT_PROCESS_TYPE',
        ),
      ),
      12 => 
      array (
        0 => 'dotb_working_days',
        1 => 'dotb_spoken_languages',
      ),
      13 => 
      array (
        0 => 'application_assignment',
        1 => 'application_provider',
      ),
    ),
  ),
);