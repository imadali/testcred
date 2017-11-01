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
        if ($bean_mod->field_name_map[$field_name] && isset($bean_mod->field_name_map[$field_name]['audited']) && $bean_mod->field_name_map[$field_name]['audited']) {
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

?>