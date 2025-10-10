<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();
    $mail->Host = 'mail.humaravikalp.org';
    $mail->SMTPAuth = true;
    $mail->Username = 'info@humaravikalp.org';
    $mail->Password = 'w,@eaT7wSrCX'; // <-- use your actual email password
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    //Recipients
    $mail->setFrom('info@humaravikalp.org', 'Sakhi Helpline');
    $mail->addAddress('info@humaravikalp.org'); // Main recipient
    $mail->addAddress('sunainamahesh1@gmail.com'); // Additional recipient
    $mail->addAddress('ipasdevelopmentfoundation@gmail.com'); // Additional recipient
    $mail->addReplyTo('info@humaravikalp.org', 'Sakhi Helpline');
    // Attachments removed for testing
    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
