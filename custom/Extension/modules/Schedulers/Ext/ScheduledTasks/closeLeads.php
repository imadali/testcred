<?php

// add the job key to the list of job strings
array_push($job_strings, 'closeLeads');

function closeLeads() {
    global $timedate,$app_list_strings;
    $closing_date = new DateTime($timedate->nowDb());
    $closing_date = $closing_date->format('Y-m-d');
    $sql = "SELECT id_c from leads_cstm WHERE closing_date_c='$closing_date' AND credit_request_status_id_c ='00_pendent_geschlossen'";    
    $result = $GLOBALS["db"]->query($sql);
    
    while ($lead = $GLOBALS["db"]->fetchByAssoc($result)) {
        $leadBean = BeanFactory::getBean('Leads', $lead['id_c']);
        $leadBean->credit_request_status_id_c = '11_closed';
        $leadBean->processed = false;
        $leadBean->save();
    }
    return true;
}

?>