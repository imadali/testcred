<?php

array_push($job_strings, 'ActivityNotificationJob');

function ActivityNotificationJob($job) {
    if (!empty($job->data)) {

        $data = json_decode($job->data);

        $emailObj = new Email();
        $defaults = $emailObj->getSystemDefaultEmail();
        $mail = new SugarPHPMailer();
        $mail->setMailerForSystem();
        $mail->From = $defaults['email'];
        $mail->FromName = $defaults['name'];
        $mail->Subject = from_html($data->subject);
        $mail->Body = from_html($data->message);
        $mail->prepForOutbound();
        $mail->AddAddress($data->email);

        if ($mail->Send()) {
            return true;
        } else {
            return false;
        }
    } else {
        return true;
    }
}

?>