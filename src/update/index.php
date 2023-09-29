<?php
require '../dbconnect.php';

if (!$_POST['toggle-id']) {
  header('Location: ../index.php');
  exit;
}

$stmt = $dbh->prepare("UPDATE todos SET completed = NOT completed WHERE id = :id");
$stmt->execute([':id' => $_POST['toggle-id']]);

header('Location: ../index.php');
exit;
?>