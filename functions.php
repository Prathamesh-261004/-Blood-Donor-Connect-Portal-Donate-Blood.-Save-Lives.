<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendOTP($to_email, $otp) {
  require 'phpmailer/src/PHPMailer.php';
  require 'phpmailer/src/SMTP.php';
  require 'phpmailer/src/Exception.php';

  $mail = new PHPMailer(true);
  try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'your@gmail.com';         // change this
    $mail->Password = 'your_app_password';      // change this
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('your@gmail.com', 'Blood Donor Connect');
    $mail->addAddress($to_email);
    $mail->Subject = 'Your OTP for Blood Donor Portal';
    $mail->Body    = "Your OTP is: $otp";

    $mail->send();
    return true;
  } catch (Exception $e) {
    return false;
  }
}

function clean($str) {
  return htmlspecialchars(trim($str));
}

function isEligible($last_date) {
  return strtotime($last_date) <= strtotime('-90 days');
}
