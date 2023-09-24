<?php
// Get form data
$name = 'aa';
$email = 'kadhiyesser@gmail.com';
$subject = 'test';
$message = 'message';

// Build email message
$to = "yesserkadhi@gmail.com";
$subject = "New message from " . $subject;
$body = "From: " . $name . "\n";
$body .= "Email: " . $email . "\n";
$body .= "Message: " . $message;

$smtp_server = "mail.ics-tn.com";
$smtp_username = "support@ics-tn.com";
$smtp_password = "Welcome02$$";
$smtp_port = 466;

// Set the SMTP server details
ini_set("SMTP", $smtp_server);
ini_set("smtp_port", $smtp_port);
ini_set("sendmail_from", $smtp_username);

// Set the email headers
$headers = "From: $smtp_username\r\n";
$headers .= "Reply-To: $email\r\n"; // Utilisez l'e-mail de l'utilisateur comme réponse
$headers .= "X-Mailer: PHP/" . phpversion();

// Send email
if (mail($to, $subject, $body, $headers)) {
    header('Location: index.html'); // Redirigez après avoir envoyé l'e-mail avec succès
    exit();
} else {
    header('Location: index.html'); // Redirigez en cas d'échec de l'envoi de l'e-mail
    exit();
}
