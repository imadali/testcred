<?php

if (!defined('sugarEntry'))
    define('sugarEntry', true);
// chdir(realpath(dirname(__FILE__)));
require_once('include/entryPoint.php');
global $timedate, $app_list_strings;
$sql = "SELECT id,parent_id,parent_type,customer_contact_user_id_c FROM tasks JOIN tasks_cstm ON id=id_c where deleted=0";
$result = $GLOBALS["db"]->query($sql);
echo '<pre>';
echo "Seting Customer Contact and Assigned User in Leads/Contact";
$count = 1;
$queries = '';
while ($task = $GLOBALS["db"]->fetchByAssoc($result)) {
    $customer_contact_user_id_c = trim($task['customer_contact_user_id_c']);
    $full_name = trim($task['customer_contact_user_id_c']);
    $id = $task['id'];
//if (!empty($customer_contact_user_id_c) && ($task['parent_type']=='Leads' || $task['parent_type']=='Contacts')) {
    if (!empty($full_name)) {
        echo '<br><br><br><br>';
        echo "Name: $full_name";
        if ($full_name == 'Test Developer' || $full_name == 'API User Comparis') {
            $user = BeanFactory::getBean('Users')->retrieve_by_string_fields(array('last_name' => $full_name));
        } else if ($full_name == 'Muhammad Tariq Ibrar') {
            $full_name = explode(" ", $full_name);
            $user = BeanFactory::getBean('Users')->retrieve_by_string_fields(array('first_name' => $full_name[0] . " " . $full_name[1], 'last_name' => $full_name[2]));
        } else if ($full_name == 'qa admin user') {
            $full_name = explode(" ", $full_name);
            $user = BeanFactory::getBean('Users')->retrieve_by_string_fields(array('first_name' => $full_name[0], 'last_name' => $full_name[1] . " " . $full_name[2]));
        } else if ($full_name == 'qa2 non admin user') {
            $full_name = explode(" ", $full_name);
            $user = BeanFactory::getBean('Users')->retrieve_by_string_fields(array('first_name' => $full_name[0], 'last_name' => $full_name[1] . " " . $full_name[2] . " " . $full_name[3]));
        } else {
            $full_name = explode(" ", $full_name);

            echo '<br>';
            print_r($full_name);
            $len = count($full_name);
            if (count($full_name) == 1) {
                $user = BeanFactory::getBean('Users')->retrieve_by_string_fields(array('last_name' => $full_name[0]));
            } else if (count($full_name) == 2) {
                $user = BeanFactory::getBean('Users')->retrieve_by_string_fields(array('first_name' => $full_name[0], 'last_name' => $full_name[1]));
            } else {
                $user = BeanFactory::getBean('Users')->retrieve_by_string_fields(array('first_name' => $full_name[0], 'last_name' => $full_name[1] . " " . $full_name[2]));
            }
        }
        echo "<br>$count)Words:$len  Task Id: $id, Customer: $customer_contact_user_id_c, Customer ID: $user->id";
        $GLOBALS['db']->query("UPDATE tasks SET customer_contact_id='$user->id' WHERE id='$id'");
        $count++;
    }
}
echo "<br> $count records were updated";
exit;
