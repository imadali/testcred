<?php
 // created: 2016-10-05 14:30:55
$dictionary['Contact']['fields']['dotb_is_rent_split']['type'] = 'enum';
$dictionary['Contact']['fields']['dotb_is_rent_split']['size'] = '255';
$dictionary['Contact']['fields']['dotb_is_rent_split']['len'] = '100';
$dictionary['Contact']['fields']['dotb_is_rent_split']['options'] = 'lq_yes_no_status_list';
$dictionary['Contact']['fields']['dotb_is_rent_split']['name'] = 'dotb_is_rent_split';
$dictionary['Contact']['fields']['dotb_is_rent_split']['vname'] = 'LBL_DOTB_IS_RENT_SPLIT';
$dictionary['Contact']['fields']['dotb_is_rent_split']['unified_search'] = false;
$dictionary['Contact']['fields']['dotb_is_rent_split']['studio'] = 'visible';
$dictionary['Contact']['fields']['dotb_is_rent_split']['massupdate'] = false;
$dictionary['Contact']['fields']['dotb_is_rent_split']['merge_filter'] = 'enabled';
$dictionary['Contact']['fields']['dotb_is_rent_split']['calculated'] = false;
$dictionary['Contact']['fields']['dotb_is_rent_split']['reportable'] = true;
$dictionary['Contact']['fields']['dotb_is_rent_split']['importable'] = 'true';
$dictionary['Contact']['fields']['dotb_is_rent_split']['audited'] = true;
$dictionary['Contact']['fields']['dotb_is_rent_split']['required'] = false;
$dictionary['Contact']['fields']['dotb_is_rent_split']['default'] = false;
$dictionary['Contact']['fields']['dotb_is_rent_split']['duplicate_merge'] = 'enabled';
$dictionary['Contact']['fields']['dotb_is_rent_split']['duplicate_merge_dom_value'] = '1';
$dictionary['Contact']['fields']['dotb_is_rent_split']['dependency'] = 'equal($dotb_housing_situation_id,"flat_share")';
$dictionary['Contact']['fields']['dotb_is_rent_split']['full_text_search']['boost'] = 1;

