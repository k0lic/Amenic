<?php namespace App\Helpers;

/*
    Author: Miloš Živkovic
    Github: zivkovicmilos
*/

use Exception;
use PHPMailer\PHPMailer\PHPMailer;

if(!function_exists('sendMail')) {

    function sendMail($to, $subject, $content) {

        $mail = new PHPMailer(true);
        
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = amenicEmail;
        $mail->Password = amenicPassword;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom(amenicEmail, 'Amenic');
        $mail->addAddress($to);

        $mail->Subject = $subject;
        $mail->isHTML(true);

        $mail->Body = $content;

        try {
            $mail->send();
        } catch (Exception $e) {
            //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }

        return true;
    }

}