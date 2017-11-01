<?php
 // created: 2016-10-05 14:30:55
$dictionary['Contact']['fields']['dotb_denial_provider']['type'] = 'varchar';
$dictionary['Contact']['fields']['dotb_denial_provider']['name'] = 'dotb_denial_provider';
$dictionary['Contact']['fields']['dotb_denial_provider']['vname'] = 'LBL_DOTB_DENIAL_PROVIDER';
$dictionary['Contact']['fields']['dotb_denial_provider']['len'] = '255';
$dictionary['Contact']['fields']['dotb_denial_provider']['merge_filter'] = 'enabled';
$dictionary['Contact']['fields']['dotb_denial_provider']['importable'] = 'true';
$dictionary['Contact']['fields']['dotb_denial_provider']['studio'] = 'visible';
$dictionary['Contact']['fields']['dotb_denial_provider']['audited'] = false;
$dictionary['Contact']['fields']['dotb_denial_provider']['massupdate'] = false;
$dictionary['Contact']['fields']['dotb_denial_provider']['duplicate_merge'] = 'enabled';
$dictionary['Contact']['fields']['dotb_denial_provider']['duplicate_merge_dom_value'] = '1';
$dictionary['Contact']['fields']['dotb_denial_provider']['full_text_search']['enabled'] = true;
$dictionary['Contact']['fields']['dotb_denial_provider']['full_text_search']['searchable'] = false;
$dictionary['Contact']['fields']['dotb_denial_provider']['full_text_search']['boost'] = 1;
$dictionary['Contact']['fields']['dotb_denial_provider']['calculated'] = false;
$dictionary['Contact']['fields']['dotb_denial_provider']['dependency'] = 'equal($dotb_had_credit_denial_in_last_6_months,"yes")';

