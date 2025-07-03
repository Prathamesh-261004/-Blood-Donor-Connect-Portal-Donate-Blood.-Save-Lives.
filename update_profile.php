<?php
session_start();
require 'db.php';

if (!isset($_SESSION['donor'])) {
  header("Location: login.php");
  exit;
}

$donor = $_SESSION['donor'];
$id = $donor['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $phone = $_POST['phone'];
  $city = $_POST['city'];
  $pincode = $_POST['pincode'];

  $stmt = $db->prepare("UPDATE donors SET name=?, phone=?, city=?, pincode=? WHERE id=?");
  $stmt->execute([$name, $phone, $city, $pincode, $id]);

  $_SESSION['donor']['name'] = $name;
  $_SESSION['donor']['phone'] = $phone;
  $_SESSION['donor']['city'] = $city;
  $_SESSION['donor']['pincode'] = $pincode;

  $msg = "✅ Profile updated!";
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Update Profile</title>
  <style>
    body { font-family: sans-serif; background: #fffdfd; padding: 40px; }
    .box {
      max-width: 500px;
      margin: auto;
      background: #fff5f5;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 0 8px #aaa;
    }
    input, button {
      width: 100%;
      padding: 10px;
      margin-top: 12px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }
    button {
      background: #d00000;
      color: white;
      border: none;
    }
    .msg { text-align: center; color: green; }
  </style>
</head>
<body>
  <div class="box">
    <h2>Update Profile</h2>
    <?php if (!empty($msg)) echo "<p class='msg'>$msg</p>"; ?>
    <form method="POST">
      <input type="text" name="name" value="<?= htmlspecialchars($donor['name']) ?>" required>
      <input type="text" name="phone" value="<?= htmlspecialchars($donor['phone']) ?>" required>
      <input type="text" name="city" value="<?= htmlspecialchars($donor['city']) ?>" required>
      <input type="text" name="pincode" value="<?= htmlspecialchars($donor['pincode']) ?>" required>
      <button type="submit">Save Changes</button>
    </form>
  </div>
</body>
</html>
