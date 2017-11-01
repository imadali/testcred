<?php
 // created: 2016-10-05 14:30:55
$dictionary['Contact']['fields']['dotb_has_second_income']['type'] = 'enum';
$dictionary['Contact']['fields']['dotb_has_second_income']['size'] = 255;
$dictionary['Contact']['fields']['dotb_has_second_income']['len'] = 100;
$dictionary['Contact']['fields']['dotb_has_second_income']['options'] = 'lq_yes_no_status_list';
$dictionary['Contact']['fields']['dotb_has_second_income']['name'] = 'dotb_has_second_income';
$dictionary['Contact']['fields']['dotb_has_second_income']['vname'] = 'LBL_DOTB_HAS_SECOND_INCOME';
$dictionary['Contact']['fields']['dotb_has_second_income']['unified_search'] = false;
$dictionary['Contact']['fields']['dotb_has_second_income']['studio'] = 'visible';
$dictionary['Contact']['fields']['dotb_has_second_income']['massupdate'] = false;
$dictionary['Contact']['fields']['dotb_has_second_income']['merge_filter'] = 'enabled';
$dictionary['Contact']['fields']['dotb_has_second_income']['calculated'] = false;
$dictionary['Contact']['fields']['dotb_has_second_income']['reportable'] = true;
$dictionary['Contact']['fields']['dotb_has_second_income']['importable'] = 'true';
$dictionary['Contact']['fields']['dotb_has_second_income']['audited'] = true;
$dictionary['Contact']['fields']['dotb_has_second_income']['required'] = false;
$dictionary['Contact']['fields']['dotb_has_second_income']['duplicate_merge'] = 'enabled';
$dictionary['Contact']['fields']['dotb_has_second_income']['duplicate_merge_dom_value'] = '1';
$dictionary['Contact']['fields']['dotb_has_second_income']['dependency'] = 'equal($dotb_civil_status_id,"married")';
$dictionary['Contact']['fields']['dotb_has_second_income']['full_text_search']['boost'] = 1;

