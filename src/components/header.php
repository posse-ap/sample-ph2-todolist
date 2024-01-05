<header class="h-16 bg-cyan-200 flex items-center justify-between px-4 py-6">
  <?php if (isset($_SESSION['email'])) { ?>
  <h1><?= $_SESSION['email']; ?>のTo-Do List</h1>
  <div>
    <form method="POST" action="/auth/logout.php">
      <button type="submit" class="cursor-pointer">ログアウト</button>
    </form>
  </div>
  <?php } else { ?>
  <h1>To-Do List</h1>
  <?php } ?>
</header>
