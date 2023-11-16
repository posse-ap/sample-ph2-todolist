<?php
require '../dbconnect.php';

session_start();

if (!isset($_SESSION['id'])) {
  header('Location: /auth/login.php');
  exit;
} 

if (!isset($_POST['delete-id'])) {
  header('Location: ../index.php');
  exit;
}

try {
  $stmt = $dbh->prepare("DELETE FROM todos WHERE id = :id");
  $stmt->bindValue(':id', $_POST['delete-id'], PDO::PARAM_INT)
  $stmt->execute();
} catch (PDOException $e) {
  header('HTTP/1.1 500 Internal Server Error');
  echo 'Database error: ' . $e->getMessage();
  exit;
}
