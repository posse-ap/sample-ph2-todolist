<header>
  <?php if (isset($_SESSION['email'])) { ?>
  <h1><?= $_SESSION['email']; ?>のTo-Do List</h1>
  <div>
    <form method="POST" action="/auth/logout.php">
      <input type="submit" value="ログアウト" class="submit"/>
    </form>
  </div>
  <?php } else { ?>
  <h1>To-Do List</h1>
  <?php }  ?>
</header>

<style>
header {
  height: 60px;
  background-color: #add8e6;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 15px 24px;
}

.submit {
  cursor: pointer;
}
</style>