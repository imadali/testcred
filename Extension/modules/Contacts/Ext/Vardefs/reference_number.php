<?php

$dictionary['Contact']['fields']["reference_number"] = 
   array (
     'duplicate_merge_dom_value' => 0,
     'labelValue' => 'Reference no',
     'full_text_search' => 
     array (
       'boost' => '0',
       'enabled' => false,
     ),
     'calculated' => '1',
     'formula' => 'add(max($reference_number),1)',
     'enforced' => false,
     'dependency' => '',
     'required' => false,
     'source' => 'custom_fields',
     'name' => 'reference_number',
     'vname' => 'LBL_REFERENCE_NUMBER',
     'type' => 'int',
     'massupdate' => false,
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
     'enable_range_search' => false,
     'disable_num_format' => NULL,
     'min' => false,
     'max' => false,
   );