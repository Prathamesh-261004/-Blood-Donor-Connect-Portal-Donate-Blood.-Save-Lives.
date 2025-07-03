<?php
require 'db.php';

$email = $_GET['email'] ?? '';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $otp = $_POST['otp'];
  $new_pass = $_POST['new_password'];

  // Fetch donor by email
  $stmt = $db->prepare("SELECT * FROM donors WHERE email=?");
  $stmt->execute([$email]);
  $user = $stmt->fetch();

  if (!$user) {
    $error = "❌ No such email found.";
  } elseif ($user['otp_code'] != $otp) {
    $error = "❌ Invalid OTP.";
  } else {
    $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
    $stmt = $db->prepare("UPDATE donors SET password=?, otp_code=NULL WHERE email=?");
    $stmt->execute([$hashed, $email]);
    $success = "✅ Password updated! You can now login.";
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Reset Password</title>
  <style>
    body { background: #f5faff; font-family: sans-serif; padding: 40px; }
    .box {
      max-width: 420px;
      margin: auto;
      background: #fff;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 0 8px #aaa;
    }
    h2 { text-align: center; color: #0077cc; }
    input, button {
      width: 100%;
      padding: 12px;
      margin-top: 12px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }
    button {
      background: #0077cc;
      color: white;
      border: none;
    }
    .error { color: red; text-align: center; }
    .success { color: green; text-align: center; }
  </style>
</head>
<body>
  <div class="box">
    <h2>🔁 Reset Password</h2>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>
    <form method="POST">
      <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
      <input type="text" name="otp" placeholder="Enter OTP" required>
      <input type="password" name="new_password" placeholder="New Password" required>
      <button type="submit">Update Password</button>
    </form>
    <div style="text-align:center; margin-top:10px;">
      <a href="login.php">← Back to Login</a>
    </div>
  </div>
</body>
</html>
