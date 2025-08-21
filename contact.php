<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = htmlspecialchars($_POST['name']);
    $phone   = htmlspecialchars($_POST['phone']);
    $city    = htmlspecialchars($_POST['city']);
    $message = htmlspecialchars($_POST['message']);

    $to      = "shallu@ghb.digital, sonal@ghb.digital";
    $subject = "नया संपर्क फॉर्म संदेश";
    $body    = "नाम: $name\nफोन नंबर: $phone\nशहर: $city\nसंदेश:\n$message";
    $headers = "From: noreply@sakhihelpline.com\r\nReply-To: $phone";

    if (mail($to, $subject, $body, $headers)) {
        echo "<script>alert('आपका संदेश सफलतापूर्वक भेज दिया गया है।'); window.location.href='index.html';</script>";
    } else {
        echo "<script>alert('संदेश भेजने में त्रुटि हुई। कृपया पुनः प्रयास करें।'); window.history.back();</script>";
    }
} else {
    header("Location: index.html");
    exit();
}
?>