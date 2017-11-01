<?php
 // created: 2016-10-05 14:30:56
$dictionary['Lead']['fields']['dotb_direct_withholding_tax_c']['labelValue'] = 'Direkte Quellensteuer';
$dictionary['Lead']['fields']['dotb_direct_withholding_tax_c']['enforced'] = '';
$dictionary['Lead']['fields']['dotb_direct_withholding_tax_c']['dependency'] = 'not(equal($dotb_iso_nationality_code_c,"ch"))';
$dictionary['Lead']['fields']['dotb_direct_withholding_tax_c']['len'] = 100;
$dictionary['Lead']['fields']['dotb_direct_withholding_tax_c']['size'] = 255;
$dictionary['Lead']['fields']['dotb_direct_withholding_tax_c']['type'] = 'enum';
$dictionary['Lead']['fields']['dotb_direct_withholding_tax_c']['options'] = 'lq_yes_no_status_list';
$dictionary['Lead']['fields']['dotb_direct_withholding_tax_c']['duplicate_merge'] = 'enabled';
$dictionary['Lead']['fields']['dotb_direct_withholding_tax_c']['merge_filter'] = 'enabled';

