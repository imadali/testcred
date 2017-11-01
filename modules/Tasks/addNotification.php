<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

require_once ('include/TimeDate.php');

class addNotification {

    public function addNotificationForCreditum($bean, $event, $arguments) {
        if (!empty($bean->assigned_user_id) && empty($bean->fetched_row)) {
            $creditum_users = array('aline.fereira', 'thomas.cavalarro', 'alexandre.bra');
            $user = new User();
            $user->retrieve($bean->assigned_user_id);
            if (in_array($user->user_name, $creditum_users)) {
                $notification_bean = BeanFactory::getBean("Notifications");
                $notification_bean->name = "Neue Pendenz";
                $notification_bean->description = "Neue Pendenz - Browser-Hinweis auf eine neu zugeteilte Aufgabe";
                $notification_bean->assigned_user_id = $bean->assigned_user_id;
                $notification_bean->parent_id = $bean->id;
                $notification_bean->parent_type = 'Tasks';
                $notification_bean->created_by = $bean->created_by;
                $notification_bean->is_read = 0;
                $notification_bean->severity = "alert";
                $notification_bean->save();
            }
        }
    }

}
