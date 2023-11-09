<?php
require '../dbconnect.php';

session_start();

if (!isset($_SESSION['id'])) {
  header('Location: /auth/login.php');
} else {
  if (!$_POST['toggle-id']) {
    header('Location: ../index.php');
    exit;
  }
  
  $stmt = $dbh->prepare("UPDATE todos SET completed = NOT completed WHERE id = :id");
  $stmt->bindValue(':id', $_POST['toggle-id']);
  $stmt->execute();
  
  header('Location: ../index.php');
  exit;
}
