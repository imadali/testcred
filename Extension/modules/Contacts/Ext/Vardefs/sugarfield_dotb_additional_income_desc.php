<?php
 // created: 2016-10-05 14:30:55
$dictionary['Contact']['fields']['dotb_additional_income_desc']['type'] = 'enum';
$dictionary['Contact']['fields']['dotb_additional_income_desc']['options'] = 'dotb_alimony_status_list';
$dictionary['Contact']['fields']['dotb_additional_income_desc']['name'] = 'dotb_additional_income_desc';
$dictionary['Contact']['fields']['dotb_additional_income_desc']['vname'] = 'LBL_DOTB_ADDITIONAL_INCOME_DESC';
$dictionary['Contact']['fields']['dotb_additional_income_desc']['len'] = '100';
$dictionary['Contact']['fields']['dotb_additional_income_desc']['size'] = '255';
$dictionary['Contact']['fields']['dotb_additional_income_desc']['merge_filter'] = 'enabled';
$dictionary['Contact']['fields']['dotb_additional_income_desc']['importable'] = 'true';
$dictionary['Contact']['fields']['dotb_additional_income_desc']['studio'] = 'visible';
$dictionary['Contact']['fields']['dotb_additional_income_desc']['audited'] = false;
$dictionary['Contact']['fields']['dotb_additional_income_desc']['massupdate'] = false;
$dictionary['Contact']['fields']['dotb_additional_income_desc']['duplicate_merge'] = 'enabled';
$dictionary['Contact']['fields']['dotb_additional_income_desc']['duplicate_merge_dom_value'] = '1';
$dictionary['Contact']['fields']['dotb_additional_income_desc']['full_text_search']['enabled'] = true;
$dictionary['Contact']['fields']['dotb_additional_income_desc']['full_text_search']['searchable'] = false;
$dictionary['Contact']['fields']['dotb_additional_income_desc']['full_text_search']['boost'] = 1;
$dictionary['Contact']['fields']['dotb_additional_income_desc']['calculated'] = false;
$dictionary['Contact']['fields']['dotb_additional_income_desc']['dependency'] = 'equal($dotb_rent_alimony_income_c,"yes")';

