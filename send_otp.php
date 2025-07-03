<?php
require 'db.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;

$otp = rand(100000, 999999);
$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$phone = $_POST['phone'];
$city = $_POST['city'];
$pincode = $_POST['pincode'];
$blood_group = $_POST['blood_group'];

// Store into database
$stmt = $db->prepare("INSERT INTO donors (name, email, password, phone, city, pincode, blood_group, otp_code, verified)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)");
$stmt->execute([$name, $email, $password, $phone, $city, $pincode, $blood_group, $otp]);

// Send email
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'your@gmail.com';
    $mail->Password = 'your_app_password'; // Use App Password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('your@gmail.com', 'Blood Donor Portal');
    $mail->addAddress($email);
    $mail->Subject = 'Your OTP Code';
    $mail->Body = "Dear $name,\n\nYour OTP for Blood Donor Portal is: $otp\n\nThank you.";

    $mail->send();
    header("Location: verify_otp.php?email=" . urlencode($email));
} catch (Exception $e) {
    echo "OTP email could not be sent. Error: {$mail->ErrorInfo}";
}
