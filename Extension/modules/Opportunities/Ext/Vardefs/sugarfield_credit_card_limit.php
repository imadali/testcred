<?php
    /**
    * Credit Card Limit field CRED-766
    */    
    $dictionary['Opportunity']['fields']['credit_card_limit'] = array( 
        'enforced' => '',
        'dependency' => '',
        'related_fields' => 
        array (
            0 => 'currency_id',
            1 => 'base_rate',
        ),
        'required' => false,
        'name' => 'credit_card_limit',
        'vname' => 'LBL_CREDIT_CARD_LIMIT',
        'type' => 'currency',
        'massupdate' => false,
        'default' => '0',
        'no_default' => false,
        'comments' => '',
        'help' => '',
        'importable' => 'true',
        'duplicate_merge' => 'enabled',
        'duplicate_merge_dom_value' => '1',
        'audited' => true,
        'reportable' => true,
        'unified_search' => false,
        'merge_filter' => 'disabled',
        'calculated' => false,
        'len' => '26',
        'size' => '20',
        'enable_range_search' => false,
        'precision' => 6,
    );

?>