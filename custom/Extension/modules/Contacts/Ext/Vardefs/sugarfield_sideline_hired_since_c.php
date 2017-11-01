<?php
 // created: 2016-10-05 14:30:55
$dictionary['Contact']['fields']['sideline_hired_since_c']['required'] = false;
$dictionary['Contact']['fields']['sideline_hired_since_c']['dependency'] = 'or(equal($dotb_has_second_job,"yes"),equal($dotb_has_second_income,"yes"))';
$dictionary['Contact']['fields']['sideline_hired_since_c']['source'] = 'custom_fields';
$dictionary['Contact']['fields']['sideline_hired_since_c']['name'] = 'sideline_hired_since_c';
$dictionary['Contact']['fields']['sideline_hired_since_c']['vname'] = 'LBL_SIDELINE_HIRED_SINCE';
$dictionary['Contact']['fields']['sideline_hired_since_c']['type'] = 'date';
$dictionary['Contact']['fields']['sideline_hired_since_c']['massupdate'] = true;
$dictionary['Contact']['fields']['sideline_hired_since_c']['default'] = NULL;
$dictionary['Contact']['fields']['sideline_hired_since_c']['no_default'] = false;
$dictionary['Contact']['fields']['sideline_hired_since_c']['comments'] = '';
$dictionary['Contact']['fields']['sideline_hired_since_c']['help'] = '';
$dictionary['Contact']['fields']['sideline_hired_since_c']['importable'] = 'true';
$dictionary['Contact']['fields']['sideline_hired_since_c']['duplicate_merge'] = 'enabled';
$dictionary['Contact']['fields']['sideline_hired_since_c']['duplicate_merge_dom_value'] = '1';
$dictionary['Contact']['fields']['sideline_hired_since_c']['audited'] = false;
$dictionary['Contact']['fields']['sideline_hired_since_c']['reportable'] = true;
$dictionary['Contact']['fields']['sideline_hired_since_c']['unified_search'] = false;
$dictionary['Contact']['fields']['sideline_hired_since_c']['merge_filter'] = 'enabled';
$dictionary['Contact']['fields']['sideline_hired_since_c']['calculated'] = false;
$dictionary['Contact']['fields']['sideline_hired_since_c']['size'] = '20';
$dictionary['Contact']['fields']['sideline_hired_since_c']['enable_range_search'] = false;
$dictionary['Contact']['fields']['sideline_hired_since_c']['full_text_search']['boost'] = 1;

