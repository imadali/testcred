<?php

/**
* CRED-758 : Lead Initial Scoring
*/
array_push($job_strings, 'leadInitialScoring');

function leadInitialScoring() {
    global $db;
    // get all the leads that have one entry for delta vista and score is empty
    $lead_query = 'SELECT leads_audit.parent_id, COUNT(leads_audit.field_name) AS deltaVistaCount FROM leads_audit'.
                    ' LEFT JOIN leads ON leads.id = leads_audit.parent_id'.
                    ' WHERE leads_audit.field_name = "dotb_deltavista_response_c" AND leads_audit.deleted=0 AND leads.deleted=0 AND (leads.initial_score = "" OR leads.initial_score IS NULL) AND leads.date_entered > "2017-08-01" GROUP BY leads_audit.parent_id HAVING deltaVistaCount=1';
    $leads_result = $db->query($lead_query);
    $leads_to_be_scored = array();
    while ($lead_row = $db->fetchByAssoc($leads_result)) {
        $leads_to_be_scored[] = $lead_row['parent_id'];
    }

    $leadsIds = implode ('","', $leads_to_be_scored);
    $leadsIds = '"' . $leadsIds . '"';
    $GLOBALS['log']->debug("Leads to be scored: " . $leadsIds);
    //get lead scoring
    $leads_scored = getLeadScoring($leadsIds);
    while ($scored_lead = $db->fetchByAssoc($leads_scored)) {
        $update_lead_query = 'UPDATE leads SET initial_score = "'.$scored_lead['probability'].'" WHERE id="'.$scored_lead['id'].'" AND deleted=0';
        $GLOBALS['log']->debug("Leads update query: " . $update_lead_query);
        $db->query($update_lead_query);
        insertRecordInAuditTable('Leads', 'initial_score', '', $scored_lead['probability'], $scored_lead['id'], 'decimal');
    }

    return true;
}
