<?php
$popupMeta = array (
    'moduleMain' => 'Contact',
    'varName' => 'CONTACT',
    'orderBy' => 'contacts.first_name, contacts.last_name',
    'whereClauses' => array (
  'first_name' => 'contacts.first_name',
  'last_name' => 'contacts.last_name',
  'account_name' => 'accounts.name',
  'account_id' => 'accounts.id',
),
    'searchInputs' => array (
  0 => 'first_name',
  1 => 'last_name',
  2 => 'account_name',
  3 => 'email',
),
    'create' => array (
  'formBase' => 'ContactFormBase.php',
  'formBaseClass' => 'ContactFormBase',
  'getFormBodyParams' => 
  array (
    0 => '',
    1 => '',
    2 => 'ContactSave',
  ),
  'createButton' => 'LNK_NEW_CONTACT',
),
    'searchdefs' => array (
  0 => 'first_name',
  1 => 'last_name',
  2 => 
  array (
    'name' => 'account_name',
    'type' => 'varchar',
  ),
  3 => 'title',
  4 => 'lead_source',
  5 => 'email',
  6 => 
  array (
    'name' => 'campaign_name',
    'displayParams' => 
    array (
      'hideButtons' => 'true',
      'size' => 30,
      'class' => 'sqsEnabled sqsNoAutofill',
    ),
  ),
  7 => 
  array (
    'name' => 'assigned_user_id',
    'type' => 'enum',
    'label' => 'LBL_ASSIGNED_TO',
    'function' => 
    array (
      'name' => 'get_user_array',
      'params' => 
      array (
        0 => false,
      ),
    ),
  ),
),
    'listviewdefs' => array (
  'TITLE' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_TITLE',
    'default' => true,
    'name' => 'title',
  ),
  'FULL_NAME' => 
  array (
    'type' => 'fullname',
    'link' => true,
    'studio' => 
    array (
      'listview' => false,
    ),
    'label' => 'LBL_NAME',
    'width' => '10%',
    'default' => true,
  ),
  'EMAIL' => 
  array (
    'type' => 'email',
    'studio' => 
    array (
      'visible' => true,
      'searchview' => true,
      'editview' => true,
      'editField' => true,
    ),
    'link' => 'email_addresses_primary',
    'label' => 'LBL_ANY_EMAIL',
    'sortable' => false,
    'width' => '10%',
    'default' => true,
  ),
  'PREFERRED_LANGUAGE' => 
  array (
    'type' => 'enum',
    'label' => 'LBL_PREFERRED_LANGUAGE',
    'width' => '10%',
    'default' => true,
  ),
  'PHONE_MOBILE' => 
  array (
    'type' => 'phone',
    'label' => 'LBL_MOBILE_PHONE',
    'width' => '10%',
    'default' => true,
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'link' => true,
    'type' => 'relate',
    'label' => 'LBL_ASSIGNED_TO',
    'id' => 'ASSIGNED_USER_ID',
    'width' => '10%',
    'default' => true,
  ),
),
);
