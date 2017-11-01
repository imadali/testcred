<?php
$dictionary['Opportunity']['fields']['lead_first_name']=array (
      'labelValue' => 'lead name',
      'calculated' => 'true',
      'formula' => 'concat(related($leads_opportunities_1,"first_name")," ",related($leads_opportunities_1,"last_name"))',
      'dependency' => '',
      'required' => false,
      'name' => 'lead_first_name',
      'vname' => 'LBL_LEAD_NAME',
      'type' => 'varchar',
      'len' => '255',
      'size' => '20',
    );

 ?>