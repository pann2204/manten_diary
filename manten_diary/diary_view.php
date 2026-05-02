<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$id = $_GET['id'] ?? '';

$sql = "SELECT * FROM diaries WHERE id=? AND user_id=?";
$stmt = $dbh->prepare($sql);
$stmt->execute([$id, $user_id]);
$diary = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$diary) {
    echo "日記データがありません。<br>";
    echo "<a href='diary_list.php'>一覧に戻る</a>";
    exit();
}

$item1 = $diary['item1'];
$item2 = $diary['item2'];
$item3 = $diary['item3'];
$item4 = $diary['item4'];
$item5 = $diary['item5'];
$comment = $diary['comment'];

$date_text = date('Y年n月j日(D)', strtotime($diary['diary_date']));
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>日記詳細</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">満点ダイアリー</div>

<p class="welcome">
ようこそ　<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>さん
</p>

<div class="title"><?php echo $date_text; ?></div>

<div class="form-area">

<label>
<input type="checkbox" <?php if ($item1) echo 'checked'; ?> disabled>
毎日日記を付ける
</label><br>

<label>
<input type="checkbox" <?php if ($item2) echo 'checked'; ?> disabled>
時間通りに起きる
</label><br>

<label>
<input type="checkbox" <?php if ($item3) echo 'checked'; ?> disabled>
遅刻をしない
</label><br>

<label>
<input type="checkbox" <?php if ($item4) echo 'checked'; ?> disabled>
栄養バランスを考えた食事をする
</label><br>

<label>
<input type="checkbox" <?php if ($item5) echo 'checked'; ?> disabled>
ちゃんとした挨拶をする
</label>

<p>今日の一言</p>
<textarea readonly><?php echo htmlspecialchars($comment, ENT_QUOTES, 'UTF-8'); ?></textarea>

</div>

<br>

<a href="diary_list.php">一覧に戻る</a><br>
<a href="menu.php">メニューに戻る</a>

</body>
</html>