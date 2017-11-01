<?php

/**
 * alternate to array_column function
 * 
 */
function valuelist($array, $array_column) {
    $return = array();
    foreach ($array AS $row) {
        $return[] = $row[$array_column];
    };
    return $return;
}

;

/**
 * gives value for salutation using the language and gender field
 * 
 */
function getSalutationText($language, $gender, $salutation) {
    global $app_list_strings;
    $langMap = array(
        'en' => 'en_us',
        'fr' => 'fr_FR',
        'it' => 'it_it',
        'de' => 'de_DE',
    );
    $no_gender = array(
        'de' => 'Guten Tag',
        'fr' => 'Bonjour,',
        'it' => 'Buongiorno,',
        'en' => 'Hello,'
    );
    if (!empty($language)) {
        if (empty($gender)) {
            return $no_gender[$language];
        } else {
            return $app_list_strings['dependent_salutation_dom'][$salutation];
        }
    }
    return '';
}

/*
 * @Three paramerers
 * 1) Module Name
 * 2) Module Id
 * 3) Team Type: primary or secondary
 * @Return: Team Set
 */

function getModuleTeams($moduleName, $moduleId, $team) {
    /*
     * Inhereting teams from parent
     */
    if (empty($moduleName) || empty($moduleId))
        return '';
    require_once('modules/Teams/TeamSet.php');
    $teamSetBean = new TeamSet();
    $primaryTeam = '';
    $parent = BeanFactory::getBean($moduleName, $moduleId);
    if ($team == 'primary') {
        return $parent->team_id;
    } else {
        $team_id = array();
        $teams = $teamSetBean->getTeams($parent->team_set_id);
        foreach ($teams as $key => $team) {
            $team_id[] = $key;
        }
        if (empty($team_id))
            $team_id[] = $parent->team_id;

        /*
         * Adding Global team in Task
         */
        $team_id[] = '1';
        return $team_id;
    }
}

/*
 * If lead is related to a contract (1:1) then the system will take the Bank value from contract
 * If the there is no related contract but one application in the Lead then the system will take the Bank value from this application.
 * If there are several related Applications then the system will alert the user to set Bank Name
 */
function getTaskBank($task_lead_id) {
    $bank = '';
    $leadObj = BeanFactory::getBean("Leads", $task_lead_id);
    if ($leadObj->load_relationship('contracts_leads_1')) {
        $relatedContracts = $leadObj->contracts_leads_1->getBeans();
        foreach ($relatedContracts as $contract) {
            $bank = $contract->provider_id_c;
        }
    }
    if (empty($bank)) {
        if ($leadObj->load_relationship('leads_opportunities_1')) {
            $relatedApplications = $leadObj->leads_opportunities_1->getBeans();
            $app_count = 0;
            $app_provider_id_c = '';
            foreach ($relatedApplications as $application) {
                $app_count++;
                $app_provider_id_c = $application->provider_id_c;
            }
            if ($app_count == 1) {
                $bank = $app_provider_id_c;
            }
        }
    }
    return $bank;
}

function insertRecordInAuditTable($module_name, $field_name, $old_val, $new_val, $parent_id, $data_type) {
    if ($old_val != $new_val) {
        global $current_user, $timedate;
        $CurrenrDateTime = $timedate->getInstance()->nowDb();
        $date = date('Y-m-d H:i:s', strtotime($CurrenrDateTime));
        $audit_id = create_guid();
        $bean_mod = BeanFactory::getBean($module_name);
        $table_name = $bean_mod->table_name;
        if ($bean_mod->field_defs[$field_name] && isset($bean_mod->field_defs[$field_name]['audited']) && $bean_mod->field_defs[$field_name]['audited']) {
            $audit_insert = 'INSERT INTO ' . $table_name . '_audit' . '
                            (id, parent_id, date_created, data_type, created_by, field_name, before_value_string, after_value_string) 
                            VALUES 
                            ("' . $audit_id . '", "' . $parent_id . '","' . $date . '","' . $data_type . '", "' . $current_user->id . '", "' . $field_name . '","' . $old_val . '","' . $new_val . '")';
            $GLOBALS['db']->query($audit_insert);
        }
        // For updating date_modified and Modified By user in Original Table
        $original_date_update = 'UPDATE  ' . $table_name . ' SET date_modified = "' . $date . '", modified_user_id = "' . $current_user->id . '" WHERE deleted = 0 AND id = "' . $parent_id . '" ';
        $GLOBALS['db']->query($original_date_update);
    }
}

