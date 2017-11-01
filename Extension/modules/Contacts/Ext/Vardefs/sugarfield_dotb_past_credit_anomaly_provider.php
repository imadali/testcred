<?php
 // created: 2016-10-05 14:30:55
$dictionary['Contact']['fields']['dotb_past_credit_anomaly_provider']['type'] = 'varchar';
$dictionary['Contact']['fields']['dotb_past_credit_anomaly_provider']['name'] = 'dotb_past_credit_anomaly_provider';
$dictionary['Contact']['fields']['dotb_past_credit_anomaly_provider']['vname'] = 'LBL_DOTB_PAST_CREDIT_ANOMALY_PROVIDER';
$dictionary['Contact']['fields']['dotb_past_credit_anomaly_provider']['len'] = '255';
$dictionary['Contact']['fields']['dotb_past_credit_anomaly_provider']['merge_filter'] = 'enabled';
$dictionary['Contact']['fields']['dotb_past_credit_anomaly_provider']['importable'] = 'true';
$dictionary['Contact']['fields']['dotb_past_credit_anomaly_provider']['studio'] = 'visible';
$dictionary['Contact']['fields']['dotb_past_credit_anomaly_provider']['audited'] = false;
$dictionary['Contact']['fields']['dotb_past_credit_anomaly_provider']['massupdate'] = false;
$dictionary['Contact']['fields']['dotb_past_credit_anomaly_provider']['duplicate_merge'] = 'enabled';
$dictionary['Contact']['fields']['dotb_past_credit_anomaly_provider']['duplicate_merge_dom_value'] = '1';
$dictionary['Contact']['fields']['dotb_past_credit_anomaly_provider']['full_text_search']['enabled'] = true;
$dictionary['Contact']['fields']['dotb_past_credit_anomaly_provider']['full_text_search']['searchable'] = false;
$dictionary['Contact']['fields']['dotb_past_credit_anomaly_provider']['full_text_search']['boost'] = 1;
$dictionary['Contact']['fields']['dotb_past_credit_anomaly_provider']['calculated'] = false;
$dictionary['Contact']['fields']['dotb_past_credit_anomaly_provider']['dependency'] = 'or(equal($dotb_payment_behaviour_type_id,"carried_out_mostly_on_time"),equal($dotb_payment_behaviour_type_id,"mostly_not_on_time"),equal($dotb_payment_behaviour_type_id,"had_enforcements"))';

