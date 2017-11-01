<?php
 // created: 2016-10-05 14:30:55
$dictionary['Contract']['fields']['customer_credit_amount_c']['labelValue'] = 'Credit Amount';
$dictionary['Contract']['fields']['customer_credit_amount_c']['enforced'] = '';
$dictionary['Contract']['fields']['customer_credit_amount_c']['dependency'] = 'not(equal($provider_id_c,"bank_now_flex"))';
$dictionary['Contract']['fields']['customer_credit_amount_c']['related_fields'][0] = 'currency_id';
$dictionary['Contract']['fields']['customer_credit_amount_c']['related_fields'][1] = 'base_rate';
$dictionary['Contract']['fields']['customer_credit_amount_c']['precision'] = 2;

