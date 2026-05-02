<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$date = $_POST['diary_date'] ?? '';

$today = date('Y-m-d');
$yesterday = date('Y-m-d', strtotime('-1 day'));

if ($date != $today && $date != $yesterday) {
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>日付跨ぎエラー</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">満点ダイアリー</div>

<p class="error">日付跨ぎのエラーが発生しました</p>

<div class="title">日記を入力してください</div>

<a href="menu.php">メニューに戻る</a>

</body>
</html>
<?php
exit();
}

$item1 = 1;
$item2 = isset($_POST['item2']) ? 1 : 0;
$item3 = isset($_POST['item3']) ? 1 : 0;
$item4 = isset($_POST['item4']) ? 1 : 0;
$item5 = isset($_POST['item5']) ? 1 : 0;
$comment = $_POST['comment'] ?? '';

$point = ($item1 + $item2 + $item3 + $item4 + $item5) * 20;

$sql = "SELECT COUNT(*) FROM diaries WHERE user_id=? AND diary_date=?";
$stmt = $dbh->prepare($sql);
$stmt->execute([$user_id, $date]);
$count = $stmt->fetchColumn();

if ($count > 0) {
    $sql = "UPDATE diaries 
            SET item1=?, item2=?, item3=?, item4=?, item5=?, comment=?, point=?
            WHERE user_id=? AND diary_date=?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$item1, $item2, $item3, $item4, $item5, $comment, $point, $user_id, $date]);
} else {
    $sql = "INSERT INTO diaries
            (user_id, diary_date, item1, item2, item3, item4, item5, comment, point)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$user_id, $date, $item1, $item2, $item3, $item4, $item5, $comment, $point]);
}

header('Location: diary_done_view.php');
exit();
?>