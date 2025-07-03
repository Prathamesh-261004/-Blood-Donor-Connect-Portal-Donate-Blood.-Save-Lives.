<?php
session_start();
require 'db.php';

// ✅ Check if admin is logged in
if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Blood Group Alerts</title>
  <style>
    body { font-family: sans-serif; background: #fffaf5; padding: 40px; }
    table { width: 100%; border-collapse: collapse; background: #fff; box-shadow: 0 0 8px #ccc; }
    th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
    th { background: #d00000; color: white; }
    .low { background: #ffcccc; }
  </style>
</head>
<body>
  <h2>📉 Blood Group Shortage Areas</h2>
  <table>
    <tr><th>City</th><th>Blood Group</th><th>Available Donors</th></tr>
    <?php
    $stmt = $db->query("SELECT city, blood_group, COUNT(*) as count 
                        FROM donors WHERE verified=1 
                        GROUP BY city, blood_group ORDER BY city, blood_group");
    foreach ($stmt as $row) {
      $class = $row['count'] < 5 ? 'low' : '';
      echo "<tr class='$class'><td>{$row['city']}</td><td>{$row['blood_group']}</td><td>{$row['count']}</td></tr>";
    }
    ?>
  </table>
</body>
</html>
