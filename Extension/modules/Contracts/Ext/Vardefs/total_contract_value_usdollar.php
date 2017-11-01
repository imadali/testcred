<?php
// created: 2016-02-19 18:03:55
$dictionary["Contract"]["fields"]["total_contract_value_usdollar"] = array (
      'name' => 'total_contract_value_usdollar',
      'vname' => 'LBL_TOTAL_CONTRACT_VALUE_USDOLLAR',
      'dbType' => 'decimal',
      'type' => 'currency',
      'len' => '26,6',
      'comment' => 'The overall contract value expressed in USD',
      'studio' => 
      array (
        'wirelesslistview' => false,
        'wirelesseditview' => false,
        'wirelessdetailview' => false,
        'wireless_basic_search' => false,
        'wireless_advanced_search' => false,
        'mobile' => false,
      ),
      'readonly' => true,
      'is_base_currency' => true,
      'related_fields' => 
      array (
        0 => 'currency_id',
        1 => 'base_rate',
      ),
      'formula' => 'ifElse(isNumeric($total_contract_value), currencyDivide($total_contract_value, ifElse(equal($base_rate,0),1,$base_rate)), "")',
      'calculated' => true,
      'enforced' => true,
);

