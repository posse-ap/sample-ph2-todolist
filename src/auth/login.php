<?php
// 絶対パスを使って取得したい
require __DIR__ . '/../dbconnect.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // バリデーション
  if (empty($_POST['email'])) {
    $message = 'メールアドレスは必須項目です。';
  } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $message = '正しいEメールアドレスを指定してください。';
  } else if (empty($_POST['password'])) {
    $message = 'パスワードは必須項目です。';
  } else {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // データベースへの接続
    $stmt = $dbh->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // ユーザーが存在し、パスワードが正しいか確認
    if ($user && password_verify($password, $user["password"])) {
      session_start();
      $_SESSION['id'] = $user["id"];
      $_SESSION['email'] = $user["email"];
      header('Location: ../index.php');
      exit();
    } else {
      // 認証失敗: エラーメッセージをセット
      $message = 'メールアドレスまたはパスワードが間違っています。';
    }
  }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログイン</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
  <?php include __DIR__ . '/../components/header.php'; ?>
  <div class="p-10">
    <div class="w-full flex justify-center items-center flex-col">
      <h1 class="mb-4">ログイン</h1>
      <form method="post" action="" class="w-1/2 mb-5 text-center">
        <?php if ($message !== '') : ?>
          <p style="color: red;"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <div class="mb-3 flex justify-center items-center gap-5">
          <label for="email">メール</label>
          <input type="email" name="email" id="email" class="border p-2 max-w-lg" required>
        </div>
        <div class="mb-3 flex justify-center items-center gap-5">
          <label for="password">パスワード</label>
          <input type="password" name="password" id="password" class="border p-2 max-w-lg" required>
        </div>
        <button type="submit" class="mt-3 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-40 text-center">
          ログイン
        </button>
      </form>
      <p>アカウントを持っていませんか？<a href="signup.php" class="text-blue-500">新規登録</a></p>
    </div>
  </div>
</body>

</html>