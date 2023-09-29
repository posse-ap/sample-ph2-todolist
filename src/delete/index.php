<?php
require '../dbconnect.php';

$deleteId = $_GET['id'] ?? null;

if (!$deleteId) {
  header('Location: ../index.php');
  exit;
}

$stmt = $dbh->prepare("DELETE FROM todos WHERE id = :id");
$stmt->execute([':id' => $deleteId]);

header('Location: ../index.php');
exit;
?>