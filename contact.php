<?php
// contact.php - receive POST, validate, send via PHPMailer (SMTP, UTF-8, Hindi messages)

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}

function clean($v) {
    return trim(strip_tags((string)$v));
}

$name    = clean($_POST['name'] ?? '');
$phone   = clean($_POST['phone'] ?? '');
$city    = clean($_POST['city'] ?? '');
$consent = clean($_POST['consent'] ?? '');
$message = clean($_POST['message'] ?? '');
$extra   = clean($_POST['extra'] ?? '');

// server-side validations
$phoneRe = '/^[6-9]\d{9}$/u';
if ($name === '') { echo "<script>alert('कृपया नाम भरें।'); window.history.back();</script>"; exit; }
if ($message === '') { echo "<script>alert('कृपया प्रश्न/संदेश लिखें।'); window.history.back();</script>"; exit; }
if ($consent === '') { echo "<script>alert('कृपया अनुमति चुनें (हाँ/नहीं)।'); window.history.back();</script>"; exit; }
if ($consent === 'नहीं' && $phone !== '') {
    echo "<script>alert('आपने \"नहीं\" चुना है — यदि आप कॉल नहीं चाहते तो फोन नंबर खाली रखें या \"हाँ\" चुनें।'); window.history.back();</script>";
    exit;
}
if ($phone !== '' && !preg_match($phoneRe, $phone)) {
    echo "<script>alert('कृपया मान्य मोबाइल नंबर डालें (10 अंक, 6-9 से शुरू)।'); window.history.back();</script>";
    exit;
}

// PHPMailer via Composer
require __DIR__ . '/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // SMTP configuration - set via env vars or hosting panel
    $smtpHost = getenv('SMTP_HOST') ?: 'smtp.example.com';
    $smtpUser = getenv('SMTP_USER') ?: 'smtp_user@example.com';
    $smtpPass = getenv('SMTP_PASS') ?: 'smtp_password';
    $smtpPort = getenv('SMTP_PORT') ?: 587;
    $smtpEnc  = getenv('SMTP_ENC')  ?: 'tls'; // tls or ssl

    $mail->isSMTP();
    $mail->Host       = $smtpHost;
    $mail->SMTPAuth   = true;
    $mail->Username   = $smtpUser;
    $mail->Password   = $smtpPass;
    $mail->SMTPSecure = $smtpEnc;
    $mail->Port       = (int)$smtpPort;
    $mail->CharSet    = 'UTF-8';

    // From and recipients
    $mail->setFrom('noreply@sakhihelpline.com', 'Sakhi Helpline');
    $mail->addAddress('shallu@ghb.digital');
    $mail->addAddress('sonal@ghb.digital');

    // Email content
    $subject = "नया संपर्क फ़ॉर्म संदेश - सखी हेल्पलाइन";
    $body  = "सखी हेल्पलाइन - संपर्क फ़ॉर्म संदेश\n\n";
    $body .= "नाम: $name\n";
    $body .= "मोबाइल नंबर: " . ($phone === '' ? 'नहीं दिया गया' : $phone) . "\n";
    $body .= "शहर: " . ($city === '' ? 'नहीं दिया गया' : $city) . "\n";
    $body .= "अनुमति (Consent): $consent\n\n";
    $body .= "प्रश्न/संदेश:\n$message\n\n";
    if ($extra !== '') { $body .= "अतिरिक्त प्रश्न:\n$extra\n\n"; }
    $body .= "----\nयह संदेश वेबसाइट के संपर्क फ़ॉर्म से प्राप्त हुआ।";

    $mail->Subject = $subject;
    $mail->Body    = $body;
    $mail->isHTML(false);

    $mail->send();

    echo "<script>alert('आपका संदेश सफलतापूर्वक भेज दिया गया है।'); window.location.href='index.html';</script>";
    exit;
} catch (Exception $e) {
    // log error for debugging
    error_log('PHPMailer Error: ' . $mail->ErrorInfo);
    echo "<script>alert('संदेश भेजने में त्रुटि हुई। कृपया बाद में पुनः प्रयास करें।'); window.history.back();</script>";
    exit;
}
?>