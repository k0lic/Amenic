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

if (!function_exists("sendMailOnCinemaClose"))
{
    /**
     *  Sends mail to a cinema account that was just closed.
     *  Informs the account holder that they can revert the closing by logging into the account in the near future.
     * 
     *  @param string $cinemaEmail email address of the chosen cinema account
     * 
     *  @return bool success
     */
    function sendMailOnCinemaClose($cinemaEmail)
    {
        $to = $cinemaEmail;
        $subject = "Account closed";

        $content = "
            Dear user,<br/>
            <br/>
            Your cinema account has just been closed.<br/>
            We are sad to see you leave and hope you come back in the future.<br/>
            <br/>
            If this action was not made by you, or you change your mind, you can save most of your account just by logging into it in the next few days.
            After a few days pass, the account will be permanently deleted.<br/>
            Follow this <a href=\"".base_url()."\">link</a> to our website.<br/>
            <br/>
            Your Amenic
        ";

        return sendMail($to, $subject, $content);
    }
}

if (!function_exists("sendMailOnCinemaApproved"))
{
    /**
     *  Sends mail to a cinema account that was just approved.
     *  Informs the account holder that they can log into the account.
     * 
     *  @param string $cinemaEmail email address of the chosen cinema account
     * 
     *  @return bool success
     */
    function sendMailOnCinemaApproved($cinemaEmail)
    {
        $to = $cinemaEmail;
        $subject = "Account approved";

        $content = "
            Dear user,<br/>
            <br/>
            Your cinema account has just been approved.<br/>
            This means you can now log into your cinema account and start managing your cinema online.<br/>
            Follow this <a href=\"".base_url()."\">link</a> to our website.<br/>
            <br/>
            Your Amenic
        ";

        return sendMail($to, $subject, $content);
    }
}

if (!function_exists("sendReservationInfo"))
{
    /**
     *  Sends mail to the reservation holder.
     *  Informs the reservation holder that they successfuly made a reservation, and gives them relevant info about the reservation.
     * 
     *  @param object $reservation the reservation that was made
     *  @param string $seatsString the seats for which the reservation was made
     *  @param object $projection the projection for which the reservation was made
     *  @param object $movie the movie for which the reservation was made
     *  @param object $cinema the cinema in which the projection is showing
     *  @param object $tech the technology in which the projection is showing
     *  @param bool $timeChange if the email was triggered by projection start time change
     * 
     *  @return bool success
     */
    function sendReservationInfo($reservation, $seatsString, $projection, $movie, $cinema, $tech, $timeChange)
    {
        $to = $reservation->email;
        $subject = $timeChange ? "Reservation changed - ".$movie->title : "New reservation - ".$movie->title;

        $hours = intval($movie->runtime / 60);
        $minutes = intval($movie->runtime % 60);

        if ($timeChange)
        {
            $text = "
                The projection you made a reservation for was moved.<br/>
                Here are the updated reservation details:<br/>
            ";
        }
        else
        {
            $text = "
            Thank you for using the Amenic platform for your cinema booking needs.<br/>
            Your reservation has been logged, and you can see the details below:<br/>
            ";
        }

        $content = "
            Dear user,<br/>
            <br/>
            ".$text."
            <br/>
            <table style=\"border-spacing: 4em 0.5em;\">
                <tr>
                    <td>Movie:</td>
                    <td>".$movie->title." &middot; ".$hours."h ".$minutes."m &middot; ".$tech->name."</td>
                </tr>
                <tr>
                    <td>Location:</td>
                    <td>".$cinema->name.", ".$projection->roomName."</td>
                </tr>
                <tr>
                    <td>Date and Time:</td>
                    <td>".date("H:i j/n/Y", strtotime($projection->dateTime))."</td>
                </tr>
                <tr>
                    <td>Seats:</td>
                    <td>".$seatsString."</td>
                </tr>
                <tr>
                    <td>Reservation ID:</td>
                    <td>".$reservation->idRes."</td>
                </tr>
            </table>
            <br/>
            Your Amenic
        ";

        return sendMail($to, $subject, $content);
    }
}

if (!function_exists("sendMailOnReservationDelete"))
{
    /**
     *  Sends mail to a cinema account that was just approved.
     *  Informs the account holder that they can log into the account.
     * 
     *  @param object $reservation the deleted reservation
     * 
     *  @return bool success
     */
    function sendMailOnReservationDelete($reservation)
    {
        $to = $reservation->email;
        $subject = "Reservation expired";

        $content = "
            Dear user,<br/>
            <br/>
            Your reservation has just expired.<br/>
            The reservation with ID ".$reservation->idRes." is not valid anymore.<br/>
            <br/>
            Your Amenic
        ";

        return sendMail($to, $subject, $content);
    }
}