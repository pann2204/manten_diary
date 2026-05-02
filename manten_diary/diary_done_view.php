<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>日記入力完了</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">満点ダイアリー</div>

<p class="welcome">
ようこそ　
<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>さん
</p>

<div class="title">登録しました</div>

<div class="link-area">
    <a href="menu.php">メニューに戻る</a>
</div>

</body>
</html>