<?php
 // created: 2016-10-05 14:30:55
$dictionary['Contract']['fields']['ppi_c']['labelValue'] = 'PPI';
$dictionary['Contract']['fields']['ppi_c']['default'] = false;
$dictionary['Contract']['fields']['ppi_c']['calculated'] = '1';
$dictionary['Contract']['fields']['ppi_c']['formula'] = 'not(equal($provider_id_c,"rci"))';
$dictionary['Contract']['fields']['ppi_c']['enforced'] = '';
$dictionary['Contract']['fields']['ppi_c']['dependency'] = 'and(not(equal($provider_id_c,"rci")),not(equal($provider_id_c,"bank_now_flex")))';

