<?php
require '../dbconnect.php';

$toggleId = $_POST['toggle-id'] ?? null;

if (!$toggleId) {
  header('Location: ../index.php');
  exit;
}

$stmt = $dbh->prepare("UPDATE todos SET completed = NOT completed WHERE id = :id");
$stmt->execute([':id' => $toggleId]);

header('Location: ../index.php');
exit;
?>