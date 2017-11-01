<?php

array_push($job_strings, 'activityNotificationJob');

function activityNotificationJob($job)
{
    if (!empty($job->data)) {

        $data = json_decode($job->data);

        $emailObj = new Email();
        $defaults = $emailObj->getSystemDefaultEmail();
        $mail = new SugarPHPMailer();
        $mail->setMailerForSystem();
        $mail->From = $defaults['email'];
        $mail->FromName = $defaults['name'];
        $mail->Subject = htmlspecialchars_decode($data->subject);
        $mail->Body = htmlspecialchars_decode($data->message);
        $mail->isHTML(true);
        $mail->prepForOutbound();

        foreach ($data->email as $address) {
            $mail->AddAddress($address);
        }

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