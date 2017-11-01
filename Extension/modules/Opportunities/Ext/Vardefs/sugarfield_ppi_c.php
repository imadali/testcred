<?php
 // created: 2016-10-05 14:30:58
$dictionary['Opportunity']['fields']['ppi_c']['labelValue'] = 'PPI';
$dictionary['Opportunity']['fields']['ppi_c']['calculated'] = '1';
$dictionary['Opportunity']['fields']['ppi_c']['formula'] = 'not(equal($provider_id_c,"rci"))';
$dictionary['Opportunity']['fields']['ppi_c']['enforced'] = '';
$dictionary['Opportunity']['fields']['ppi_c']['dependency'] = 'and(not(equal($provider_id_c,"rci")),not(equal($provider_id_c,"bank_now_flex")))';

