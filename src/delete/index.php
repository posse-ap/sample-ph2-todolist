<?php
require '../dbconnect.php';

session_start();

if (!isset($_SESSION['id'])) {
  header('Location: /auth/login.php');
} else {
  if (!$_GET['id']) {
    header('Location: ../index.php');
    exit;
  }
  
  $stmt = $dbh->prepare("DELETE FROM todos WHERE id = :id");
  $stmt->bindValue(':id', $_GET['id']);
  $stmt->execute();
  
  header('Location: ../index.php');
  exit;
}
