<?php
session_start();
require 'db.php';
if (!isset($_SESSION['donor'])) {
  header("Location: login.php");
  exit;
}
$donor_id = $_SESSION['donor']['id'];
$stmt = $db->prepare("SELECT * FROM blood_requests WHERE donor_id=? ORDER BY requested_at DESC");
$stmt->execute([$donor_id]);
$requests = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Received Blood Requests</title>
  <style>
    body { font-family: sans-serif; background: #fffaf5; padding: 40px; }
    .box { max-width: 700px; margin: auto; background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 0 8px #aaa; }
    .req { background: #f9f9f9; margin-top: 15px; padding: 15px; border-radius: 6px; }
    .req p { margin: 5px 0; }
  </style>
</head>
<body>
  <div class="box">
    <h2>📬 Blood Requests You've Received</h2>
    <?php
    if ($requests) {
      foreach ($requests as $r) {
        echo "<div class='req'>";
        echo "<p><strong>From:</strong> {$r['requester_name']} ({$r['requester_email']})</p>";
        echo "<p><strong>Phone:</strong> {$r['requester_phone']}</p>";
        echo "<p><strong>Message:</strong> " . nl2br(htmlspecialchars($r['message'])) . "</p>";
        echo "<p><strong>Status:</strong> " . strtoupper($r['status']) . "</p>";
        echo "<p><em>Sent on " . date("d M Y H:i", strtotime($r['requested_at'])) . "</em></p>";
        echo "</div>";
      }
    } else {
      echo "<p>No requests yet.</p>";
    }
    ?>
    <br><a href="dashboard.php">← Back to Dashboard</a>
  </div>
</body>
</html>
