<?php
session_start();
require 'db.php';

if (!isset($_SESSION['donor'])) {
  header("Location: login.php");
  exit;
}

$donor = $_SESSION['donor'];
$donor_id = $donor['id'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $donated_on = $_POST['donated_on'] ?? null;
  $hospital = $_POST['hospital_name'] ?? null;
  $city = $_POST['city'] ?? null;
  $file = $_FILES['proof_file'] ?? null;

  if ($donated_on && $hospital && $city && $file && $file['tmp_name']) {
    // Save uploaded file
    $filename = time() . "_" . basename($file['name']);
    $filepath = "proofs/" . $filename;
    move_uploaded_file($file['tmp_name'], $filepath);

    // Insert into donation_history
    $stmt = $db->prepare("INSERT INTO donation_history 
      (donor_id, donated_on, hospital_name, city, proof_file)
      VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$donor_id, $donated_on, $hospital, $city, $filename]);

    // Update last_donated in donors table
    $db->prepare("UPDATE donors SET last_donated=? WHERE id=?")
       ->execute([$donated_on, $donor_id]);

    $_SESSION['donor']['last_donated'] = $donated_on; // Update session
    $message = "✅ Proof uploaded and last donated date updated.";
  } else {
    $message = "❌ Please fill all fields and select a file.";
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Upload Donation Proof</title>
  <style>
    body { font-family: sans-serif; background: #fffdf5; padding: 40px; }
    .box {
      max-width: 500px;
      margin: auto;
      background: #fff;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 0 10px #ccc;
    }
    h2 { color: #d00000; text-align: center; }
    label, input, textarea, select {
      display: block;
      width: 100%;
      margin-top: 10px;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }
    button {
      margin-top: 15px;
      background: #d00000;
      color: white;
      border: none;
      padding: 12px;
      border-radius: 6px;
      width: 100%;
    }
    .msg { text-align: center; margin: 15px 0; color: green; }
  </style>
</head>
<body>
  <div class="box">
    <h2>📤 Upload Donation Proof</h2>
    <?php if ($message): ?>
      <p class="msg"><?= $message ?></p>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
      <label for="donated_on">Donation Date</label>
      <input type="date" name="donated_on" required value="<?= date('Y-m-d') ?>">

      <label for="hospital_name">Hospital/Center Name</label>
      <input type="text" name="hospital_name" required>

      <label for="city">City</label>
      <input type="text" name="city" required>

      <label for="proof_file">Upload Proof (Image or PDF)</label>
      <input type="file" name="proof_file" accept=".jpg,.jpeg,.png,.pdf" required>

      <button type="submit">Upload</button>
    </form>
  </div>
</body>
</html>
