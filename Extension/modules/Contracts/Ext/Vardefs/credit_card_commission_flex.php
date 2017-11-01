<?php
$dictionary['Contract']['fields']['credit_card_commission_flex']=array (
    'required' => false,
    'name' => 'credit_card_commission_flex',
    'vname' => 'LBL_CREDIT_CARD_COMMISSION_FLEX',
    'label' => 'LBL_CREDIT_CARD_COMMISSION_FLEX',
    'type' => 'bool',
    'massupdate' => '0',
    'default' => NULL,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => true,
    'reportable' => true,
    'dependency' => 'equal($provider_id_c,"bank_now_flex")',
    );

 ?>