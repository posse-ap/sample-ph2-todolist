<?php
require '../dbconnect.php';

if (!$_GET['id']) {
  header('Location: ../index.php');
  exit;
}

$stmt = $dbh->prepare("DELETE FROM todos WHERE id = :id");
$stmt->execute([':id' => $_GET['id']]);

header('Location: ../index.php');
exit;
?>