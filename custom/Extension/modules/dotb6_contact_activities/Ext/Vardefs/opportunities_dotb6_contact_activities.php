<?php
// created: 2016-02-26 15:00:22
$dictionary["dotb6_contact_activities"]["fields"]["opportunities_dotb6_contact_activities"] = array (
	'name' => 'opportunities_dotb6_contact_activities',
	'type' => 'link',
	'relationship' => 'opportunities_dotb6_contact_activities',
	'source' => 'non-db',
	'module' => 'Opportunities',
	'bean_name' => 'Opportunities',
	'vname' => 'LBL_OPP_DOTB6_CONTACT_ACTIVITIES_FROM_OPP_DOTB6_CONTACT_ACTIVITIES_TITLE',
	'id_name' => 'contact_id_c',
	'link-type' => 'many',
	'side' => 'right'
);
$dictionary["dotb6_contact_activities"]["relationships"]["opportunities_dotb6_contact_activities"] = array (
	'lhs_module' => 'Opportunities',
	'lhs_table' => 'opportunities',
	'lhs_key' => 'id',
	'rhs_module' => 'dotb6_contact_activities',
	'rhs_table' => 'dotb6_contact_activities',
	'rhs_key' => 'contact_id_c',
	'relationship_type' => 'one-to-many'
);

?>