<?php
 // created: 2016-10-05 14:30:59
$dictionary['Task']['fields']['email_c']['labelValue'] = 'Lead Email';
$dictionary['Task']['fields']['email_c']['full_text_search']['enabled'] = true;
$dictionary['Task']['fields']['email_c']['full_text_search']['searchable'] = false;
$dictionary['Task']['fields']['email_c']['full_text_search']['boost'] = 1;
$dictionary['Task']['fields']['email_c']['calculated'] = 'true';
$dictionary['Task']['fields']['email_c']['enforced'] = 'true';
$dictionary['Task']['fields']['email_c']['dependency'] = '';
$dictionary['Task']['fields']['email_c']['required'] = false;
$dictionary['Task']['fields']['email_c']['source'] = 'custom_fields';
$dictionary['Task']['fields']['email_c']['name'] = 'email_c';
$dictionary['Task']['fields']['email_c']['vname'] = 'LBL_EMAIL';
$dictionary['Task']['fields']['email_c']['type'] = 'varchar';
$dictionary['Task']['fields']['email_c']['massupdate'] = false;
$dictionary['Task']['fields']['email_c']['default'] = '';
$dictionary['Task']['fields']['email_c']['no_default'] = false;
$dictionary['Task']['fields']['email_c']['comments'] = '';
$dictionary['Task']['fields']['email_c']['help'] = '';
$dictionary['Task']['fields']['email_c']['importable'] = 'true';
$dictionary['Task']['fields']['email_c']['duplicate_merge'] = 'enabled';
$dictionary['Task']['fields']['email_c']['duplicate_merge_dom_value'] = '1';
$dictionary['Task']['fields']['email_c']['audited'] = false;
$dictionary['Task']['fields']['email_c']['reportable'] = true;
$dictionary['Task']['fields']['email_c']['unified_search'] = false;
$dictionary['Task']['fields']['email_c']['merge_filter'] = 'disabled';
$dictionary['Task']['fields']['email_c']['len'] = '255';
$dictionary['Task']['fields']['email_c']['size'] = '20';
$dictionary['Task']['fields']['email_c']['formula'] = 'related($leads,"email1")';

