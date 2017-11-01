<?php

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class CreatTask{

    function create($bean, $event, $arguments)
    {
      global $app_list_strings, $current_user, $timedate;
      if(!empty($_REQUEST['__sugar_url']) ){
          $check = explode('/', $_REQUEST['__sugar_url']);
          if($check[0] == "v10" && $check[1] == "Emails"){
             if(!empty($bean->parent_id) && ($bean->parent_type=="Leads" || $bean->parent_type == "Contacts")){
                   $task = BeanFactory::getBean("Tasks");
                   $task->name = "Outlook-Task";
                   $task->status = "open";
                   $dueDate = '';
                   if(!empty($bean->date_sent)){
                        $dueDate = new DateTime($bean->date_sent);
                        $dueDate = $dueDate->format('Y-m-d H:i:s');
                   }
                   else{
                       $dueDate = new DateTime($timedate->nowDb());
                       $dueDate->add(new DateInterval('P1D'));
                       $dueDate = $dueDate->format('Y-m-d');
                       $dueDate = $dueDate.' 00:00:00';
                   }
                   $task->date_due = $dueDate;
                   $task->parent_type = $bean->parent_type;
                   $task->parent_module = $bean->parent_type;
                   $task->parent_id = $bean->parent_id;
                   $task->assigned_user_id = $bean->modified_user_id;
                   $task->save();
				   
                    /*
                     * Linking Task to the Email
                     */
                    $task->load_relationship("emails");
                    $task->emails->add($bean->id);

                    /*
                     *  add relationship to lead or contact
                     */
                    if ($bean->parent_type == "Leads") {
						$task->load_relationship("leads");
						$task->leads->add($bean->parent_id);
                    } else if ($bean->parent_type == "Contacts") {
						$task->load_relationship("contacts");
						$task->contacts->add($bean->parent_id);
					}
           }

       }
   }
}
}
