<?php
require 'PHPMailerAutoload.php';
function send_email2017($to_id,$message,$subject,$cc)
{
    
    // Fetching data that is entered by the user
    $email = "norepaly.kpi@gmail.com";
    $password = "noreply123";
    
    // Configuring SMTP server settings
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = $email;
    $mail->Password = $password;
    
    // Email Sending Details
    $mail->FromName    = "KPI";
    $mail->addAddress($to_id);
    $mail->AddCC($cc);
    $mail->Subject = $subject;
    $mail->msgHTML($message);
    
    // Success or Failure
    if (!$mail->send()) {
    //$error = "Mailer Error: " . $mail->ErrorInfo;
    //echo '<p id="para">'.$error.'</p>';
        return 0;
    }
    else {
    //echo '<p id="para">Message sent!</p>';
        return 1;
    }
}

send_email2017('scspl.amarshi@gmail.com','this is test msg','Test','');
?>