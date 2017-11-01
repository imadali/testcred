<?php
/*
$dictionary['Task']['fields']['name'] =
    array (
      'name' => 'name',
      'vname' => 'LBL_SUBJECT',
      'dbType' => 'varchar',
      'type' => 'name',
      'len' => '50',
      'unified_search' => false,
      'full_text_search' => 
      array (
        'boost' => '3',
        'enabled' => true,
      ),
      'importable' => true,
      'required' => true,
      'audited' => false,
      'massupdate' => false,
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => 0,
      'merge_filter' => 'disabled',
      'calculated' => 'true',
      'formula' => 'getDropdownValue("dotb_task_categories_list",$category_c)',
      'enforced' => false,
    );

*/
$dictionary['Task']['fields']['category_c'] =
    array (
      'labelValue' => 'Category',
      'dependency' => '',
      'visibility_grid' => '',
      'required' => false,
      'source' => 'custom_fields',
      'name' => 'category_c',
      'vname' => 'LBL_CATEGORY',
      'type' => 'enum',
      'massupdate' => true,
      'default' => NULL,
      'no_default' => false,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'enabled',
      'duplicate_merge_dom_value' => '1',
      'audited' => false,
      'reportable' => true,
      'unified_search' => false,
      'merge_filter' => 'disabled',
      'calculated' => false,
      'len' => 100,
      'size' => '20',
      'options' => 'dotb_task_categories_list',
    );
 ?>