<?php
// created: 2017-07-10 11:50:42
$searchdefs['Documents'] = array (
  'templateMeta' => 
  array (
    'maxColumns' => '3',
    'maxColumnsBasic' => '4',
    'widths' => 
    array (
      'label' => '10',
      'field' => '30',
    ),
  ),
  'layout' => 
  array (
    'basic_search' => 
    array (
      0 => 'document_name',
      1 => 
      array (
        'name' => 'favorites_only',
        'label' => 'LBL_FAVORITES_FILTER',
        'type' => 'bool',
      ),
    ),
    'advanced_search' => 
    array (
      0 => 
      array (
        'name' => 'document_name',
        'default' => true,
        'width' => '10%',
      ),
      1 => 
      array (
        'name' => 'category_id',
        'default' => true,
        'width' => '10%',
      ),
      2 => 
      array (
        'name' => 'subcategory_id',
        'default' => true,
        'width' => '10%',
      ),
      3 => 
      array (
        'name' => 'active_date',
        'default' => true,
        'width' => '10%',
      ),
      4 => 
      array (
        'name' => 'exp_date',
        'default' => true,
        'width' => '10%',
      ),
      5 => 
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
        'default' => true,
        'width' => '10%',
      ),
      6 => 
      array (
        'type' => 'int',
        'default' => true,
        'label' => 'LBL_CONVERTED',
        'width' => '10%',
        'name' => 'converted',
      ),
      7 => 
      array (
        'name' => 'favorites_only',
        'label' => 'LBL_FAVORITES_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
    ),
  ),
);