/**
* CRED-758 : SQL for lead scoring
*/
function getLeadScoring($leadIds){
    global $db;
    $scoring_query = 'SELECT'.  
                    ' id,'.
                    ' var_1,'.
                    ' var_2,'.
                    ' var_3,'.
                    ' var_4,'.
                    ' var_5,'.
                    ' var_6,'.
                    ' var_7,'.
                    ' -3.197157, /*constant out of modell*/'.
                    ' var_1+var_2+var_3+var_4+var_5+var_6+var_7+-3.197157 as sum1,'.
                    ' exp(var_1+var_2+var_3+var_4+var_5+var_6+var_7+-3.197157) /(1+ exp(var_1+var_2+var_3+var_4+var_5+var_6+var_7+-3.197157)) probability'.
                    ' FROM'. 
                    ' (SELECT l.id,'.
                        ' l_cstm.deltavista_score_c,'.
                            ' case'.
                                ' when l_cstm.deltavista_score_c is null then 0'.
                                ' when l_cstm.deltavista_score_c <=349 then -4.095'.
                                ' when l_cstm.deltavista_score_c <=399 then -0.755'.
                                ' when l_cstm.deltavista_score_c <=449 then 0.128'.
                                ' when l_cstm.deltavista_score_c <=499 then 0.468'.
                                ' when l_cstm.deltavista_score_c <=549 then 0.721'.
                            ' else'.
                                ' 0.833'.
                            ' end'.
                            ' as var_1,'.
                            ' l_cstm.input_process_type_id_c,'.
                            ' l_cstm.contact_type_option_id_c,'.
                            ' case '.
                                ' when l_cstm.input_process_type_id_c is null then 0'.
                                ' when l_cstm.input_process_type_id_c in  ("car_finder",  "moto_finder") then -1.911'.
                                ' when l_cstm.input_process_type_id_c ="refinance" then -1.348'.
                                ' /*credaris case in several steps to be sure about null handling*/'.
                                ' when l_cstm.input_process_type_id_c = "credaris" and l_cstm.contact_type_option_id_c is null then -1.648 '.
                                ' when l_cstm.input_process_type_id_c = "credaris" and l_cstm.contact_type_option_id_c =  "user_requested_offer_mobile" then -2.094'.
                                ' when l_cstm.input_process_type_id_c = "credaris" and l_cstm.contact_type_option_id_c <>"user_requested_offer_mobile" then -1.648'.
                            ' else'.
                                ' 0'.
                            ' end'.  
                                ' as var_2,'.
                            ' l_cstm.dotb_iso_nationality_code_c,'.
                            ' case'. 
                                ' when l_cstm.dotb_iso_nationality_code_c is null then 0'.
                                ' when l_cstm.dotb_iso_nationality_code_c = "Ch" then 0.295'.
                                ' when l_cstm.dotb_iso_nationality_code_c = "De" then 0.547'.
                                ' when l_cstm.dotb_iso_nationality_code_c = "Fr" then 0.355'.
                            ' else'.
                            ' 0'.
                            ' end as var_3,'.
                            ' l_cstm.no_of_dependent_children_c,'.
                            ' case'.
                                ' when l_cstm.no_of_dependent_children_c is null then 0'.
                                ' when l_cstm.no_of_dependent_children_c = 0 then 1.050'.
                                ' when l_cstm.no_of_dependent_children_c = 1 then 0.759'.
                                ' when l_cstm.no_of_dependent_children_c = 2 then 0.580'.
                                ' when l_cstm.no_of_dependent_children_c >=3 then 0.630'.
                            ' else'.
                            ' 0'.
                            ' end as var_4,'.
                            ' l_cstm.dotb_housing_costs_rent_c,'.
                            ' l_cstm.dotb_monthly_gross_income_c,'.
                            ' l_cstm.credit_amount_c,'.
                            ' case'.
                                ' when l_cstm.dotb_monthly_gross_income_c is null or l_cstm.dotb_housing_costs_rent_c is null then 0'.
                                ' when l_cstm.dotb_housing_costs_rent_c <= 0 then 1.768'.
                                ' when l_cstm.credit_amount_c / (l_cstm.dotb_monthly_gross_income_c- l_cstm.dotb_housing_costs_rent_c) <=1.99 then 2.268'.
                                ' when l_cstm.credit_amount_c / (l_cstm.dotb_monthly_gross_income_c- l_cstm.dotb_housing_costs_rent_c) <=2.99 then 2.241'.
                                ' when l_cstm.credit_amount_c / (l_cstm.dotb_monthly_gross_income_c- l_cstm.dotb_housing_costs_rent_c) <=3.99 then 2.230'.
                                ' when l_cstm.credit_amount_c / (l_cstm.dotb_monthly_gross_income_c- l_cstm.dotb_housing_costs_rent_c) <=4.99 then 2.073'.
                                ' when l_cstm.credit_amount_c / (l_cstm.dotb_monthly_gross_income_c- l_cstm.dotb_housing_costs_rent_c) <=5.99 then 1.903'.
                            ' else'.
                                ' 1.630'.
                            ' end var_5,'.
                            ' l_cstm.credit_usage_type_id_c,'.
                            ' case'.
                                ' when l_cstm.credit_usage_type_id_c is null then 0'.
                                ' when l_cstm.credit_usage_type_id_c = "Furniture" then 0.611'.
                                ' when l_cstm.credit_usage_type_id_c = "Others" then 0.868'.
                                ' else'. 
                                ' 0'.
                                ' end var_6,'.
                            ' l_cstm.dotb_monthly_gross_income_c as income_border,'.
                            ' case'.
                                ' when l_cstm.dotb_monthly_gross_income_c is null then 0'.
                                ' when l_cstm.dotb_monthly_gross_income_c < 3000 then -2.047606'.
                            ' else'.
                                ' 0'.
                            ' end var_7'.
                            ' FROM leads l LEFT JOIN'.
                            ' leads_cstm l_cstm ON l.id = l_cstm.id_c'.  
                            ' WHERE deleted=0'.  
                            ' and l.date_entered >= "2016-07-01 11:33:09"'.
                            ' and l.id in ('.$leadIds.')'.
                            ' ORDER BY l.date_entered'.
                            ' DESC /*LIMIT 10000 OFFSET 0*/'.
                ' ) basis';
    $GLOBALS['log']->debug("Scoring query: " . $scoring_query);
    $leads_score_result = $db->query($scoring_query);
    return $leads_score_result;
}

?>