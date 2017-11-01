<?php

class Leads_tasksApi extends SugarApi {

    public function registerApiRest() {
        return array(
            'quoteCall' => array(
                'reqType' => 'GET',
                'path' => array('getRelatedTasks'),
                'method' => 'getTasks',
                'shortHelp' => '',
                'longHelp' => '',
            ),
        );
    }

    public function getTasks($api, $args) {
        global $db, $current_user,$app_list_strings,$current_user;
        $status = $app_list_strings["dotb_credit_request_status_list"];
        $orderBy = "";

        if (isset($args['sort']) && $args['sort'] == 'status') {
            $orderBy = 'ORDER BY  `lead_status` ' . $args['order'];
            $orderByCall = 'status';
            $orderByIn = $args['order'];
        } else if (isset($args['sort']) && $args['sort'] == 'assign_to_name') {
            $orderBy = 'ORDER BY  `assigned_user_name` ' . $args['order'];
            $orderByCall = 'assignTo';
            $orderByIn = $args['order'];
        } else if (isset($args['sort']) && $args['sort'] == 'Oldest') {
            $orderBy = 'ORDER BY  `Oldest` ' . $args['order'];
            $orderByCall = 'date';
            $orderByIn = $args['order'];
        } else {
            $orderBy = 'ORDER BY  `lead_status` asc';
            $orderByCall = 'status';
            $orderByIn = 'asc';
        }

  $user_id = $current_user->id;
  $objTeams = new Team();
  $teams = $objTeams->get_teams_for_user($user_id);
       $teams_id=array();
        foreach ($teams as $team) {
           $teams_id[]=$team->id;
        }
        $teams_id = join('", "', $teams_id);
        $teams_id='"'.$teams_id.'"';
        $cur_qry = 'SELECT `l`.`id`,`lc`.`cstm_last_name_c`,`lc`.`credit_request_status_id_c` AS `lead_status`,
        u.id AS `assigned_user_id`,
        MIN(`t`.`date_due`) AS `Oldest` FROM `leads` AS `l`
        INNER JOIN `users` AS `u` ON `l`.`assigned_user_id`=`u`.`id`AND `l`.`deleted`=0
        LEFT JOIN `tasks` AS `t` ON `t`.`parent_id`=`l`.`id`
        LEFT JOIN leads_cstm AS lc ON l.id = lc.id_c
        WHERE u.`id` = "'.$current_user->id.'" OR `l`.`team_id` IN ('."$teams_id".')
        AND l.deleted = 0
        GROUP BY `l`.`id`  '.$orderBy;
        
        
        $cur_res = $db->query($cur_qry);
        if (!empty($cur_res)) {

            $array_of_results = array();
            $timeDate = new TimeDate();

            while ($singleLead = $db->fetchByAssoc($cur_res)) {

                $lead_id = $singleLead['id'];
                $lead_detail['lead_id'] = $lead_id;
                $lead_detail['lead_name'] = $singleLead['cstm_last_name_c'];
                
                if (isset($status[$singleLead['lead_status']])) { 
                    $lead_detail['lead_status'] = $status[$singleLead['lead_status']];
                }
                else{
                    $lead_detail['lead_status'] = "";
                }
                
                $userBean = BeanFactory::getBean("Users", $singleLead['assigned_user_id']);
                
                $lead_detail['lead_assign_to_name'] = $userBean->first_name." ".$userBean->last_name;
                $older_task_date = $singleLead['Oldest'];
                $lead_detail['last_open_task'] = $timeDate->to_display_date_time($older_task_date, true, true, $current_user);

                $related_tasks_query = "SELECT `t`.`id`,`t`.`name`,`t`.`parent_id`,`t`.`status`,`t`.`assigned_user_id`,`t`.`date_due`,u.id AS `user_id` FROM `tasks` AS `t`
                INNER JOIN `users` AS `u` ON `t`.`assigned_user_id`=`u`.`id`AND `t`.`deleted`=0  AND  `t`.`parent_id` =  '$lead_id'";
                $related_tasks_query_exe = $db->query($related_tasks_query);
                $tasks_related = array();
                if (!empty($related_tasks_query_exe)) {

                    while ($allRelatedTasks = $db->fetchByAssoc($related_tasks_query_exe)) {//$status[$allRelatedTasks['status']];

                        $name['id'] = $allRelatedTasks['id'];
                        $name['name'] = $allRelatedTasks['name'];
                        $name['parent_id'] = $allRelatedTasks['parent_id'];
                        $name['status'] = $allRelatedTasks['status'];
                        
                        $userBeanTask = BeanFactory::getBean("Users", $allRelatedTasks['user_id']);
                        $name['assigned_user_id'] = $allRelatedTasks['user_id'];
                        $name['assigned_user_name'] = $userBeanTask->first_name." ".$userBeanTask->last_name;
                        $name['date_due'] = $timeDate->to_display_date_time($allRelatedTasks['date_due'], true, true, $current_user);
                        $tasks_related[] = $name;
                    }
                    $lead_detail['related_tasks'] = $tasks_related;
                    $array_of_results[] = $lead_detail;
                }
            }
            
            $arrayStatus = array();
            $final_response_array = array();

            $arrayStatus['order_by'] = $orderByCall;
            $arrayStatus['order_in'] = $orderByIn;
            $final_response_array['leads_response'] = $array_of_results;
            $final_response_array['status_rsponse'] = $arrayStatus;
            return $final_response_array;
        }
    }

}
