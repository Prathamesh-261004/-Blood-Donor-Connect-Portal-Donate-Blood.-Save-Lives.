<?php require 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Smart Search</title>
  <style>
    body { font-family: sans-serif; background: #fdf9f9; padding: 40px; }
    .box { max-width: 600px; margin: auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px #ccc; }
    input, select, button { width: 100%; padding: 10px; margin-top: 10px; border-radius: 6px; border: 1px solid #ccc; }
    button { background: #d00000; color: white; border: none; }
    .result { margin-top: 20px; background: #f9f9f9; padding: 15px; border-radius: 6px; }
    .alert { color: #d00000; font-weight: bold; margin-top: 20px; }
  </style>
</head>
<body>
  <div class="box">
    <h2>📍 Smart Pincode Search</h2>
    <form method="GET">
      <input type="text" name="pincode" placeholder="Your Pincode" required>
      <select name="blood_group" required>
        <option value="">-- Select Blood Group --</option>
        <option>A+</option><option>A-</option><option>B+</option><option>B-</option>
        <option>O+</option><option>O-</option><option>AB+</option><option>AB-</option>
      </select>
      <button type="submit">Search Nearby</button>
    </form>

    <?php
    if (isset($_GET['pincode'], $_GET['blood_group'])) {
      $prefix = substr($_GET['pincode'], 0, 3);
      $bg = $_GET['blood_group'];

      // 1. Exact match (both pincode & blood group)
      $stmt = $db->prepare("SELECT name, city, pincode, phone, blood_group FROM donors WHERE pincode LIKE ? AND blood_group = ? AND verified = 1");
      $stmt->execute(["$prefix%", $bg]);
      $results = $stmt->fetchAll();

      $alert = '';

      // 2. If no full matches, try blood group only
      if (!$results) {
        $stmt = $db->prepare("SELECT name, city, pincode, phone, blood_group FROM donors WHERE blood_group = ? AND verified = 1");
        $stmt->execute([$bg]);
        $results = $stmt->fetchAll();
        $alert = "⚠️ No nearby pincode matches found, showing donors with same blood group.";
      }

      // 3. If still none, try pincode only
      if (!$results) {
        $stmt = $db->prepare("SELECT name, city, pincode, phone, blood_group FROM donors WHERE pincode LIKE ? AND verified = 1");
        $stmt->execute(["$prefix%"]);
        $results = $stmt->fetchAll();
        $alert = "⚠️ No matching blood group found nearby, showing donors in your area.";
      }

      if ($results) {
        if ($alert) echo "<p class='alert'>$alert</p>";
        foreach ($results as $donor) {
          echo "<div class='result'>";
          echo "<strong>" . htmlspecialchars($donor['name']) . "</strong> - " . htmlspecialchars($donor['blood_group']) . "<br>";
          echo "📍 " . htmlspecialchars($donor['city']) . " (" . htmlspecialchars($donor['pincode']) . ")<br>";
          echo "📞 " . htmlspecialchars($donor['phone']);
          echo "</div>";
        }
      } else {
        echo "<p class='result'>❌ No donors found with the provided info.</p>";
      }
    }
    ?>
  </div>
</body>
</html>
