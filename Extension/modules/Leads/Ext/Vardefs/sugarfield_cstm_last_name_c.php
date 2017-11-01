<?php
 // created: 2016-10-05 14:30:56
$dictionary['Lead']['fields']['cstm_last_name_c']['name'] = 'cstm_last_name_c';
$dictionary['Lead']['fields']['cstm_last_name_c']['vname'] = 'LBL_CSTM_LAST_NAME_C';
$dictionary['Lead']['fields']['cstm_last_name_c']['type'] = 'varchar';
$dictionary['Lead']['fields']['cstm_last_name_c']['len'] = '255';
$dictionary['Lead']['fields']['cstm_last_name_c']['comment'] = 'Last name of the contact';
$dictionary['Lead']['fields']['cstm_last_name_c']['merge_filter'] = 'enabled';
$dictionary['Lead']['fields']['cstm_last_name_c']['required'] = false;
$dictionary['Lead']['fields']['cstm_last_name_c']['enforced'] = false;
$dictionary['Lead']['fields']['cstm_last_name_c']['link'] = true;
$dictionary['Lead']['fields']['cstm_last_name_c']['calculated'] = '1';
$dictionary['Lead']['fields']['cstm_last_name_c']['importable'] = 'false';
$dictionary['Lead']['fields']['cstm_last_name_c']['audited'] = false;
$dictionary['Lead']['fields']['cstm_last_name_c']['massupdate'] = false;
$dictionary['Lead']['fields']['cstm_last_name_c']['source'] = 'custom_fields';
$dictionary['Lead']['fields']['cstm_last_name_c']['comments'] = 'Last name of the contact';
$dictionary['Lead']['fields']['cstm_last_name_c']['duplicate_merge'] = 'enabled';
$dictionary['Lead']['fields']['cstm_last_name_c']['duplicate_merge_dom_value'] = '1';
$dictionary['Lead']['fields']['cstm_last_name_c']['formula'] = 'concat($first_name," ",$last_name," ",ifElse(equal($date_entered,""),toString(today()),subStr(toString($date_entered),0,10))," Lead")';
$dictionary['Lead']['fields']['cstm_last_name_c']['full_text_search']['enabled'] = true;
$dictionary['Lead']['fields']['cstm_last_name_c']['full_text_search']['searchable'] = false;
$dictionary['Lead']['fields']['cstm_last_name_c']['full_text_search']['boost'] = 1;

