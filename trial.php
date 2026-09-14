<?php
$recipients = 'jessie@diveshopsolutions.com, marc@diveshopsolutions.com, kohkooddivers@gmail.com';
$from_address = 'jessie@diveshopsolutions.com';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: trial.html'); exit; }
if (!empty($_POST['website'])) { header('Location: trial.html?sent=1'); exit; }
$shop     = trim(strip_tags($_POST['shop'] ?? ''));
$name     = trim(strip_tags($_POST['name'] ?? ''));
$email    = trim($_POST['email'] ?? '');
$whatsapp = trim(strip_tags($_POST['whatsapp'] ?? ''));
$location = trim(strip_tags($_POST['location'] ?? ''));
$message  = trim(strip_tags($_POST['message'] ?? ''));
if ($shop === '' || $name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) { header('Location: trial.html'); exit; }
foreach ([$shop, $name, $email, $whatsapp, $location] as $field) {
    if (preg_match('/[\r\n]/', $field)) { header('Location: trial.html'); exit; }
}
$subject = 'FREE TRIAL request: ' . $shop . ' (' . $name . ')';
$body  = "NEW FREE TRIAL REQUEST\n======================\n\n";
$body .= "Shop:     " . $shop . "\n";
$body .= "Name:     " . $name . "\n";
$body .= "Email:    " . $email . "\n";
$body .= "WhatsApp: " . ($whatsapp !== '' ? $whatsapp : '-') . "\n";
$body .= "Location: " . ($location !== '' ? $location : '-') . "\n\n";
$body .= "Notes:\n" . ($message !== '' ? $message : '-') . "\n\n";
$body .= "--\nAction: set up their shop and send the login.\nSent " . date('Y-m-d H:i') . " from diveshopsolutions.com/trial.html\n";
$headers  = "From: DiveTower Website <" . $from_address . ">\r\n";
$headers .= "Reply-To: " . $name . " <" . $email . ">\r\n";
$headers .= "MIME-Version: 1.0\r\nContent-Type: text/plain; charset=UTF-8\r\n";
mail($recipients, $subject, $body, $headers);
header('Location: trial.html?sent=1');
exit;
