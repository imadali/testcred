<?php
 // created: 2016-10-05 14:30:58
$dictionary['Opportunity']['fields']['saldo']['precision'] = '2';
$dictionary['Opportunity']['fields']['saldo']['labelValue'] = 'Saldo';
$dictionary['Opportunity']['fields']['saldo']['enforced'] = '';
$dictionary['Opportunity']['fields']['saldo']['dependency'] = 'equal($transfer_fee,"1")';
$dictionary['Opportunity']['fields']['saldo']['related_fields'][0] = 'currency_id';
$dictionary['Opportunity']['fields']['saldo']['related_fields'][1] = 'base_rate';
$dictionary['Opportunity']['fields']['saldo']['required'] = false;
$dictionary['Opportunity']['fields']['saldo']['name'] = 'saldo';
$dictionary['Opportunity']['fields']['saldo']['vname'] = 'LBL_SALDO';
$dictionary['Opportunity']['fields']['saldo']['type'] = 'currency';
$dictionary['Opportunity']['fields']['saldo']['massupdate'] = false;
$dictionary['Opportunity']['fields']['saldo']['default'] = NULL;
$dictionary['Opportunity']['fields']['saldo']['no_default'] = false;
$dictionary['Opportunity']['fields']['saldo']['comments'] = '';
$dictionary['Opportunity']['fields']['saldo']['help'] = '';
$dictionary['Opportunity']['fields']['saldo']['importable'] = 'true';
$dictionary['Opportunity']['fields']['saldo']['duplicate_merge'] = 'enabled';
$dictionary['Opportunity']['fields']['saldo']['duplicate_merge_dom_value'] = '1';
$dictionary['Opportunity']['fields']['saldo']['audited'] = true;
$dictionary['Opportunity']['fields']['saldo']['reportable'] = true;
$dictionary['Opportunity']['fields']['saldo']['unified_search'] = false;
$dictionary['Opportunity']['fields']['saldo']['merge_filter'] = 'disabled';
$dictionary['Opportunity']['fields']['saldo']['calculated'] = false;
$dictionary['Opportunity']['fields']['saldo']['len'] = '26';
$dictionary['Opportunity']['fields']['saldo']['size'] = '20';
$dictionary['Opportunity']['fields']['saldo']['enable_range_search'] = false;

