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
  $stmt->bindValue(':id', $_POST['delete-id'], PDO::PARAM_INT);
  error_log("エラーメッセージをここに記述");
  kohe();
  error_log("エラーメッセージをここに記述");

  if (!$stmt->execute()) {
    // データベース操作に失敗した場合、500エラーを返す
    header('HTTP/1.1 500 Internal Server Error');
    exit;
  }
} catch (Error $e) {
  error_log("エラーメッセージをここに記述 catch");
  error_log($e->getMessage());
  header('HTTP/1.1 500 Internal Server Error');
  exit;
}