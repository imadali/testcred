<?php

// add the job key to the list of job strings
array_push($job_strings, 'closeLeads');

function closeLeads() 
{
    global $timedate,$app_list_strings;
    $closing_date = new DateTime($timedate->nowDb());
    $closing_date = $closing_date->format('Y-m-d');
    /**
     * CRED-1024 : Added check for deleted
     */
    $sql = "SELECT l.id FROM leads l LEFT JOIN leads_cstm lcstm ON l.id = lcstm.id_c WHERE lcstm.closing_date_c='$closing_date' AND lcstm.credit_request_status_id_c ='00_pendent_geschlossen' AND l.deleted=0";
    $result = $GLOBALS["db"]->query($sql);
    
    while ($lead = $GLOBALS["db"]->fetchByAssoc($result)) {
        $leadBean = BeanFactory::getBean('Leads', $lead['id']);
        $leadBean->credit_request_status_id_c = '11_closed';
        $leadBean->processed = false;
        $leadBean->save();
    }
    return true;
}

?>