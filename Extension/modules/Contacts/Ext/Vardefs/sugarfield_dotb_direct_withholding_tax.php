<?php
 // created: 2016-10-05 14:30:55
$dictionary['Contact']['fields']['dotb_direct_withholding_tax']['dependency'] = 'not(equal($dotb_iso_nationality_code,"ch"))';
$dictionary['Contact']['fields']['dotb_direct_withholding_tax']['visibility_grid'] = '';
$dictionary['Contact']['fields']['dotb_direct_withholding_tax']['required'] = false;
$dictionary['Contact']['fields']['dotb_direct_withholding_tax']['source'] = 'custom_fields';
$dictionary['Contact']['fields']['dotb_direct_withholding_tax']['name'] = 'dotb_direct_withholding_tax';
$dictionary['Contact']['fields']['dotb_direct_withholding_tax']['vname'] = 'LBL_DOTB_DIRECT_WITHHOLDING_TAX';
$dictionary['Contact']['fields']['dotb_direct_withholding_tax']['type'] = 'enum';
$dictionary['Contact']['fields']['dotb_direct_withholding_tax']['massupdate'] = true;
$dictionary['Contact']['fields']['dotb_direct_withholding_tax']['default'] = '';
$dictionary['Contact']['fields']['dotb_direct_withholding_tax']['no_default'] = false;
$dictionary['Contact']['fields']['dotb_direct_withholding_tax']['comments'] = '';
$dictionary['Contact']['fields']['dotb_direct_withholding_tax']['help'] = '';
$dictionary['Contact']['fields']['dotb_direct_withholding_tax']['importable'] = 'true';
$dictionary['Contact']['fields']['dotb_direct_withholding_tax']['duplicate_merge'] = 'enabled';
$dictionary['Contact']['fields']['dotb_direct_withholding_tax']['duplicate_merge_dom_value'] = '1';
$dictionary['Contact']['fields']['dotb_direct_withholding_tax']['audited'] = false;
$dictionary['Contact']['fields']['dotb_direct_withholding_tax']['reportable'] = true;
$dictionary['Contact']['fields']['dotb_direct_withholding_tax']['unified_search'] = false;
$dictionary['Contact']['fields']['dotb_direct_withholding_tax']['merge_filter'] = 'enabled';
$dictionary['Contact']['fields']['dotb_direct_withholding_tax']['calculated'] = false;
$dictionary['Contact']['fields']['dotb_direct_withholding_tax']['len'] = 100;
$dictionary['Contact']['fields']['dotb_direct_withholding_tax']['size'] = '255';
$dictionary['Contact']['fields']['dotb_direct_withholding_tax']['options'] = 'lq_yes_no_status_list';
$dictionary['Contact']['fields']['dotb_direct_withholding_tax']['full_text_search']['boost'] = 1;

