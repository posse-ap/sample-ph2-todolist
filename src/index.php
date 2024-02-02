<?php
require __DIR__ . '/dbconnect.php';

session_start();

if (!isset($_SESSION['id'])) {
  header('Location: /auth/login.php');
  exit;
}

$userId = $_SESSION['id'];
$todos = $dbh->prepare("SELECT * FROM todos WHERE user_id = :user_id");
$todos->bindValue(':user_id', $userId);
$todos->execute();
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
  <?php include __DIR__ . '/components/header.php'; ?>
  <div class="p-10">
    <div class="w-full flex justify-center items-center flex-col">
      <div class="mb-5 text-center">
        <input name="todo-text" class="border p-2 w-full max-w-lg js-todo-text" type="text" placeholder="新しいToDoを入力してください" />
        <button type="button" class="mt-3 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-40 text-center js-create-todo">
          追加
        </button>
      </div>
      <ul class="space-y-4 text-center js-todo-list">
        <?php foreach ($todos as $todo) : ?>
          <li class="flex items-center justify-center js-todo" data-id="<?= $todo['id'] ?>">
            <?= $todo['text'] ?>
            <button type="button" class="ml-2 px-3 py-1 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded js-complete-todo">
              <?= $todo['completed'] ? 'Undo' : 'Complete' ?>
            </button>
            <a href="edit/index.php?id=<?= $todo['id'] ?>&text=<?= $todo['text'] ?>" class="ml-2 px-3 py-1 bg-yellow-500 hover:bg-yellow-700 text-white font-bold rounded">Edit</a>
            <button type="button" class="ml-2 px-3 py-1 bg-red-500 hover:bg-red-600 text-white font-bold rounded js-delete-todo">
              Delete
            </button>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
  <template>
    <li class="flex items-center justify-center js-todo">
      <span class="js-todo-text"></span>
      <button type="button" class="ml-2 px-3 py-1 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded js-complete-todo-template" data-id="">
        Complete
      </button>
      <a href="" class="ml-2 px-3 py-1 bg-yellow-500 hover:bg-yellow-700 text-white font-bold rounded js-edit-link">Edit</a>
      <button type="button" class="ml-2 px-3 py-1 bg-red-500 hover:bg-red-600 text-white font-bold rounded js-delete-todo-template" data-id="">
        Delete
      </button>
    </li>
  </template>
  <script src="./js/script.js"></script>
</body>

</html>
