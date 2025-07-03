<?php
session_start();
require 'db.php';

if (!isset($_SESSION['donor'])) {
  header("Location: login.php");
  exit;
}

$donor = $_SESSION['donor'];
$donor_id = $donor['id'];

if (isset($_POST['confirm'])) {
  // Delete related records first (optional depending on foreign keys)
  $db->prepare("DELETE FROM donation_history WHERE donor_id = ?")->execute([$donor_id]);
  $db->prepare("DELETE FROM blood_requests WHERE donor_id = ?")->execute([$donor_id]);

  // Delete donor record
  $db->prepare("DELETE FROM donors WHERE id = ?")->execute([$donor_id]);

  // Destroy session and redirect
  session_destroy();
  header("Location: login.php");
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Delete Account</title>
  <style>
    body { font-family: sans-serif; background: #fff8f8; padding: 40px; text-align: center; }
    .box {
      max-width: 500px; margin: auto; background: #fff; padding: 30px;
      border-radius: 10px; box-shadow: 0 0 10px #aaa;
    }
    h2 { color: #a00000; }
    form { margin-top: 20px; }
    button {
      padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer;
      margin: 10px;
    }
    .yes { background: #d00000; color: white; }
    .no { background: #888; color: white; }
  </style>
</head>
<body>
  <div class="box">
    <h2>⚠️ Delete Account</h2>
    <p>Are you sure you want to delete your account permanently?</p>
    <form method="POST">
      <button class="yes" name="confirm">Yes, Delete</button>
      <a href="dashboard.php"><button type="button" class="no">Cancel</button></a>
    </form>
  </div>
</body>
</html>
