DROP VIEW IF EXISTS dotb6_contact_activities;
CREATE VIEW dotb6_contact_activities (
	id, 
	name, 
	date_entered, 
	date_modified, 
	modified_user_id, 
	created_by, 
	description, 
	deleted, 
	team_id, 
	team_set_id, 
	assigned_user_id,
	left_parent_type,
	left_parent_id,
	contact_id_c,
	parent_type,
	parent_id,
	`status`,
	dotb_type,
	date_start,
	date_end
)
AS 
	SELECT 
		c.id AS id, 
		c.name AS name, 
		c.date_entered AS date_entered, 
		c.date_modified AS date_modified, 
		c.modified_user_id AS modified_user_id, 
		c.created_by AS created_by, 
		c.description AS description, 
		c.deleted AS deleted, 
		c.team_id AS team_id, 
		c.team_set_id AS team_set_id, 
		c.assigned_user_id AS assigned_user_id,
		'Contacts' AS left_parent_type,
		c.parent_id AS left_parent_id,
		c.parent_id AS contact_id_c,
		'Calls' AS parent_type,
		c.id AS parent_id,
		c.`status` AS `status`,
		c.direction AS dotb_type,
		c.date_start AS date_start,
		c.date_end AS date_end
	FROM calls c
	INNER JOIN calls_contacts cc ON cc.call_id = c.id
	WHERE c.deleted = 0
		AND cc.deleted = 0
UNION
	SELECT 
		m.id AS id, 
		m.name AS name, 
		m.date_entered AS date_entered, 
		m.date_modified AS date_modified, 
		m.modified_user_id AS modified_user_id, 
		m.created_by AS created_by, 
		m.description AS description, 
		m.deleted AS deleted, 
		m.team_id AS team_id, 
		m.team_set_id AS team_set_id, 
		m.assigned_user_id AS assigned_user_id,
		'Contacts' AS left_parent_type,
		mc.contact_id AS left_parent_id,
		mc.contact_id AS contact_id_c,
		'Meetings' AS parent_type,
		m.id AS parent_id,
		m.`status` AS `status`,
		m.`type` AS dotb_type,
		m.date_start AS date_start,
		m.date_end AS date_end
	FROM meetings m
	INNER JOIN meetings_contacts mc ON mc.meeting_id=m.id
	WHERE m.deleted = 0
		AND mc.deleted = 0
UNION
	SELECT 
		t.id AS id, 
		t.name AS name, 
		t.date_entered AS date_entered, 
		t.date_modified AS date_modified, 
		t.modified_user_id AS modified_user_id, 
		t.created_by AS created_by, 
		t.description AS description, 
		t.deleted AS deleted, 
		t.team_id AS team_id, 
		t.team_set_id AS team_set_id, 
		t.assigned_user_id AS assigned_user_id,
		'Contacts' AS left_parent_type,
		t.parent_id AS left_parent_id,
		t.parent_id AS contact_id_c,
		'Tasks' AS parent_type,
		t.id AS parent_id,
		t.`status` AS `status`,
		t.`priority` AS dotb_type,
		t.date_start AS date_start,
		t.date_due AS date_end
	FROM tasks t
	WHERE deleted = 0
	AND parent_type = 'Contacts'
UNION
	SELECT 
		e.id AS id, 
		e.name AS name, 
		e.date_entered AS date_entered, 
		e.date_modified AS date_modified, 
		e.modified_user_id AS modified_user_id, 
		e.created_by AS created_by, 
		null AS description, 
		e.deleted AS deleted, 
		e.team_id AS team_id, 
		e.team_set_id AS team_set_id, 
		e.assigned_user_id AS assigned_user_id,
		'Contacts' AS left_parent_type,
		eb.bean_id AS left_parent_id,
		eb.bean_id AS contact_id_c,
		'Emails' AS parent_type,
		e.id AS parent_id,
		e.`status` AS `status`,
		e.`type` AS dotb_type,
		e.date_entered AS date_start,
		null AS date_end
	FROM emails e
	INNER JOIN emails_beans eb ON eb.email_id = e.id
	WHERE e.deleted = 0
	AND eb.deleted = 0
	AND eb.bean_module = 'Contacts'
UNION
	SELECT 
		n.id AS id, 
		n.name AS name, 
		n.date_entered AS date_entered, 
		n.date_modified AS date_modified, 
		n.modified_user_id AS modified_user_id, 
		n.created_by AS created_by, 
		n.description AS description, 
		n.deleted AS deleted, 
		n.team_id AS team_id, 
		n.team_set_id AS team_set_id, 
		n.assigned_user_id AS assigned_user_id,
		'Contacts' AS left_parent_type,
		n.contact_id AS left_parent_id,
		n.contact_id AS contact_id_c,
		'Notes' AS parent_type,
		n.id AS parent_id,
		null AS `status`,
		null AS dotb_type,
		n.date_entered AS date_start,
		null AS date_end
	FROM notes n
	WHERE deleted = 0
	AND n.contact_id is not null
;	