<?php
$listViewDefs['Documents'] = 
array (
  'document_name' => 
  array (
    'width' => '20%',
    'label' => 'LBL_DOCUMENT_NAME',
    'link' => true,
    'default' => true,
    'bold' => true,
  ),
  'filename' => 
  array (
    'width' => '20%',
    'label' => 'LBL_FILENAME',
    'link' => true,
    'default' => true,
    'bold' => false,
    'displayParams' => 
    array (
      'module' => 'Documents',
    ),
    'sortable' => false,
    'related_fields' => 
    array (
      0 => 'document_revision_id',
      1 => 'doc_id',
      2 => 'doc_type',
      3 => 'doc_url',
    ),
  ),
  'doc_type' => 
  array (
    'width' => '5%',
    'label' => 'LBL_DOC_TYPE',
    'link' => false,
    'default' => true,
  ),
  'category_id' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_CATEGORY',
    'default' => true,
  ),
  'subcategory_id' => 
  array (
    'width' => '15%',
    'label' => 'LBL_LIST_SUBCATEGORY',
    'default' => true,
  ),
  'last_rev_create_date' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_LAST_REV_DATE',
    'default' => true,
    'sortable' => false,
    'module' => 'DocumentRevisions',
    'related_fields' => 
    array (
      0 => 'latest_revision_id',
    ),
  ),
  'exp_date' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_EXP_DATE',
    'default' => true,
  ),
  'assigned_user_name' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_ASSIGNED_USER',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
  'converted' => 
  array (
    'type' => 'int',
    'default' => true,
    'label' => 'LBL_CONVERTED',
    'width' => '10%',
  ),
  'date_entered' => 
  array (
    'width' => '10%',
    'label' => 'LBL_DATE_ENTERED',
    'default' => true,
  ),
  'team_name' => 
  array (
    'width' => '2%',
    'label' => 'LBL_LIST_TEAM',
    'default' => false,
    'sortable' => false,
  ),
  'modified_by_name' => 
  array (
    'width' => '10%',
    'label' => 'LBL_MODIFIED_USER',
    'module' => 'Users',
    'id' => 'USERS_ID',
    'default' => false,
    'sortable' => false,
    'related_fields' => 
    array (
      0 => 'modified_user_id',
    ),
  ),
);
