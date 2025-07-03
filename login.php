<?php
session_start();
require 'db.php';

// Admin credentials (preset)
$admin_email = "admin@bloodportal.com";
$admin_password = "admin123"; // You can hash it for more security

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Check if admin
  if ($email === $admin_email && $password === $admin_password) {
    $_SESSION['admin'] = true;
    header("Location: admin_panel.php");
    exit;
  }

  // Else check donor login
  $stmt = $db->prepare("SELECT * FROM donors WHERE email=? AND verified=1");
  $stmt->execute([$email]);
  $donor = $stmt->fetch();

  if ($donor && password_verify($password, $donor['password'])) {
    $_SESSION['donor'] = $donor;
    header("Location: dashboard.php");
    exit;
  } else {
    $error = "❌ Invalid credentials or email not verified.";
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Donor/Admin Login</title>
  <style>
    body { background: #fff5f5; font-family: sans-serif; padding: 40px; }
    .box {
      max-width: 400px;
      margin: auto;
      background: #fff;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 0 8px #aaa;
    }
    h2 { text-align: center; color: #d00000; }
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
    .error { color: red; text-align: center; }
    .forgot-link {
      text-align: center;
      margin-top: 10px;
    }
    .forgot-link a {
      text-decoration: none;
      color: #d00000;
    }
  </style>
</head>
<body>
  <div class="box">
    <h2>🔐 Donor / Admin Login</h2>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
    <div class="forgot-link">
      <a href="forgot.php">Forgot Password?</a>
    </div>
  </div>
</body>
</html>
