<?php
require '../dbconnect.php';

session_start();

if (!isset($_SESSION['id'])) {
  header('Location: /auth/login.php');
  exit;
} else {
  if (isset($_POST['delete-id'])) {
    try {
      $stmt = $dbh->prepare("DELETE FROM todos WHERE id = :id");
      $stmt->bindValue(':id', $_POST['delete-id']);
      $stmt->execute();
    } catch (PDOException $e) {
      header('HTTP/1.1 500 Internal Server Error');
      echo 'Database error: ' . $e->getMessage();
      exit;
    }
  } else {
    header('HTTP/1.1 400 Bad Request');
    echo 'Bad Request: delete-id is missing';
    exit;
  }
}
