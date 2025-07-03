<?php require 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Search Donors</title>
  <style>
    body { background: #fffaf5; font-family: sans-serif; padding: 40px; }
    .box { max-width: 600px; margin: auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px #ccc; }
    input, select, button { width: 100%; padding: 10px; margin-top: 10px; border-radius: 6px; border: 1px solid #ccc; }
    button { background: #d00000; color: white; border: none; }
    .result { margin-top: 20px; background: #f9f9f9; padding: 15px; border-radius: 6px; }
  </style>
</head>
<body>
  <div class="box">
    <h2>🔍 Find Blood Donors</h2>
    <form method="GET">
      <input type="text" name="city" placeholder="Enter City" required>
      <select name="blood_group" required>
        <option value="">-- Select Blood Group --</option>
        <option>A+</option><option>A-</option><option>B+</option><option>B-</option>
        <option>O+</option><option>O-</option><option>AB+</option><option>AB-</option>
      </select>
      <button type="submit">Search</button>
    </form>

    <?php
    if (isset($_GET['city'], $_GET['blood_group'])) {
      $city = $_GET['city'];
      $bg = $_GET['blood_group'];
      $stmt = $db->prepare("SELECT id, name, city, phone, blood_group FROM donors WHERE city=? AND blood_group=? AND verified=1");
      $stmt->execute([$city, $bg]);

      if ($stmt->rowCount()) {
        foreach ($stmt as $donor) {
          echo "<div class='result'>";
          echo "<strong>{$donor['name']}</strong> ({$donor['blood_group']})<br>";
          echo "📍 {$donor['city']}<br>";
          echo "📞 {$donor['phone']}<br>";
          echo "<a href='request_blood.php?id={$donor['id']}'>Request Blood</a>";
          echo "</div>";
        }
      } else {
        echo "<p class='result'>No matching donors found.</p>";
      }
    }
    ?>
  </div>
</body>
</html>
