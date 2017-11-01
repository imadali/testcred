<?php
$dictionary['Contact']['fields']['currency_id']=array (
      'name' => 'currency_id',
      'type' => 'currency_id',
      'dbType' => 'id',
      'group' => 'currency_id',
      'vname' => 'LBL_CURRENCY',
      'function' => 'getCurrencies',
      'function_bean' => 'Currencies',
      'reportable' => false,
      'comment' => 'Currency used for display purposes',
      //'default' => '-99',
    );
    $dictionary['Contact']['fields']['currency_name'] = array (
      'name' => 'currency_name',
      'rname' => 'name',
      'id_name' => 'currency_id',
      'vname' => 'LBL_CURRENCY_NAME',
      'type' => 'relate',
      //'link' => 'currencies',
      'isnull' => true,
      'table' => 'currencies',
      'module' => 'Currencies',
      'source' => 'non-db',
      'function' => 'getCurrencies',
      'function_bean' => 'Currencies',
      'studio' => false,
      'duplicate_merge' => 'disabled',
      'massupdate' => false,
    );
    $dictionary['Contact']['fields']['currency_symbol'] =    array (
      'name' => 'currency_symbol',
      'rname' => 'symbol',
      'id_name' => 'currency_id',
      'vname' => 'LBL_CURRENCY_SYMBOL',
      'type' => 'relate',
      //'link' => 'currencies',
      'isnull' => true,
      'table' => 'currencies',
      'module' => 'Currencies',
      'source' => 'non-db',
      'function' => 'getCurrencySymbols',
      'function_bean' => 'Currencies',
      'studio' => false,
      'duplicate_merge' => 'disabled',
      'massupdate' => false,
    );
      $dictionary['Contact']['fields']['base_rate'] = array(
            'name' => 'base_rate',
            'vname' => 'LBL_CURRENCY_RATE',
            'type' => 'decimal',
            'len' => '26,6',
            'studio' => false
    );
 ?>