<?php
 // created: 2016-10-05 14:30:56
$dictionary['Lead']['fields']['dotb_credit_anomaly_provider_c']['labelValue'] = 'Past credit anomaly provider';
$dictionary['Lead']['fields']['dotb_credit_anomaly_provider_c']['full_text_search']['enabled'] = true;
$dictionary['Lead']['fields']['dotb_credit_anomaly_provider_c']['full_text_search']['searchable'] = false;
$dictionary['Lead']['fields']['dotb_credit_anomaly_provider_c']['full_text_search']['boost'] = 1;
$dictionary['Lead']['fields']['dotb_credit_anomaly_provider_c']['enforced'] = '';
$dictionary['Lead']['fields']['dotb_credit_anomaly_provider_c']['dependency'] = 'or(equal($dotb_payment_behaviour_type_c,"carried_out_mostly_on_time"),equal($dotb_payment_behaviour_type_c,"mostly_not_on_time"),equal($dotb_payment_behaviour_type_c,"had_enforcements"))';
$dictionary['Lead']['fields']['dotb_credit_anomaly_provider_c']['duplicate_merge'] = 'enabled';
$dictionary['Lead']['fields']['dotb_credit_anomaly_provider_c']['merge_filter'] = 'enabled';

