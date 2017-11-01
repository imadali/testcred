<?php    
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');    
require_once("clients/base/api/CurrentUserApi.php");    
class CustomCurrentUserApi extends CurrentUserApi    
{    
   public function registerApiRest()    
   {    
       return parent::registerApiRest();    
   }
   
   public function retrieveCurrentUser($api, $args) {
        $data = parent::retrieveCurrentUser($api, $args);

        $start_time_values = array();
        $end_time_values = array();
        $datetime = new DateTime(date("Y-m-d H:i"));
        $datetime->setTimezone(new DateTimeZone(date_default_timezone_get()));

        $userData = $this->getUserData($api->platform, $args);
        $userId = $userData['current_user']['id'];
        $userBean = BeanFactory::getBean("Users", $userId);
        $check_user = $userBean->check_employee_timings_c;
        $startTime = $userBean->employee_start_time_c;
        $endTime = $userBean->employee_end_time_c;

        if ($check_user) {
            if (isset($startTime) && isset($endTime) && !empty($startTime) && !empty($endTime)) {

                $start_time_values = explode("_", $startTime);
                $startTime = DateTime::createFromFormat('!H:i', "$start_time_values[0]:$start_time_values[1]");
                if ($endTime == '00_00') {
                    $endTime = DateTime::createFromFormat('!H:i', '23:59');
                } else {
                    $end_time_values = explode("_", $endTime);
                    $endTime = DateTime::createFromFormat('!H:i', "$end_time_values[0]:$end_time_values[1]");
                }
                $currentTime = DateTime::createFromFormat('!H:i', $datetime->format('H:i'));

                if ($startTime > $endTime || $startTime == $endTime) {
                    $endTime->modify('+1 day');
                }

                if (($startTime <= $currentTime && $currentTime <= $endTime) || ($startTime <= $currentTime->modify('+1 day') && $currentTime <= $endTime)) {
                    throw new SugarApiExceptionError('LBL_NO_LOGIN_MESSAGE');
                }
            }
        }

        return $data;
    }

} 