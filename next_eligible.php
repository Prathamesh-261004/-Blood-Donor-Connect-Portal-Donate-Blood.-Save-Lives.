<?php
session_start();
if (!isset($_SESSION['donor'])) {
  header("Location: login.php");
  exit;
}

$donor = $_SESSION['donor'];
$last_date_raw = $donor['last_donated'] ?? null;

if ($last_date_raw && strtotime($last_date_raw)) {
  $last = strtotime($last_date_raw);
  $next = strtotime('+90 days', $last);
  $today = time();

  $status = ($today >= $next)
    ? "✅ You are eligible to donate now."
    : "❌ You can donate after: <strong>" . date("d M Y", $next) . "</strong>";
  
  $last_display = "<a href='history.php' title='View Full Donation History'>" . date("d M Y", $last) . "</a>";
} else {
  $status = "⚠️ No donation record found.";
  $last_display = "<span style='color:#d00000;'>Not recorded</span><br><a href='upload_proof.php' style='font-size:14px;'>📤 Click here to upload donation proof</a>";
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Next Eligible Date</title>
  <style>
    body { font-family: sans-serif; background: #f4fff4; padding: 40px; margin: 0; }
    .box {
      max-width: 500px;
      margin: auto;
      background: #ffffff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px #aaa;
      text-align: center;
    }
    h2 { color: #006400; }
    p { font-size: 18px; margin: 10px 0; }
    a {
      color: #d00000;
      text-decoration: none;
    }
    .status {
      margin-top: 20px;
      font-weight: bold;
      color: #333;
    }
  </style>
</head>
<body>
  <div class="box">
    <h2>🗓 Donation Eligibility</h2>
    <p><strong>Last Donation Date:</strong><br><?= $last_display ?></p>
    <p class="status"><?= $status ?></p>
    <p><a href="dashboard.php">← Back to Dashboard</a></p>
  </div>
</body>
</html>
