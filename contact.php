<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}

function clean($v) {
    return trim(strip_tags((string)$v));
}

// Form values
$name      = clean($_POST['name'] ?? '');
$phone     = clean($_POST['phone'] ?? '');
$city      = clean($_POST['city'] ?? '');
$consent   = clean($_POST['consent'] ?? '');
$call_time = clean($_POST['call_time'] ?? ''); // ✅ new field
$message   = clean($_POST['message'] ?? '');
$extra     = clean($_POST['extra'] ?? '');

// Validations
$phoneRe = '/^[6-9]\d{9}$/u';
if ($name === '') { echo "<script>alert('कृपया नाम भरें।'); window.history.back();</script>"; exit; }
if ($message === '') { echo "<script>alert('कृपया प्रश्न/संदेश लिखें।'); window.history.back();</script>"; exit; }
if ($consent === '') { echo "<script>alert('कृपया अनुमति चुनें (हाँ/नहीं)।'); window.history.back();</script>"; exit; }
if ($consent === 'नहीं' && $phone !== '') {
    echo "<script>alert('यदि आप कॉल नहीं चाहते तो फोन नंबर खाली रखें या \"हाँ\" चुनें।'); window.history.back();</script>";
    exit;
}
if ($phone !== '' && !preg_match($phoneRe, $phone)) {
    echo "<script>alert('कृपया मान्य मोबाइल नंबर डालें (10 अंक, 6-9 से शुरू)।'); window.history.back();</script>";
    exit;
}

// DB connection
require_once __DIR__ . '/db.php';

// Insert data
$stmt = $conn->prepare("INSERT INTO contacts (name, phone, city, consent, call_time, message, extra) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $name, $phone, $city, $consent, $call_time, $message, $extra);
$stmt->execute();
$stmt->close();
$conn->close();

// PHPMailer
require __DIR__ . '/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'mail.humaravikalp.org';
    $mail->SMTPAuth = true;
    $mail->Username = 'info@humaravikalp.org';
    $mail->Password = 'w,@eaT7wSrCX'; 
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->CharSet = 'UTF-8';
    $mail->setFrom('info@humaravikalp.org', 'Sakhi Helpline');
    $mail->addAddress('info@humaravikalp.org');
    $mail->addAddress('sunainamahesh1@gmail.com');
    $mail->addAddress('ipasdevelopmentfoundation@gmail.com');

    $subject = "नया संपर्क फ़ॉर्म संदेश - सखी हेल्पलाइन";
    $body  = "सखी हेल्पलाइन - नया संदेश\n\n";
    $body .= "नाम: $name\n";
    $body .= "मोबाइल: " . ($phone === '' ? 'नहीं दिया गया' : $phone) . "\n";
    $body .= "शहर: " . ($city === '' ? 'नहीं दिया गया' : $city) . "\n";
    $body .= "अनुमति: $consent\n";
    if ($call_time !== '') { $body .= "कॉल का समय: $call_time\n"; }
    $body .= "\nप्रश्न/संदेश:\n$message\n\n";
    if ($extra !== '') { $body .= "अतिरिक्त प्रश्न:\n$extra\n\n"; }
    $body .= "----\nयह संदेश वेबसाइट के संपर्क फ़ॉर्म से प्राप्त हुआ।";

    $mail->Subject = $subject;
    $mail->Body    = $body;
    $mail->isHTML(false);

    if ($mail->send()) {
        echo "<script>alert('आपका संदेश सफलतापूर्वक भेज दिया गया है।'); window.location.href='index.html';</script>";
    } else {
        error_log('PHPMailer Error: ' . $mail->ErrorInfo);
        echo "<script>alert('संदेश भेजने में त्रुटि हुई।'); window.history.back();</script>";
    }
} catch (Exception $e) {
    error_log('PHPMailer Exception: ' . $e->getMessage());
    echo "<script>alert('सर्वर त्रुटि! कृपया बाद में पुनः प्रयास करें।'); window.history.back();</script>";
}
?>
