<?php
 // created: 2016-10-05 14:30:55
$dictionary['Contract']['fields']['customer_ppi_c']['labelValue'] = 'PPI';
$dictionary['Contract']['fields']['customer_ppi_c']['enforced'] = '';
$dictionary['Contract']['fields']['customer_ppi_c']['dependency'] = 'and(not(equal($provider_id_c,"rci")),not(equal($provider_id_c,"")),not(equal($provider_id_c,"bank_now_flex")))';


