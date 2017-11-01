<?php
$dictionary['Contract']['fields']['lead_first_name']=array (
      'labelValue' => 'lead name',
      'calculated' => true,
      'formula' => 'concat(related($contracts_leads_1,"first_name")," ",related($contracts_leads_1,"last_name"))',
      'dependency' => '',
      'required' => false,
      'name' => 'lead_first_name',
      'vname' => 'LBL_LEAD_NAME',
      'type' => 'varchar',
      'len' => '255',
      'size' => '20',
    );

 ?>