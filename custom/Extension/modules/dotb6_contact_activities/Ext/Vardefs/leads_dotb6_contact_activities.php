<?php
// created: 2016-02-26 15:00:22
$dictionary["dotb6_contact_activities"]["fields"]["leads_dotb6_contact_activities"] = array (
	'name' => 'leads_dotb6_contact_activities',
	'type' => 'link',
	'relationship' => 'leads_dotb6_contact_activities',
	'source' => 'non-db',
	'module' => 'Leads',
	'bean_name' => 'Leads',
	'vname' => 'LBL_LEADS_DOTB6_CONTACT_ACTIVITIES_FROM_LEADS_DOTB6_CONTACT_ACTIVITIES_TITLE',
	'id_name' => 'contact_id_c',
	'link-type' => 'many',
	'side' => 'right'
);
$dictionary["dotb6_contact_activities"]["relationships"]["leads_dotb6_contact_activities"] = array (
	'lhs_module' => 'Leads',
	'lhs_table' => 'leads',
	'lhs_key' => 'id',
	'rhs_module' => 'dotb6_contact_activities',
	'rhs_table' => 'dotb6_contact_activities',
	'rhs_key' => 'contact_id_c',
	'relationship_type' => 'one-to-many'
);

?>