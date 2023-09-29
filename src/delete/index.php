<?php
require '../dbconnect.php';

$deleteId = $_GET['id'] ?? null;

if (!$deleteId) {
  header('Location: ../index.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $stmt = $dbh->prepare("DELETE FROM todos WHERE id = :id");
  $stmt->execute([':id' => $deleteId]);
  header('Location: ../index.php');
  exit;
}

$stmt = $dbh->prepare("SELECT text FROM todos WHERE id = :id");
$stmt->execute([':id' => $deleteId]);
$deleteText = $stmt->fetchColumn() ?? '';
?>