<?php
session_start();
require 'db.php';


if (!isset($_SESSION['donor'])) {
  header("Location: login.php");
  exit;
}

$requester = $_SESSION['donor'];
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $donor_id = $_POST['donor_id'] ?? null;
  $message = trim($_POST['message'] ?? '');

  // Validate donor selection
  if (!$donor_id || !is_numeric($donor_id)) {
    $error = "❌ Please select a valid donor.";
  } elseif (empty($message)) {
    $error = "❌ Message cannot be empty.";
  } else {
    // Check if the selected donor exists and is verified
    $stmt = $db->prepare("SELECT id FROM donors WHERE id = ? AND verified = 1");
    $stmt->execute([$donor_id]);
    $donor_exists = $stmt->fetch();

    if (!$donor_exists) {
      $error = "❌ Selected donor does not exist or is not verified.";
    } else {
      // Insert request
      $stmt = $db->prepare("INSERT INTO blood_requests 
        (donor_id, requester_name, requester_email, requester_phone, message) 
        VALUES (?, ?, ?, ?, ?)");
      $stmt->execute([
        $donor_id,
        $requester['name'],
        $requester['email'],
        $requester['phone'],
        $message
      ]);
      $success = "✅ Blood request sent successfully!";
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Request Blood</title>
  <style>
    body { font-family: sans-serif; background: #fffef6; padding: 40px; }
    .box {
      max-width: 500px;
      margin: auto;
      background: #fff;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 0 8px #aaa;
    }
    h2 { color: #d00000; text-align: center; }
    textarea, button, select {
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
      cursor: pointer;
    }
    .msg {
      text-align: center;
      margin-top: 10px;
      font-weight: bold;
    }
    .success { color: green; }
    .error { color: red; }
    .back {
      display: block;
      text-align: center;
      margin-top: 20px;
    }
    .back a { color: #d00000; text-decoration: none; }
  </style>
</head>
<body>
  <div class="box">
    <h2>🩸 Request Blood</h2>

    <?php if ($success): ?>
      <p class="msg success"><?= $success ?></p>
    <?php elseif ($error): ?>
      <p class="msg error"><?= $error ?></p>
    <?php endif; ?>

    <?php if (empty($success)): ?>
    <form method="POST">
      <select name="donor_id" required>
        <option value="">Select a verified donor</option>
        <?php
        $stmt = $db->prepare("SELECT id, name, city, blood_group FROM donors WHERE verified=1 AND id != ?");
        $stmt->execute([$requester['id']]);
        foreach ($stmt as $row) {
          echo "<option value='{$row['id']}'>" .
               htmlspecialchars("{$row['name']} - {$row['blood_group']} ({$row['city']})") .
               "</option>";
        }
        ?>
      </select>

      <textarea name="message" placeholder="Write your request message..." required></textarea>
      <button type="submit">Send Request</button>
    </form>
    <?php endif; ?>

    <div class="back"><a href="dashboard.php">← Back to Dashboard</a></div>
  </div>
</body>
</html>
