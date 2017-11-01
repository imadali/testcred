<?php
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
    $dictionary['kevacampaignarticles_contacts'] = array (
        'table' => 'k_evacampaignarticles_contacts'
        , 'fields' => array (
            array('name' =>'id', 'type' =>'varchar', 'len'=>'36')
            , array('name' =>'k_evacampaignarticle_id', 'type' =>'varchar', 'len'=>'36')
            , array('name' =>'contact_id', 'type' =>'varchar', 'len'=>'36')
            // , array('name' =>'opened', 'type' =>'int', 'required'=>false, 'default'=>'0')
            , array ('name' => 'date_modified','type' => 'datetime')
            , array('name' =>'deleted', 'type' =>'bool', 'len'=>'1', 'required'=>true, 'default'=>'0')
        )
        , 'indices' => array (
        array('name' =>'k_evacampaignarticles_contactspk', 'type' =>'primary', 'fields'=>array('id'))
        , array('name' => 'idx_evacampaignarticles_contacts', 'type' => 'index', 'fields'=> array('k_evacampaignarticle_id', 'deleted', 'contact_id'))
        )

        , 'relationships' => array ('kevacampaignarticles_contacts' => array(
            'lhs_module'=> 'K_EvaCampaignArticles',
            'lhs_table'=> 'k_evacampaignarticles',
            'lhs_key' => 'id',
            'rhs_module'=> 'Contacts',
            'rhs_table'=> 'contacts',
            'rhs_key' => 'id',
            'relationship_type'=>'many-to-many',
            'join_table'=> 'k_evacampaignarticles_contacts',
            'join_key_lhs'=>'k_evacampaignarticle_id',
            'join_key_rhs'=>'contact_id'
            )
        )
    );
?>