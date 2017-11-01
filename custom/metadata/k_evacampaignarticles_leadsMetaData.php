<?php
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
    $dictionary['kevacampaignarticles_leads'] = array (
        'table' => 'k_evacampaignarticles_leads'
        , 'fields' => array (
            array('name' =>'id', 'type' =>'varchar', 'len'=>'36')
            , array('name' =>'k_evacampaignarticle_id', 'type' =>'varchar', 'len'=>'36')
            , array('name' =>'lead_id', 'type' =>'varchar', 'len'=>'36')
            // , array('name' =>'opened', 'type' =>'int', 'required'=>false, 'default'=>'0')
            , array ('name' => 'date_modified','type' => 'datetime')
            , array('name' =>'deleted', 'type' =>'bool', 'len'=>'1', 'required'=>true, 'default'=>'0')
        )
        , 'indices' => array (
        array('name' =>'k_evacampaignarticles_leadspk', 'type' =>'primary', 'fields'=>array('id'))
        , array('name' => 'idx_evacampaignarticles_leads', 'type' => 'index', 'fields'=> array('k_evacampaignarticle_id', 'deleted', 'lead_id'))
        )

        , 'relationships' => array ('kevacampaignarticles_leads' => array(
            'lhs_module'=> 'K_EvaCampaignArticles',
            'lhs_table'=> 'k_evacampaignarticles',
            'lhs_key' => 'id',
            'rhs_module'=> 'Leads',
            'rhs_table'=> 'leads',
            'rhs_key' => 'id',
            'relationship_type'=>'many-to-many',
            'join_table'=> 'k_evacampaignarticles_leads',
            'join_key_lhs'=>'k_evacampaignarticle_id',
            'join_key_rhs'=>'lead_id'
            )
        )
    );
?>