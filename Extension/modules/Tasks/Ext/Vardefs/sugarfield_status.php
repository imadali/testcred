<?php
 // created: 2016-10-05 14:30:59
$dictionary['Task']['fields']['status']['name'] = 'status';
$dictionary['Task']['fields']['status']['vname'] = 'LBL_STATUS';
$dictionary['Task']['fields']['status']['type'] = 'enum';
$dictionary['Task']['fields']['status']['options'] = 'task_status_dom';
$dictionary['Task']['fields']['status']['len'] = 100;
$dictionary['Task']['fields']['status']['required'] = true;
$dictionary['Task']['fields']['status']['default'] = 'open';
$dictionary['Task']['fields']['status']['duplicate_on_record_copy'] = 'no';
$dictionary['Task']['fields']['status']['full_text_search']['enabled'] = true;
$dictionary['Task']['fields']['status']['full_text_search']['searchable'] = false;
$dictionary['Task']['fields']['status']['full_text_search']['boost'] = 1;
$dictionary['Task']['fields']['status']['audited'] = true;

