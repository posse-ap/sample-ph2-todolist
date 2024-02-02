// 追加処理、更新処理、削除処理を addEventListener で指定
document.getElementById('js-create-todo').addEventListener('click', createTodo);

const completeButtons = document.querySelectorAll('.js-complete-todo');
completeButtons.forEach(button => {
  button.addEventListener('click', () => {
    const todoId = button.parentNode.getAttribute('data-id');
    updateTodo(todoId);
  });
});

const deleteButtons = document.querySelectorAll('.js-delete-todo');
deleteButtons.forEach(button => {
  button.addEventListener('click', () => {
    const todoId = button.parentNode.getAttribute('data-id');
    const parentNode = button.parentNode;
    deleteTodo(todoId, parentNode);
  });
});

const addTodoElement = (text, id) => {
  const template = document.getElementById('js-template').content.cloneNode(true);
  template.getElementById('js-todo-text').textContent = text;

  const todoElement = template.getElementById("js-todo-template");
  todoElement.setAttribute("data-id", id);

  const completeButton = template.getElementById('js-complete-todo-template');
  completeButton.setAttribute('data-id', id);
  completeButton.addEventListener('click', () => {
    updateTodo(id);
  });

  template.getElementById('js-edit-todo-template').href = `edit/index.php?id=${id}&text=${text}`;

  const deleteButton = template.getElementById('js-delete-todo-template');
  deleteButton.setAttribute('data-id', id);
  deleteButton.addEventListener('click', () => {
    deleteTodo(id, deleteButton.parentNode);
  });

  document.getElementById('js-todo-list').appendChild(template);
}

async function createTodo() {
  const todoInput = document.getElementById('js-todo-input');
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