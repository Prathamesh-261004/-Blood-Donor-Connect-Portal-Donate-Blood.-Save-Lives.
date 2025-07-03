<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}

$action = $_GET['action'] ?? '';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Panel</title>
  <style>
    body { font-family: sans-serif; padding: 40px; background: #f5fff8; }
    h1 { color: #006400; text-align: center; }
    .buttons {
      max-width: 1000px; margin: auto; display: flex; flex-wrap: wrap;
      gap: 10px; justify-content: center; margin-bottom: 30px;
    }
    a.button {
      padding: 12px 18px; background: #006400; color: white;
      text-decoration: none; border-radius: 6px; font-size: 14px;
      text-align: center; min-width: 200px;
    }
    table {
      width: 100%; border-collapse: collapse; margin-top: 30px; background: #fff;
      box-shadow: 0 0 10px #ccc;
    }
    th, td {
      padding: 10px; border: 1px solid #ccc; text-align: center;
    }
    th { background-color: #006400; color: white; }
    .note { text-align: center; margin-top: 30px; font-size: 14px; color: #444; }
    .low { background: #ffdddd; }
  </style>
</head>
<body>

<h1>🛡 Admin Control Panel</h1>

<div class="buttons">
  <a class="button" href="?action=alerts">📉 Shortage Alerts</a>
  <a class="button" href="?action=manage">🛠 Manage Donors</a>
  <a class="button" href="?action=recent_requests">📬 Recent Requests</a>
  <a class="button" href="?action=cleanup_unverified">🧹 Cleanup</a>
  <a class="button" href="?action=blood_groups">🩸 Blood Group Stats</a>
  <a class="button" href="?action=requests">📬 All Requests</a>
  <a class="button" href="?action=donations">🧾 Donation Records</a>
  <a class="button" href="?action=stats">📊 Portal Stats</a>
  <a class="button" href="?action=cities">🌍 City Listing</a>
  <a class="button" href="?action=export">📤 Export CSV</a>
  <a class="button" href="logout.php">🚪 Logout</a>
</div>

<?php
// ACTION HANDLERS
if ($action === 'alerts') {
  echo "<h2>📉 Blood Group Shortage Alerts</h2>";
  $stmt = $db->query("SELECT city, blood_group, COUNT(*) as count 
                      FROM donors WHERE verified=1 
                      GROUP BY city, blood_group");
  echo "<table><tr><th>City</th><th>Blood Group</th><th>Count</th></tr>";
  foreach ($stmt as $row) {
    $low = $row['count'] < 5 ? 'class="low"' : '';
    echo "<tr $low><td>{$row['city']}</td><td>{$row['blood_group']}</td><td>{$row['count']}</td></tr>";
  }
  echo "</table>";
}

elseif ($action === 'manage') {
  echo "<h2>🛠 Manage Donor Accounts</h2>";
  if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $db->query("UPDATE donors SET active = NOT active WHERE id = $id");
    echo "<p style='color:green;'>✅ Donor ID $id status toggled!</p>";
  }
  $stmt = $db->query("SELECT id, name, email, city, active FROM donors WHERE verified=1");
  echo "<table><tr><th>ID</th><th>Name</th><th>Email</th><th>City</th><th>Status</th><th>Action</th></tr>";
  foreach ($stmt as $d) {
    $status = $d['active'] ? 'Active' : 'Inactive';
    $action = $d['active'] ? 'Deactivate' : 'Reactivate';
    echo "<tr>
            <td>{$d['id']}</td><td>{$d['name']}</td><td>{$d['email']}</td><td>{$d['city']}</td>
            <td>$status</td>
            <td><a href='?action=manage&toggle={$d['id']}'>$action</a></td>
          </tr>";
  }
  echo "</table>";
}

elseif ($action === 'recent_requests') {
  echo "<h2>📬 Recent Blood Requests</h2>";
  $stmt = $db->query("SELECT * FROM blood_requests ORDER BY id DESC LIMIT 10");
  echo "<table><tr><th>ID</th><th>Donor ID</th><th>Name</th><th>Email</th><th>Message</th></tr>";
  foreach ($stmt as $r) {
    echo "<tr><td>{$r['id']}</td><td>{$r['donor_id']}</td><td>{$r['requester_name']}</td>
          <td>{$r['requester_email']}</td><td>{$r['message']}</td></tr>";
  }
  echo "</table>";
}

elseif ($action === 'cleanup_unverified') {
  echo "<h2>🧹 Cleanup Unverified Donors</h2>";
  if (isset($_GET['confirm'])) {
    $count = $db->exec("DELETE FROM donors WHERE verified=0");
    echo "<p style='color:red;'>❌ Deleted $count unverified donor(s).</p>";
  } else {
    echo "<p>⚠️ This will delete all unverified donors. <a href='?action=cleanup_unverified&confirm=1'>Confirm Delete</a></p>";
  }
}

elseif ($action === 'blood_groups') {
  echo "<h2>🩸 Blood Group Distribution</h2>";
  $stmt = $db->query("SELECT blood_group, COUNT(*) as total FROM donors GROUP BY blood_group");
  echo "<table><tr><th>Blood Group</th><th>Total</th></tr>";
  foreach ($stmt as $row) {
    echo "<tr><td>{$row['blood_group']}</td><td>{$row['total']}</td></tr>";
  }
  echo "</table>";
}

elseif ($action === 'requests') {
  echo "<h2>📬 All Blood Requests</h2>";
  $stmt = $db->query("SELECT * FROM blood_requests ORDER BY id DESC");
  echo "<table><tr><th>ID</th><th>Donor ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Message</th></tr>";
  foreach ($stmt as $r) {
    echo "<tr><td>{$r['id']}</td><td>{$r['donor_id']}</td><td>{$r['requester_name']}</td>
          <td>{$r['requester_email']}</td><td>{$r['requester_phone']}</td><td>{$r['message']}</td></tr>";
  }
  echo "</table>";
}

elseif ($action === 'donations') {
  echo "<h2>🧾 All Donation Records</h2>";
  $stmt = $db->query("SELECT * FROM donation_history ORDER BY donated_on DESC");
  echo "<table><tr><th>ID</th><th>Donor ID</th><th>Date</th><th>Hospital</th><th>City</th><th>Proof</th></tr>";
  foreach ($stmt as $r) {
    $link = $r['proof_file'] ? "<a href='proofs/{$r['proof_file']}' target='_blank'>View</a>" : "N/A";
    echo "<tr><td>{$r['id']}</td><td>{$r['donor_id']}</td><td>{$r['donated_on']}</td>
          <td>{$r['hospital_name']}</td><td>{$r['city']}</td><td>$link</td></tr>";
  }
  echo "</table>";
}

elseif ($action === 'stats') {
  echo "<h2>📊 Portal Stats</h2>";
  $total = $db->query("SELECT COUNT(*) FROM donors")->fetchColumn();
  $verified = $db->query("SELECT COUNT(*) FROM donors WHERE verified=1")->fetchColumn();
  $donations = $db->query("SELECT COUNT(*) FROM donation_history")->fetchColumn();
  $requests = $db->query("SELECT COUNT(*) FROM blood_requests")->fetchColumn();
  echo "<table>
          <tr><th>Total Donors</th><td>$total</td></tr>
          <tr><th>Verified Donors</th><td>$verified</td></tr>
          <tr><th>Total Donations</th><td>$donations</td></tr>
          <tr><th>Total Requests</th><td>$requests</td></tr>
        </table>";
}

elseif ($action === 'cities') {
  echo "<h2>🌍 City-wise Donor Count</h2>";
  $stmt = $db->query("SELECT city, COUNT(*) as total FROM donors GROUP BY city ORDER BY total DESC");
  echo "<table><tr><th>City</th><th>Donors</th></tr>";
  foreach ($stmt as $row) {
    echo "<tr><td>{$row['city']}</td><td>{$row['total']}</td></tr>";
  }
  echo "</table>";
}

elseif ($action === 'export') {
  header("Content-Type: text/csv");
  header("Content-Disposition: attachment;filename=donors_export.csv");
  $stmt = $db->query("SELECT name,email,phone,city,blood_group FROM donors WHERE verified=1");
  echo "Name,Email,Phone,City,Blood Group\n";
  foreach ($stmt as $d) {
    echo "{$d['name']},{$d['email']},{$d['phone']},{$d['city']},{$d['blood_group']}\n";
  }
  exit;
}

else {
  echo "<p class='note'>Select an option above to view data.</p>";
}
?>

</body>
</html>
