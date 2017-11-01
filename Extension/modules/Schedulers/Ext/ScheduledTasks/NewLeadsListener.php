<?php

array_push($job_strings, 'newLeadListener');

/**
 * CRED-804 : Adjustment of listener for ingoing leads
 * 
 * @global type $sugar_config
 * @return boolean
 */
function newLeadListener()
{

    global $sugar_config;
    $config = '';
    $configToCheck = null;
    
    $query = 'SELECT name, value FROM config WHERE name = "lead_listener"';
    $result = $GLOBALS['db']->query($query);

    $row = $GLOBALS['db']->fetchByAssoc($result);
    $config = json_decode($row['value']);
    
    $datetime = new DateTime(date("Y-m-d H:i"));
    $datetime->setTimezone(new DateTimeZone(date_default_timezone_get()));
    
    $today = strtolower(date('l', strtotime($datetime->format('Y-m-d H:i:s'))));
  
    foreach ($config as $con) {
        if (isset($con->$today) && !empty($con->$today)) {
            $configToCheck = $con->$today;
            $GLOBALS['log']->debug(print_r($configToCheck, 1));
            break;
        }
    }

    foreach ($configToCheck as $configs) {
        if ($configs->enable == 1) {
            $currentTime = $datetime->format('H:i');
            $GLOBALS['log']->debug("Current Time : " . $currentTime);

            $to = explode("_", $configs->to);
            $from = explode("_", $configs->from);
            $lowerLimit = DateTime::createFromFormat('H\h i\m', "$from[0]h $from[1]m")->format('H:i');
            $upperLimit = DateTime::createFromFormat('H\h i\m', "$to[0]h $to[1]m")->format('H:i');
            
            if (strtotime($currentTime) > strtotime($lowerLimit) && strtotime($currentTime) <= strtotime($upperLimit)) {
                $emails = array();
                if (strpos($configs->email, ';') !== false) {
                    $emails = explode(";", $configs->email);
                } else {
                    $emails[] = $configs->email;
                }

                $lastLead = getLastLead();
                $GLOBALS['log']->debug("Last Lead Creation : " . $lastLead);
                $timeElapsed = (strtotime($datetime->format('Y-m-d H:i')) - strtotime($lastLead)) / 60;
                $GLOBALS['log']->debug("Time Elapsed : " . $timeElapsed . " minutes");
                if (!empty($emails)) {
                    if ($timeElapsed >= 60) {
                        return sendEmail($emails);
                    }
                }
            }
        }
    }
    
    return true;
}

function sendEmail($emails)
{
    global $sugar_config;
    $emailObj = new Email();
    $defaults = $emailObj->getSystemDefaultEmail();
    $mail = new SugarPHPMailer();
    $mail->setMailerForSystem();
    $mail->From = $defaults['email'];
    $mail->FromName = $defaults['name'];
    $mail->Subject = from_html('Sugar-Listener-Alert: no new Leads arrived during the last hour. | ACTION REQUIRED!');

    $mail->Body = from_html();
    $mail->prepForOutbound();
    
    if (!empty($emails)) {
        foreach ($emails as $recepient) {
            $mail->AddAddress($recepient);
        }

        if ($mail->Send()) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function getLastLead()
{
    $query = "SELECT id,first_name,last_name,date_entered FROM leads WHERE deleted = 0 ORDER BY date_entered DESC LIMIT 0,1";
    $result = $GLOBALS['db']->query($query);
    $row = $GLOBALS['db']->fetchByAssoc($result);
    $lastLead = date('Y-m-d H:i:s +00:00', strtotime($row['date_entered']));
    $GLOBALS['log']->debug("Lead GMT Time  : ".$lastLead);
    $datetime = new DateTime($lastLead);
    $datetime->setTimezone(new DateTimeZone(date_default_timezone_get()));
    $lastLead = $datetime->format('Y-m-d H:i:s');
    return $lastLead;
}

?>