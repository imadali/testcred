<?php
 // created: 2016-10-05 14:30:56
$dictionary['Lead']['fields']['description']['audited'] = true;
$dictionary['Lead']['fields']['description']['massupdate'] = false;
$dictionary['Lead']['fields']['description']['comments'] = 'Full text of the note';
$dictionary['Lead']['fields']['description']['duplicate_merge'] = 'enabled';
$dictionary['Lead']['fields']['description']['duplicate_merge_dom_value'] = '1';
$dictionary['Lead']['fields']['description']['merge_filter'] = 'enabled';
$dictionary['Lead']['fields']['description']['full_text_search']['enabled'] = true;
$dictionary['Lead']['fields']['description']['full_text_search']['searchable'] = false;
$dictionary['Lead']['fields']['description']['full_text_search']['boost'] = 0.69999999999999996;
$dictionary['Lead']['fields']['description']['calculated'] = false;
$dictionary['Lead']['fields']['description']['rows'] = '6';
$dictionary['Lead']['fields']['description']['cols'] = '80';
$dictionary['Lead']['fields']['description']['required'] = false;
$dictionary['Lead']['fields']['description']['dependency'] = 'equal($has_applied_for_other_credit_c,"yes")';

