<?php
 // created: 2016-10-05 14:30:59
// $dictionary['Task']['fields']['name']['calculated'] = '1';
$dictionary['Task']['fields']['name']['size'] = '255';
$dictionary['Task']['fields']['name']['len'] = '255';
// $dictionary['Task']['fields']['name']['formula'] = 'ifElse(equal(strlen(getDropdownValue("dotb_task_categories_list",$category_c)),0),$name,getDropdownValue("dotb_task_categories_list",$category_c))';
$dictionary['Task']['fields']['name']['full_text_search']['enabled'] = true;
$dictionary['Task']['fields']['name']['full_text_search']['searchable'] = true;
$dictionary['Task']['fields']['name']['full_text_search']['boost'] = 1.45;

