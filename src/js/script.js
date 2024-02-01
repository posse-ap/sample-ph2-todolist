// 追加処理、更新処理、削除処理を addEventListener で指定
document.querySelector(".js-create-todo").addEventListener("click", createTodo);

const completeButtons = document.getElementsByClassName("js-complete-todo");
for (let i = 0; i < completeButtons.length; i++) {
  const button = completeButtons[i];
  button.addEventListener("click", () => {
    const todoId = button.parentNode.getAttribute("data-id");
    updateTodo(todoId);
  });
}

const deleteButtons = document.getElementsByClassName("js-delete-todo");
for (let i = 0; i < deleteButtons.length; i++) {
  const button = deleteButtons[i];
  button.addEventListener("click", () => {
    const todoId = button.parentNode.getAttribute("data-id");
    const parentNode = button.parentNode;
    deleteTodo(todoId, parentNode);
  });
}

const addTodoElement = (text, id) => {
  const template = document.querySelector("template").content.cloneNode(true);
  template.querySelector(".js-todo-text").textContent = text;

  const completeButton = template.querySelector(".js-complete-todo-template");
  completeButton.setAttribute("data-id", id);
  completeButton.addEventListener("click", () => {
    updateTodo(id);
  });

  template.querySelector(
    ".js-edit-link"
  ).href = `edit/index.php?id=${id}&text=${text}`;

  const deleteButton = template.querySelector(".js-delete-todo-template");
  deleteButton.setAttribute("data-id", id);
  deleteButton.addEventListener("click", () => {
    deleteTodo(id, deleteButton.parentNode);
  });

  document.querySelector(".js-todo-list").appendChild(template);
};

async function createTodo() {
  const todoInput = document.querySelector(".js-todo-text");
  const todoText = todoInput.value;

  try {
    const response = await fetch("./create/index.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `todo-text=${todoText}`,
    });

    if (!response.ok) {
      const errorText = await response.text();
      throw new Error("Error from server: " + errorText);
    }

    const data = await response.json();
    addTodoElement(todoText, data.id);

    todoInput.value = "";
  } catch (error) {
    alert("Error: " + error.message);
  }
}

const updateTodoElement = (id, isCompleted) => {
  const todoElement = document.querySelector(`.js-todo[data-id="${id}"]`);

  if (todoElement) {
    const completeButton = todoElement.querySelector(".js-complete-todo");
    completeButton.textContent = isCompleted ? "Undo" : "Complete";
  }
};

async function updateTodo(id) {
  try {
    const response = await fetch("./update/index.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `toggle-id=${id}`,
    });

    if (!response.ok) {
      const errorText = await response.text();
      throw new Error("Error from server: " + errorText);
    }

    const data = await response.json();
    updateTodoElement(id, data.completed);
  } catch (error) {
    alert("Error: " + error.message);
  }
}

async function deleteTodo(id, element) {
  try {
    const response = await fetch("./delete/index.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `delete-id=${id}`,
    });

    if (!response.ok) {
      const errorText = await response.text();
      throw new Error("Error from server: " + errorText);
    }

    element.remove();
  } catch (error) {
    alert("Error: " + error.message);
  }
}
