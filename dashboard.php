<?php
session_start();
if (!isset($_SESSION['donor'])) {
  header("Location: login.php");
  exit;
}
$donor = $_SESSION['donor'];
?>
<!DOCTYPE html>
<html>
<head>
  <title>Donor Dashboard</title>
  <style>
    body { font-family: sans-serif; background: #f9fff9; padding: 40px; }
    .box {
      max-width: 700px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px #aaa;
    }
    h2 { text-align: center; color: #d00000; }
    p { margin: 8px 0; }
    .info { background: #f0f0f0; padding: 10px 15px; border-radius: 6px; }
    .actions {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 10px;
      margin-top: 25px;
    }
    a.button {
      background: #d00000;
      color: white;
      text-decoration: none;
      padding: 10px 14px;
      border-radius: 6px;
      font-size: 14px;
    }
  </style>
</head>
<body>
  <div class="box">
    <h2>Welcome, <?= htmlspecialchars($donor['name']) ?> 👋</h2>

    <div class="info">
      <p><strong>Blood Group:</strong> <?= $donor['blood_group'] ?></p>
      <p><strong>City:</strong> <?= $donor['city'] ?></p>
      <p><strong>Pincode:</strong> <?= $donor['pincode'] ?></p>
      <p><strong>Phone:</strong> <?= $donor['phone'] ?></p>
      <p><strong>Email:</strong> <?= $donor['email'] ?></p>
    </div>

    <div class="actions">
      <a class="button" href="update_profile.php">🛠 Update Profile</a>
      <a class="button" href="upload_proof.php">📤 Upload Donation Proof</a>
      <a class="button" href="next_eligible.php">📅 Next Eligible Date</a>
      <a class="button" href="request_blood.php">💉 Request Blood</a>
      <a class="button" href="donor_requests.php">📨 Received Requests</a>
      <a class="button" href="history.php">📜 Donation History</a>
      <a class="button" href="search.php">🔍 Search Donors</a>
      <a class="button" href="smart_search.php">🧠 Smart Search (Pincode)</a>
      <a class="button" href="logout.php">🚪 Logout</a>
      <a href="delete_account.php" class="button" >🗑 Delete My Account</a>

    </div>
  </div>
</body>
</html>
