<?php
// !!!important for PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// !!!important for PHPMailer

trait SendMail {


	public function sendMail($to,$subject,$message) { // to use this class, set it to protected and you must extend
		
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = '';
            // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = '';
            // SMTP username
            $mail->Password   = '';// SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            
            //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
    
            //Recipients
            $mail->setFrom('');
            $mail->addAddress($to);     // Add a recipient
           // $mail->addAddress('ellen@example.com');               // Name is optional
            $mail->addReplyTo('', 'Name');
            
            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $message;
    
            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }   
                        
	}

}