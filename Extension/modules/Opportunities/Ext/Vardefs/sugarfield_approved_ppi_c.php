<?php
 // created: 2016-10-05 14:30:57
$dictionary['Opportunity']['fields']['approved_ppi_c']['labelValue'] = 'PPI';
$dictionary['Opportunity']['fields']['approved_ppi_c']['enforced'] = '';
$dictionary['Opportunity']['fields']['approved_ppi_c']['dependency'] = 'and(not(equal($provider_id_c,"rci")),not(equal($provider_id_c,"bank_now_flex")))';


