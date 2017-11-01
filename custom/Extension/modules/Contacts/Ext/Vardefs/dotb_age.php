<?php

$dictionary['Contact']['fields']['dotb_age_c'] = 
    array (
      'duplicate_merge_dom_value' => 0,
      'labelValue' => 'Age',
      'full_text_search' => 
      array (
        'boost' => '0',
        'enabled' => false,
      ),
      'calculated' => '0',
      //'formula' => 'floor(divide(divide(subtract(daysUntil(today()),daysUntil($birthdate)),30),12))',
      'readonly' => 'true',
      'enforced' => '1',
      'dependency' => '',
      'required' => false,
      'source' => 'custom_fields',
      'name' => 'dotb_age_c',
      'vname' => 'LBL_DOTB_AGE',
      'type' => 'varchar',
      'massupdate' => false,
      'default' => NULL,
      'no_default' => false,
      'comments' => '',
      'help' => '',
      'importable' => 'false',
      'duplicate_merge' => 'enabled',
      'audited' => true,
      'reportable' => true,
      'unified_search' => false,
      'merge_filter' => 'enabled',
      'len' => '255',
      'size' => '20',
    );


 ?>