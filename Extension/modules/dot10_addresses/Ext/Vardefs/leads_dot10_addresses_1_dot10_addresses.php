<?php

// created: 2016-03-10 12:55:42
$dictionary["dot10_addresses"]["fields"]["leads_dot10_addresses_1"] = array(
    'name' => 'leads_dot10_addresses_1',
    'type' => 'link',
    'relationship' => 'leads_dot10_addresses_1',
    'source' => 'non-db',
    'module' => 'Leads',
    'bean_name' => 'Lead',
    'side' => 'right',
    'vname' => 'LBL_LEADS_DOT10_ADDRESSES_1_FROM_DOT10_ADDRESSES_TITLE',
    'id_name' => 'leads_dot10_addresses_1leads_ida',
    'link-type' => 'one',
    'populate_list' => array(
        'first_name' => 'first_name',
        'last_name' => 'last_name',
        
    ),
);
$dictionary["dot10_addresses"]["fields"]["leads_dot10_addresses_1_name"] = array(
    'name' => 'leads_dot10_addresses_1_name',
    'type' => 'relate',
    'source' => 'non-db',
    'vname' => 'LBL_LEADS_DOT10_ADDRESSES_1_FROM_LEADS_TITLE',
    'save' => true,
    'id_name' => 'leads_dot10_addresses_1leads_ida',
    'link' => 'leads_dot10_addresses_1',
    'table' => 'leads',
    'module' => 'Leads',
    'rname' => 'full_name',
    'populate_list' => array(
        'first_name' => 'first_name',
        'last_name' => 'last_name',
    ),
    'db_concat_fields' =>
    array(
        0 => 'first_name',
        1 => 'last_name',
    ),
);
$dictionary["dot10_addresses"]["fields"]["leads_dot10_addresses_1leads_ida"] = array(
    'name' => 'leads_dot10_addresses_1leads_ida',
    'type' => 'id',
    'source' => 'non-db',
    'vname' => 'LBL_LEADS_DOT10_ADDRESSES_1_FROM_DOT10_ADDRESSES_TITLE_ID',
    'id_name' => 'leads_dot10_addresses_1leads_ida',
    'link' => 'leads_dot10_addresses_1',
    'table' => 'leads',
    'module' => 'Leads',
    'rname' => 'id',
    'reportable' => false,
    'side' => 'right',
    'massupdate' => false,
    'duplicate_merge' => 'disabled',
    'hideacl' => true,
);
