<?php
require 'db.php';
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=donors_export.csv');

$output = fopen("php://output", "w");
fputcsv($output, ['Name', 'Email', 'Phone', 'City', 'Pincode', 'Blood Group', 'Verified']);

$stmt = $db->query("SELECT name, email, phone, city, pincode, blood_group, verified FROM donors");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $row['verified'] = $row['verified'] ? 'Yes' : 'No';
  fputcsv($output, $row);
}
fclose($output);
exit;
