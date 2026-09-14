<?php
// DiveTower contact form handler — SiteGround PHP mail
// Sends every submission to both founders.

// ==== EDIT THESE TWO LINES ====
$recipients = 'jessie@diveshopsolutions.com, marc@diveshopsolutions.com, kohkooddivers@gmail.com';
$from_address = 'jessie@diveshopsolutions.com'; // must be an email on this domain (create it in SiteGround Site Tools -> Email -> Accounts)
// ==============================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.html');
    exit;
}

// Honeypot: real people never fill this hidden field
if (!empty($_POST['website'])) {
    header('Location: contact.html?sent=1'); // pretend success to bots
    exit;
}

$name    = trim(strip_tags($_POST['name'] ?? ''));
$email   = trim($_POST['email'] ?? '');
$shop    = trim(strip_tags($_POST['shop'] ?? ''));
$message = trim(strip_tags($_POST['message'] ?? ''));

if ($name === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: contact.html');
    exit;
}

// Block header injection attempts
foreach ([$name, $email, $shop] as $field) {
    if (preg_match('/[\r\n]/', $field)) {
        header('Location: contact.html');
        exit;
    }
}

$subject = 'DiveTower website: message from ' . $name . ($shop !== '' ? ' (' . $shop . ')' : '');

$body  = "New message from the DiveTower website\n";
$body .= "======================================\n\n";
$body .= "Name:  " . $name . "\n";
$body .= "Email: " . $email . "\n";
$body .= "Shop:  " . ($shop !== '' ? $shop : '-') . "\n\n";
$body .= "Message:\n" . $message . "\n\n";
$body .= "--\nSent " . date('Y-m-d H:i') . " (server time) from diveshopsolutions.com\n";

$headers  = "From: DiveTower Website <" . $from_address . ">\r\n";
$headers .= "Reply-To: " . $name . " <" . $email . ">\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

mail($recipients, $subject, $body, $headers);

header('Location: contact.html?sent=1');
exit;
