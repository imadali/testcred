<?php
 // created: 2016-10-05 14:30:57
$dictionary['Lead']['fields']['other_credit_reason_c']['labelValue'] = 'Other credit reason';
$dictionary['Lead']['fields']['other_credit_reason_c']['full_text_search']['enabled'] = true;
$dictionary['Lead']['fields']['other_credit_reason_c']['full_text_search']['searchable'] = false;
$dictionary['Lead']['fields']['other_credit_reason_c']['full_text_search']['boost'] = 1;
$dictionary['Lead']['fields']['other_credit_reason_c']['enforced'] = '';
$dictionary['Lead']['fields']['other_credit_reason_c']['dependency'] = 'equal($credit_usage_type_id_c,"others")';
$dictionary['Lead']['fields']['other_credit_reason_c']['duplicate_merge'] = 'enabled';
$dictionary['Lead']['fields']['other_credit_reason_c']['merge_filter'] = 'enabled';

