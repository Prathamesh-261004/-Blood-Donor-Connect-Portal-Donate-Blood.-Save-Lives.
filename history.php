<?php
session_start();
require 'db.php';

if (!isset($_SESSION['donor'])) {
  header("Location: login.php");
  exit;
}

$donor = $_SESSION['donor'];
$donor_id = $donor['id'];

$stmt = $db->prepare("SELECT * FROM donation_history WHERE donor_id = ? ORDER BY donated_on DESC");
$stmt->execute([$donor_id]);
$history = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Donation History</title>
  <style>
    body { font-family: sans-serif; background: #f4fff4; padding: 40px; }
    h2 { text-align: center; color: #006400; margin-bottom: 30px; }
    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      box-shadow: 0 0 10px #ccc;
    }
    th, td {
      padding: 12px;
      border: 1px solid #ccc;
      text-align: center;
    }
    th {
      background-color: #d00000;
      color: white;
    }
    a.view-link {
      color: #d00000;
      text-decoration: none;
    }
    .back {
      text-align: center;
      margin-top: 20px;
    }
    .back a {
      color: #d00000;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <h2>🧾 Your Donation History</h2>

  <?php if (count($history) === 0): ?>
    <p style="text-align:center;">No donation records found yet.</p>
  <?php else: ?>
    <table>
      <tr>
        <th>Date</th>
        <th>Hospital</th>
        <th>City</th>
        <th>Proof</th>
      </tr>
      <?php foreach ($history as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['donated_on']) ?></td>
          <td><?= htmlspecialchars($row['hospital_name']) ?></td>
          <td><?= htmlspecialchars($row['city']) ?></td>
          <td>
            <?php if ($row['proof_file']): ?>
              <a href="proofs/<?= urlencode($row['proof_file']) ?>" class="view-link" target="_blank">View</a>
            <?php else: ?>
              No File
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>

  <div class="back">
    <a href="dashboard.php">← Back to Dashboard</a>
  </div>
</body>
</html>
