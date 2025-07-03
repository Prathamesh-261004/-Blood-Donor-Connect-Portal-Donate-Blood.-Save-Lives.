<?php
require 'db.php';
$data = [
  'total'=>$db->query("SELECT COUNT(*) FROM donors")->fetchColumn(),
  'verified'=>$db->query("SELECT COUNT(*) FROM donors WHERE verified=1")->fetchColumn(),
  'requests'=>$db->query("SELECT COUNT(*) FROM blood_requests")->fetchColumn(),
  'donations'=>$db->query("SELECT COUNT(*) FROM donation_history")->fetchColumn()
];
header('Content-Type: application/json');
echo json_encode($data);
