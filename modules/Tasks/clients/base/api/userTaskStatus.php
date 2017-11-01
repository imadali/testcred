<?php

require_once('include/SugarQuery/SugarQuery.php');

class userTaskStatus extends SugarApi {

    /**
     * @function registerApiRest
     * @description registering the API call to make project status field read-only or not
     * @return type
     */
    public function registerApiRest() {
        return array(
            'getTaskStatus' => array(
                'reqType' => 'GET',
                'path' => array('Tasks', 'TaskStatus','?'),
                'pathVars' => array('', '', 'param'),
                'method' => 'getTaskStatus',
                'shortHelp' => 'This api will return the missing status of tasks for a User',
                'longHelp' => '',
            ),
        );
    }
    
    public function getTaskStatus(ServiceBase $api, array $args) {
        //task_history_duration_list(DropDown)
        global $current_user;
        $param = $args['param'];
        $tasks =  array();
        
        $seed = BeanFactory::newBean('Tasks');
        $q = new SugarQuery();
        
        $from = "";
        $to = "";
        
        $duration = array("today","yesterday","this_week","last_week","this_month","last_month");

        if($param == $duration[0]){
            $from = date("Y-m-d")." 00:00:00";
            $to = date("Y-m-d")." 23:59:59";
        }
        else if($param == $duration[1]){
            $from = date("Y-m-d", time() - 60 * 60 * 24)." 00:00:00";
            $to = date("Y-m-d", time() - 60 * 60 * 24)." 23:59:59";
        }
        else if($param == $duration[2]){
            $from = date("Y-m-d", strtotime('previous monday'))." 00:00:00";
            $to = date("Y-m-d", strtotime('next friday'))." 23:59:59";
        }
        else if($param == $duration[3]){
            $from = date("Y-m-d", strtotime("1 week ago", strtotime('previous monday')))." 00:00:00";
            $to = date("Y-m-d", strtotime("1 week ago", strtotime('next friday')))." 23:59:59";
        }
        else if($param == $duration[4]){
            $from = date('Y-m-01')." 00:00:00";
            $to = date('Y-m-t')." 23:59:59";
        }
        else if($param == $duration[5]){
            $prev_month_start = new DateTime("first day of last month");
            $prev_month_end = new DateTime("last day of last month");

            $from = $prev_month_start ->format('Y-m-d')." 00:00:00";
            $to = $prev_month_end->format('Y-m-d')." 23:59:59";
        }
        
        if($from == "" && $to == ""){
            $sql = "tasks.assigned_user_id = '$current_user->id'";
        }
        else{
            $sql = "tasks.assigned_user_id = '$current_user->id' AND date_modified BETWEEN '$from' AND '$to'";
        }
        
        $q->from($seed);
        $q->select('tasks.id');
        $q->select('tasks.name');
        $q->select('tasks.date_modified');
        $q->select('tasks.status');
        $q->whereRaw($sql);

      
        $taskRecords = array();
        $taskRecords = $q->execute();
        $total_count = count($taskRecords);
        $open_count = 0;
        $closed_count = 0;
        
        $closed_tasks = array();
        $open_tasks = array();
        foreach($taskRecords as $key => $value){
            if($value['status'] == "open"){
                $open_tasks[] = $value;
                $open_count +=1;
            }
            else if($value['status'] == "closed"){
                $closed_tasks[] = $value;
                $closed_count +=1;
            }
        }

        $tasks['total_tasks'] = $taskRecords;
        $tasks['open_tasks'] = $open_tasks;
        $tasks['closed_tasks'] = $closed_tasks;
        $tasks['total_count'] = $total_count;
        $tasks['open_count'] = $open_count;
        $tasks['closed_count'] = $closed_count;
        
        return $tasks;
    }
}
