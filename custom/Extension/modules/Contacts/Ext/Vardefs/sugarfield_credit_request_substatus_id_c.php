<?php
 // created: 2016-10-05 14:30:55
$dictionary['Contact']['fields']['credit_request_substatus_id_c']['labelValue'] = 'Substatus';
$dictionary['Contact']['fields']['credit_request_substatus_id_c']['dependency'] = 'not(equal($credit_request_status_id_c,"01_new"))';
$dictionary['Contact']['fields']['credit_request_substatus_id_c']['duplicate_merge'] = 'enabled';
$dictionary['Contact']['fields']['credit_request_substatus_id_c']['merge_filter'] = 'enabled';
$dictionary['Contact']['fields']['credit_request_substatus_id_c']['full_text_search']['boost'] = 1;

