<?php

array_push($job_strings, 'NewLeadListener');
array_push($job_strings, 'NewHourlyLeadListener');

function NewLeadListener() {
    global $sugar_config;
    $datetime = new DateTime(date("Y-m-d H:i"));
    $datetime->setTimezone(new DateTimeZone(date_default_timezone_get()));
    
    $currentTime = $datetime->format('H:i');
    $GLOBALS['log']->debug("Current Time : ".$currentTime);
    $lowerLimit = DateTime::createFromFormat('H\h i\m', '8h 00m')->format('H:i'); 
    $upperLimit = DateTime::createFromFormat('H\h i\m', '20h 00m')->format('H:i');

    if (strtotime($currentTime) > strtotime($lowerLimit) && strtotime($currentTime) <= strtotime($upperLimit)) {

        $lastLead = getLastLead();
        $GLOBALS['log']->debug("Last Lead Creation : ".$lastLead);
        $timeElapsed = (strtotime($datetime->format('Y-m-d H:i')) - strtotime($lastLead)) / 60;
        $time_gap = '';
        if (isset($sugar_config['listener_email_alert1'])) {
            $time_gap = $sugar_config['listener_email_alert1'];
            if ($timeElapsed >= $time_gap) {
                return sendEmail('bi-hourly');
            }
        }
    }

    return true;
}

function NewHourlyLeadListener() {
    global $sugar_config;
    $datetime = new DateTime(date("Y-m-d H:i"));
    $datetime->setTimezone(new DateTimeZone(date_default_timezone_get()));
    
    $time_gap = '';
    $currentTime = $datetime->format('Y-m-d H:i');
    $GLOBALS['log']->debug("Current Time : ".$currentTime);
    $lowerLimit = DateTime::createFromFormat('H\h i\m', '20h 00m')->format('H:i');
    $upperLimit = DateTime::createFromFormat('H\h i\m', '8h 00m')->format('H:i');
    $midnight = date("Y-m-d 00:00:00", strtotime('+1 day'));

    $lastLead = getLastLead();
    $GLOBALS['log']->debug("Last Lead Creation : ".$lastLead);
    
    if (strtotime($currentTime) > strtotime($lowerLimit) && strtotime($currentTime) <= strtotime($midnight)) {
        $timeElapsed = round((strtotime($currentTime) - strtotime($lastLead)) / 60);
        if (isset($sugar_config['listener_email_alert2'])) {
            $time_gap = $sugar_config['listener_email_alert2'];
            if ($timeElapsed >= $time_gap) {
                return sendEmail('hourly');
            }
        }
    }

    $midnight = date("Y-m-d 00:00:00");
    if (strtotime($currentTime) >= strtotime($midnight) && strtotime($currentTime) <= strtotime($upperLimit)) {
        $timeElapsed = round((strtotime($currentTime) - strtotime($lastLead)) / 60);
        if (isset($sugar_config['listener_email_alert2'])) {
            $time_gap = $sugar_config['listener_email_alert2'];
            if ($timeElapsed >= $time_gap) {
                return sendEmail('hourly');
            }
        }
    }

    return true;
}

function sendEmail($type) {
    global $sugar_config;
    $emailObj = new Email();
    $defaults = $emailObj->getSystemDefaultEmail();
    $mail = new SugarPHPMailer();
    $mail->setMailerForSystem();
    $mail->From = $defaults['email'];
    $mail->FromName = $defaults['name'];
    if ($type == 'hourly') {
        $mail->Subject = from_html('Sugar-Listener-Alert: no new Leads arrived during the last 4 hours. | ACTION REQUIRED!');
    } else if ($type == 'bi-hourly') {
        $mail->Subject = from_html('Sugar-Listener-Alert: no new Leads arrived during the last 30 minutes. | ACTION REQUIRED!');
    }

    $mail->Body = from_html();
    $mail->prepForOutbound();
    if (isset($sugar_config['lead_listener'])) {
        $recepient = $sugar_config['lead_listener'];
        $mail->AddAddress($recepient);
    }
    
    if ($mail->Send()) {
        return true;
    } else {
        return false;
    }
}

function getLastLead() {
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
