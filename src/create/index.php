<?php
require '../dbconnect.php';

session_start();

if (!isset($_SESSION['id'])) {
  header('Location: /auth/login.php');
} else {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['todo-text'])) {
      $stmt = $dbh->prepare('INSERT INTO todos (text) VALUES (:text)');
      $stmt->bindValue(':text', $_POST['todo-text']);
      $stmt->execute();
    }
    header('Location: ../index.php');
    exit;
  }
}
