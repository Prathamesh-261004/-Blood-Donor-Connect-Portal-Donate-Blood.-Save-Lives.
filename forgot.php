<?php
require 'db.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $otp = rand(100000, 999999);

  $stmt = $db->prepare("UPDATE donors SET otp_code=? WHERE email=?");
  $stmt->execute([$otp, $email]);

  $mail = new PHPMailer(true);
  try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'your@gmail.com';
    $mail->Password = 'your_app_password';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('your@gmail.com', 'Blood Donor Portal');
    $mail->addAddress($email);
    $mail->Subject = 'Password Reset OTP';
    $mail->Body = "Your password reset OTP is: $otp";

    $mail->send();
    header("Location: reset.php?email=" . urlencode($email));
    exit;
  } catch (Exception $e) {
    $msg = "❌ OTP sending failed.";
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
  <style>
    body { font-family: sans-serif; background: #fffaf5; padding: 40px; }
    .box { max-width: 400px; margin: auto; background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 0 8px #aaa; }
    input, button { width: 100%; padding: 12px; margin-top: 12px; border-radius: 6px; border: 1px solid #ccc; }
    .msg { text-align: center; color: red; }
  </style>
</head>
<body>
  <div class="box">
    <h2>🔐 Forgot Password</h2>
    <?php if (!empty($msg)) echo "<p class='msg'>$msg</p>"; ?>
    <form method="POST">
      <input type="email" name="email" placeholder="Enter your email" required>
      <button type="submit">Send OTP</button>
    </form>
  </div>
</body>
</html>
