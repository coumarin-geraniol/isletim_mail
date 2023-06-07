<?php

require 'vendor/autoload.php'; // Include the required library (e.g., PHPMailer)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function send_email($sender, $recipient, $subject, $message) {
    // Retrieve SMTP server hostname from Postfix configuration
    $smtp_host ='sandbox.smtp.mailtrap.io';
    $smtp_port = 2525;
    $smtp_username = '14569ed10de6f7';
    $smtp_password = '9c9aedc7c2dd78';

    $mail = new PHPMailer(true);

    try {
        // Set SMTP configuration
        $mail->isSMTP();
        $mail->Host = $smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $smtp_username;
        $mail->Password = $smtp_password;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $smtp_port;

        // Set email content
        $mail->setFrom($sender);
        $mail->addAddress($recipient);
        $mail->Subject = $subject;
        $mail->Body = $message;

        // Send the email
        $mail->send();
        echo 'Email sent successfully!';
    } catch (Exception $e) {
        echo 'Failed to send email: ' . $mail->ErrorInfo;
    }
}

// Database connection parameters
$host = 'localhost';
$username = 'userlime';
$password = 'LimeSurvey_123';
$database = 'limesurvey';

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Select all sid values from lime_surveys table
    $stmt = $pdo->query("SELECT sid FROM lime_surveys");
    $sids = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Select sid values from sent_sids table
    $stmt = $pdo->query("SELECT sid FROM sent_sids");
    $sentSids = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Find missing sids and insert them into sent_sids table
    $missingSids = array_diff($sids, $sentSids);
    foreach ($missingSids as $sid) {
        $stmt = $pdo->prepare("INSERT INTO sent_sids (sid) VALUES (:sid)");
        $stmt->bindParam(':sid', $sid);
        $stmt->execute();
    }

    // Generate message with sid values
    $message = 'New sids: ' . implode(', ', $missingSids);

    // Call the send_email function with the generated message
   foreach ($missingSids as $sid) {
    // Generate a custom message for each sid
    $message = 'New sid: ' . $sid;

    // Call the send_email function with the generated message
    $sender = 'g00qle.team@gmail.com';
    $recipient = 'semahatartikova@gmail.com';
    $subject = 'Şifre ihlali';
    send_email($sender, $recipient, $subject, $message);
}
echo 'done!';
} catch (PDOException $e) {
    echo 'Database connection failed: ' . $e->getMessage();
}





// $sender = 'g00qle.team@gmail.com';

// $recipient = 'esteeerol@gmail.com';

// $subject = 'Şifre ihlali';

// $message = 'Sevgili semahatartikova@gmail.com,





// Sunucularımıza birden fazla şifre ihlaliyle sonuçlanan bir saldırı olduğunu ve daha detaylı inceleme sonucunda şifrelerinizin ve kredi bilgilerinizin çalınanlardan biri olduğunu üzülerek bildiririz. Bu konuda yakında size geri döneceğiz.





// Saygılarımızla, Hag-- Google ekibi.';



// send_email($sender, $recipient, $subject, $message);


?>
