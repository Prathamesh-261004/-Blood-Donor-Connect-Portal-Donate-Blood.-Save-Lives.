<?php
session_start();
if (!isset($_SESSION['donor'])) {
  header("Location: login.php");
  exit;
}
$donor = $_SESSION['donor']; // Now $donor is available everywhere
