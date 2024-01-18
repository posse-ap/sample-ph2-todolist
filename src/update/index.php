<?php
require __DIR__ . '/../dbconnect.php';

session_start();

if (!isset($_SESSION['id'])) {
  header('Location: /auth/login.php');
  exit;
}

if (!$_POST['toggle-id']) {
  header('Location: ../index.php');
  exit;
}

try {
  $stmt = $dbh->prepare("UPDATE todos SET completed = NOT completed WHERE id = :id");
  $stmt->bindValue(':id', $_POST['toggle-id']);
  $stmt->execute();

  $stmt = $dbh->prepare("SELECT completed FROM todos WHERE id = :id");
  $stmt->bindValue(':id', $_POST['toggle-id']);
  $stmt->execute();
  $result = $stmt->fetch();

  echo json_encode(['completed' => $result['completed']]);
} catch (PDOException $e) {
  header('HTTP/1.1 500 Internal Server Error');
  exit;
}
