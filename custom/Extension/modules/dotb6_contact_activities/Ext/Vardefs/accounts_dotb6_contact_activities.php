<?php
// created: 2016-02-26 15:00:22
$dictionary["dotb6_contact_activities"]["fields"]["accounts_dotb6_contact_activities"] = array (
	'name' => 'accounts_dotb6_contact_activities',
	'type' => 'link',
	'relationship' => 'accounts_dotb6_contact_activities',
	'source' => 'non-db',
	'module' => 'Accounts',
	'bean_name' => 'Accounts',
	'vname' => 'LBL_ACCOUNTS_DOTB6_CONTACT_ACTIVITIES_FROM_ACCOUNTS_DOTB6_CONTACT_ACTIVITIES_TITLE',
	'id_name' => 'contact_id_c',
	'link-type' => 'many',
	'side' => 'right'
);
$dictionary["dotb6_contact_activities"]["relationships"]["accounts_dotb6_contact_activities"] = array (
	'lhs_module' => 'Accounts',
	'lhs_table' => 'accounts',
	'lhs_key' => 'id',
	'rhs_module' => 'dotb6_contact_activities',
	'rhs_table' => 'dotb6_contact_activities',
	'rhs_key' => 'contact_id_c',
	'relationship_type' => 'one-to-many'
);

?>