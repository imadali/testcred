<?php
 // created: 2016-04-15 07:01:40
$dictionary['Lead']['fields']['credit_request_substatus_id_c']['labelValue']='Substatus';
$dictionary['Lead']['fields']['credit_request_substatus_id_c']['dependency'] = 'or(equal($credit_request_status_id_c,"00_pendent_geschlossen"),equal($credit_request_status_id_c,"11_closed"))';
$dictionary['Lead']['fields']['credit_request_substatus_id_c']['duplicate_merge']='enabled';
$dictionary['Lead']['fields']['credit_request_substatus_id_c']['merge_filter']='enabled';
/**
* CRED-959 : With visibility_grid dependency was not working. Removed as for both 00 and 11 status same values are shown.
*/
/* $dictionary['Lead']['fields']['credit_request_substatus_id_c']['visibility_grid']=array (
  'trigger' => 'credit_request_status_id_c',
  'values' => 
  array (
    '00_pendent_geschlossen' => 
    array (
      0 => '',
      1 => 'fake_inquiry',
      2 => 'interest_rate_too_high',
      3 => 'bad_credit_worthiness',
      4 => 'other_financing_solutio',
      5 => 'other_reasons',
      6 => 'no_need',
      7 => 'awaiting_deadline',
      8 => 'duplicate_request', 
      9 => 'unemployed',
      10 => 'enforcements',
      11 => 'dv_score_nok',
      12 => 'car_finder',
      13 => 'moto_finder',
      14 => 'waiver',
    ),  
    '01_new' => 
    array (
    ),
    '2a_not_reached_first_round' => 
    array (
      0 => '',
    ),
    '2b_not_reached_second_round' => 
    array (
      0 => '',
    ),
    '2c_not_reached_third_round' => 
    array (
      0 => '',
    ),
    '3a_document_sent_first_round' => 
    array (
      0 => '',
    ),
    '3b_document_sent_second_round' => 
    array (
      0 => '',
    ),
    '04_documents_received' => 
    array (
      0 => '',
    ),
    '05_checking_request' => 
    array (
      0 => '',
    ),
    '06_sales_conversation' => 
    array (
      0 => '',
    ),
    '07_creating_contract' => 
    array (
      0 => '',
    ),
    '08_contract_at_customer' => 
     array (
      0 => '',
    ),
    '09_payout' => 
    array (
      0 => '',
    ),
    '10_active' => 
    array (
      0 => '',
    ),
    '11_closed' => 
     array (
      0 => '',
      1 => 'fake_inquiry',
      2 => 'interest_rate_too_high',
      3 => 'bad_credit_worthiness',
      4 => 'other_financing_solutio',
      5 => 'other_reasons',
      6 => 'no_need',
      7 => 'awaiting_deadline',
      8 => 'duplicate_request',
      9 => 'unemployed',
      10 => 'enforcements',
      11 => 'dv_score_nok',
      12 => 'car_finder',
      13 => 'moto_finder',
      14 => 'waiver',
    ),
    '13_customer_center' => 
    array (
      0 => '',
    ),
  ),
); */

?>