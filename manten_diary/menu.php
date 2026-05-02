<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>メニュー</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">満点ダイアリー</div>

<p class="welcome">
ようこそ　<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>さん
</p>

<div class="title">メニューより選んでください</div>

<a class="btn" href="diary_add.php">日記を入力する</a>
<a class="btn" href="diary_calendar.php">過去の日記を見る</a>
<a class="btn" href="ranking_month.php">ランキングを見る</a>
<a class="btn" href="user_edit.php">ユーザ情報を変更する</a>
<a class="btn" href="logout.php">ログアウトする</a>

</body>
</html>