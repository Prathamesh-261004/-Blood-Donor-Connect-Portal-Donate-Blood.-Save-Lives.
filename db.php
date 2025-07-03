<?php
$host = 'localhost';
$dbname = 'blood_donor';
$user = 'root';
$pass = ''; // update if needed

try {
  $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("DB Connection Failed: " . $e->getMessage());
}
