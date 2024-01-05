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
        <input id="todo-text" name="todo-text" class="border p-2 w-full max-w-lg" type="text" placeholder="新しいToDoを入力してください" />
        <button type="button" onclick="createTodo()" class="mt-3 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-40 text-center">
          追加
        </button>
      </div>
      <ul class="space-y-4 text-center js-todo-list">
        <?php foreach ($todos as $todo) : ?>
          <li class="flex items-center justify-center js-todo" data-id="<?= $todo['id'] ?>">
            <?= $todo['text'] ?>
            <button type="button" onclick="updateTodo(<?= $todo['id'] ?>)" class="ml-2 px-3 py-1 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded js-complete-todo">
              <?= $todo['completed'] ? 'Undo' : 'Complete' ?>
            </button>
            <a href="edit/index.php?id=<?= $todo['id'] ?>&text=<?= $todo['text'] ?>" class="ml-2 px-3 py-1 bg-yellow-500 hover:bg-yellow-700 text-white font-bold rounded">Edit</a>
            <button type="button" onclick="deleteTodo(<?= $todo['id'] ?>, this.parentNode)" class="ml-2 px-3 py-1 bg-red-500 hover:bg-red-600 text-white font-bold rounded">
              Delete
            </button>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
  <template>
    <li class="flex items-center justify-center">
      <span class="js-todo-text"></span>
      <form method="post" action="./update/index.php" class="inline">
        <input type="hidden" name="toggle-id" value="">
        <button type="submit" class="ml-2 px-3 py-1 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded">
          Complete
        </button>
      </form>
      <a href="" class="ml-2 px-3 py-1 bg-yellow-500 hover:bg-yellow-700 text-white font-bold rounded js-edit-link">Edit</a>
      <button type="button" class="ml-2 px-3 py-1 bg-red-500 hover:bg-red-600 text-white font-bold rounded js-delete-todo" data-id="">
        Delete
      </button>
    </li>
  </template>
</body>

<script>
  const addTodoElement = (text, id) => {
    const template = document.querySelector('template').content.cloneNode(true);
    template.querySelector('.js-todo-text').textContent = text;

    // 編集用のリンクを設定
    template.querySelector('.js-edit-link').href = `edit/index.php?id=${id}&text=${text}`;

    // 削除ボタンの設定
    const deleteButton = template.querySelector('.js-delete-todo');
    deleteButton.setAttribute('data-id', id);
    deleteButton.addEventListener('click', () => {
      deleteTodo(id, deleteButton.parentNode);
    });

    // 元のリストに追加
    document.querySelector('.js-todo-list').appendChild(template);
  }

  async function createTodo() {
    const todoInput = document.getElementById('todo-text');
    const todoText = todoInput.value;

    try {
      const response = await fetch('./create/index.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `todo-text=${todoText}`
      });

      if (!response.ok) {
        const errorText = await response.text();
        throw new Error('Error from server: ' + errorText);
      }

      const data = await response.json();
      addTodoElement(todoText, data.id);

      todoInput.value = '';
    } catch (error) {
      alert('Error: ' + error.message);
    }
  }

  const updateTodoElement = (id, isCompleted) => {
    const todoElement = document.querySelector(`.js-todo[data-id="${id}"]`);

    if (todoElement) {
      const completeButton = todoElement.querySelector('.js-complete-todo');
      completeButton.textContent = isCompleted ? 'Undo' : 'Complete';
    }
  }

  async function updateTodo(id) {
    try {
      const response = await fetch('./update/index.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `toggle-id=${id}`
      });

      if (!response.ok) {
        const errorText = await response.text();
        throw new Error('Error from server: ' + errorText);
      }

      const data = await response.json();
      updateTodoElement(id, data.completed);

    } catch (error) {
      alert('Error: ' + error.message);
    }
  }

  async function deleteTodo(id, element) {
    try {
      const response = await fetch('./delete/index.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `delete-id=${id}`
      });

      if (!response.ok) {
        const errorText = await response.text();
        throw new Error('Error from server: ' + errorText);
      }

      element.remove();
    } catch (error) {
      alert('Error: ' + error.message);
    }
  }
</script>

</html>
