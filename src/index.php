<?php
require 'dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['todo-text'])) {
    $stmt = $dbh->prepare('INSERT INTO todos (text) VALUES (:text)');
    $stmt->bindValue(':text', $_POST['todo-text']);
    $stmt->execute();
  }
  header('Location: index.php');
  exit;
}

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
        <input name="todo-text" class="border p-2 w-full max-w-lg" type="text" placeholder="新しいToDoを入力してください" />
        <button type="submit" class="mt-3 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-40 text-center">
          追加
        </button>
      </form>
      <ul class="space-y-4 text-center">
        <?php foreach ($todos as $todo) : ?>
          <li class="flex items-center justify-center">
            <?= htmlspecialchars($todo['text']) ?>
            <form method="post" action="./update/index.php" class="inline">
              <input type="hidden" name="toggle-id" value="<?= $todo['id'] ?>">
              <button type="submit" class="ml-2 px-3 py-1 <?= $todo['completed'] ? 'bg-blue-500 hover:bg-blue-700' : 'bg-blue-500 hover:bg-blue-700' ?> text-white font-bold rounded">
                <?= $todo['completed'] ? 'Undo' : 'Complete' ?>
              </button>
            </form>
            <a href="edit/index.php?id=<?= $todo['id'] ?>&text=<?= $todo['text'] ?>" class="ml-2 px-3 py-1 bg-yellow-500 hover:bg-yellow-700 text-white font-bold rounded">Edit</a>
            <a href="delete/index.php?id=<?= $todo['id'] ?>" class="ml-2 px-3 py-1 bg-red-500 hover:bg-red-600 text-white font-bold rounded">Delete</a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</body>

</html>