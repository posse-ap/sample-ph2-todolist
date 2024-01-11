<?php
require __DIR__ . '/../dbconnect.php';

session_start();

if (!isset($_SESSION['id'])) {
  // dirname を使ってlocation を指定したい
  header('Location: /auth/login.php');
  exit;
}

if (!isset($_POST['delete-id'])) {
  header('HTTP/1.1 400 Bad Request');
  echo 'Bad Request: delete-id is missing';
  exit;
}

try {
  $stmt = $dbh->prepare("DELETE FROM todos WHERE id = :id");
  $stmt->bindValue(':id', $_POST['delete-id']);
  $stmt->execute();
} catch (PDOException $e) {
  header('HTTP/1.1 500 Internal Server Error');
  echo 'Database error: ' . $e->getMessage();
  exit;
}
