<?php
    /**
     * CRED-988 : Update KINAMU-Connector (updated fields array to use field name as index)
     */
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
    $dictionary['kevacampaigns_contacts'] = array (
        'table' => 'k_evacampaigns_contacts'
        , 'fields' => array (
            'id' => array('name' =>'id', 'type' =>'varchar', 'len'=>'36')
            , 'k_evacampaign_id' => array('name' =>'k_evacampaign_id', 'type' =>'varchar', 'len'=>'36')
            , 'contact_id' => array('name' =>'contact_id', 'type' =>'varchar', 'len'=>'36')
            , 'opened' => array('name' =>'opened', 'type' =>'int', 'required'=>false, 'default'=>'0')
            , 'unsubscribed' => array('name' =>'unsubscribed', 'type' =>'int', 'required'=>false, 'default'=>'0')
            , 'date_modified' => array('name' => 'date_modified','type' => 'datetime')
            , 'deleted' => array('name' =>'deleted', 'type' =>'bool', 'len'=>'1', 'required'=>true, 'default'=>'0')
        )
        , 'indices' => array (
        array('name' =>'k_evacampaigns_contactspk', 'type' =>'primary', 'fields'=>array('id'))
        , array('name' => 'idx_evacampaigns_contacts', 'type' => 'index', 'fields'=> array('k_evacampaign_id', 'deleted', 'contact_id'))
        )

        , 'relationships' => array ('kevacampaigns_contacts' => array(
            'lhs_module'=> 'K_EvaCampaigns',
            'lhs_table'=> 'k_evacampaigns',
            'lhs_key' => 'id',
            'rhs_module'=> 'Contacts',
            'rhs_table'=> 'contacts',
            'rhs_key' => 'id',
            'relationship_type'=>'many-to-many',
            'join_table'=> 'k_evacampaigns_contacts',
            'join_key_lhs'=>'k_evacampaign_id',
            'join_key_rhs'=>'contact_id'
            )
        )
    );
?>