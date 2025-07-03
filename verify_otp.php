<?php
require 'db.php';
$email = $_GET['email'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $otp = $_POST['otp'];

  $stmt = $db->prepare("SELECT * FROM donors WHERE email=? AND otp_code=?");
  $stmt->execute([$email, $otp]);

  if ($stmt->rowCount() > 0) {
    $db->prepare("UPDATE donors SET verified=1, otp_code=NULL WHERE email=?")->execute([$email]);
    echo "<p style='text-align:center;color:green;'>✅ Email verified! <a href='login.php'>Login Now</a></p>";
  } else {
    echo "<p style='text-align:center;color:red;'>❌ Invalid OTP. Try again.</p>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Verify OTP</title>
  <style>
    body { background: #fffdfd; font-family: sans-serif; padding: 40px; }
    .box {
      max-width: 400px;
      margin: auto;
      background: #fff5f5;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 10px #ccc;
    }
    input, button {
      width: 100%;
      padding: 12px;
      margin-top: 12px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }
    button {
      background: #d00000;
      color: white;
      border: none;
    }
  </style>
</head>
<body>
  <div class="box">
    <h2>Enter OTP</h2>
    <form method="POST">
      <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
      <input type="text" name="otp" placeholder="6-digit OTP" required>
      <button type="submit">Verify</button>
    </form>
  </div>
</body>
</html>
