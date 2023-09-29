<?php
require 'dbconnect.php';

$editId = null;
$editText = '';

// 新しいToDoを追加、または既存のToDoを更新
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $text = trim($_POST['todo-text']);
  if ($text) {
    if (isset($_POST['edit-id'])) {
      $stmt = $dbh->prepare("UPDATE todos SET text = :text WHERE id = :id");
      $stmt->execute([':text' => $text, ':id' => $_POST['edit-id']]);
    } else {
      $stmt = $dbh->prepare("INSERT INTO todos (text) VALUES (:text)");
      $stmt->execute([':text' => $text]);
    }
    header('Location: index.php');
    exit;
  }
}

// ToDoの状態を変更 (completed)
if (isset($_GET['toggle']) && isset($_GET['id'])) {
  $id = (int)$_GET['id'];
  $stmt = $dbh->prepare("UPDATE todos SET completed = NOT completed WHERE id = :id");
  $stmt->execute([':id' => $id]);
  header('Location: index.php');
  exit;
}

// ToDoを削除
if (isset($_GET['delete']) && isset($_GET['id'])) {
  $id = (int)$_GET['id'];
  $stmt = $dbh->prepare("DELETE FROM todos WHERE id = :id");
  $stmt->execute([':id' => $id]);
  header('Location: index.php');
  exit;
}

// すべてのToDoを取得
$stmt = $dbh->query("SELECT * FROM todos");
$todos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ToDo List</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
  <div class="p-10">
    <div class="w-full flex justify-center items-center flex-col">
      <form method="post" class="mb-5 text-center">
        <input name="todo-text" value="<?= htmlspecialchars($editText) ?>" class="border p-2 w-full max-w-lg" type="text" placeholder="新しいToDoを入力してください" />
        <?php if ($editId) : ?>
          <input type="hidden" name="edit-id" value="<?= $editId ?>">
        <?php endif; ?>
        <button type="submit" class="mt-3 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-40 text-center">
          <?= $editId ? '更新' : '追加' ?>
        </button>
      </form>
      <ul class="space-y-4 text-center">
        <?php foreach ($todos as $todo) : ?>
          <li class="flex items-center">
            <?= htmlspecialchars($todo['text']) ?>
            <a href="index.php?toggle=1&id=<?= $todo['id'] ?>" class="ml-2 px-3 py-1 <?= $todo['completed'] ? 'bg-blue-500 hover:bg-blue-700' : 'bg-blue-500 hover:bg-blue-700' ?> text-white font-bold rounded">
              <?= $todo['completed'] ? 'Undo' : 'Complete' ?>
            </a>
            <a href="edit/index.php?id=<?= $todo['id'] ?>" class="ml-2 px-3 py-1 bg-yellow-500 hover:bg-yellow-700 text-white font-bold rounded">Edit</a>
            <a href="index.php?delete=1&id=<?= $todo['id'] ?>" class="ml-2 px-3 py-1 bg-red-500 hover:bg-red-600 text-white font-bold rounded">Delete</a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</body>

</html>