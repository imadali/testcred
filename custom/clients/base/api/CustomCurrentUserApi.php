<?php    
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');    
require_once("clients/base/api/CurrentUserApi.php");    
class CustomCurrentUserApi extends CurrentUserApi    
{    
   public function registerApiRest()    
   {    
       return parent::registerApiRest();    
   }
   
   public function retrieveCurrentUser($api, $args)    
   {    
       $data = parent::retrieveCurrentUser($api, $args);    
       $startTime = '';
       $endTime = '';
       $userId = '';
       $check_user = false;
       $start_time_values = array();
       $end_time_values = array();
       global $sugar_config;
  
       $userData = $this->getUserData($api->platform, $args);
       $userId = $userData['current_user']['id'];
       $userBean = BeanFactory::getBean("Users", $userId);
       $check_user = $userBean->check_employee_timings_c;
       $startTime = $userBean->employee_start_time_c;
       $endTime = $userBean->employee_end_time_c;
      
       if($check_user){
          if(isset($startTime) && isset($endTime) && !empty($startTime) && !empty($endTime) ){
             
               $start_time_values = explode("_", $startTime);
               $end_time_values = explode("_", $endTime);
               $startTime = DateTime::createFromFormat('H\h i\m', $start_time_values[0].'h '.$start_time_values[1].'m')->format('H:i');
               $endTime = DateTime::createFromFormat('H\h i\m', $end_time_values[0].'h '.$end_time_values[1].'m')->format('H:i');
               if(strtotime(date('H:i')) > strtotime($startTime) && strtotime(date('H:i')) < strtotime($endTime)){              
                 throw new SugarApiExceptionError('LBL_NO_LOGIN_MESSAGE');
               }
          }
          
       }
                       
       return $data;    
   }    
} 