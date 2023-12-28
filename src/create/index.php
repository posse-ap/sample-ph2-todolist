<?php
require '../dbconnect.php';

session_start();

if (!isset($_SESSION['id'])) {
  header('Location: /auth/login.php');
  exit;
}

if (isset($_POST['todo-text'])) {
  try {
    $stmt = $dbh->prepare('INSERT INTO todos (user_id, text) VALUES (:user_id, :text)');
    $stmt->bindValue(':user_id', $_SESSION['id']);
    $stmt->bindValue(':text', $_POST['todo-text']);
    $stmt->execute();

    $newlyInsertedId = $dbh->lastInsertId();
    echo json_encode(['id' => $newlyInsertedId]);
  } catch (PDOException $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo 'Database error: ' . $e->getMessage();
    exit;
  }
} else {
  header('HTTP/1.1 400 Bad Request');
  echo 'Bad Request: todo-text is missing';
  exit;
}
