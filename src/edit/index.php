<?php
require __DIR__ . '/../dbconnect.php';

session_start();

if (!isset($_SESSION['id'])) {
  header('Location: /auth/login.php');
  exit;
}

if (!$_GET['id']) {
  header('Location: ../index.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $text = trim($_POST['text']);
  if ($text) {
    $stmt = $dbh->prepare("UPDATE todos SET text = :text WHERE id = :id");
    $stmt->bindValue(':text', $text);
    $stmt->bindValue(':id', $_GET['id']);
    $stmt->execute();
    header('Location: ../index.php');
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update ToDo List</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
  <?php include __DIR__ . '/../components/header.php'; ?>
  <div class="p-10">
    <div class="w-full flex justify-center items-center flex-col">
      <form method="post" class="mb-5 text-center">
        <input name="text" value="<?= htmlspecialchars($_GET['text']) ?>" class="border p-2 w-full max-w-lg" type="text" placeholder="ToDoを編集してください" />
        <button type="submit" class="mt-3 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-40 text-center">
          更新
        </button>
      </form>
    </div>
  </div>
</body>

</html>