<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$date = $_GET['date'] ?? date('Y-m-d');

$today = date('Y-m-d');
$yesterday = date('Y-m-d', strtotime('-1 day'));

if ($date != $today && $date != $yesterday) {
    echo "入力できる日記は当日または前日だけです。<br>";
    echo "<a href='menu.php'>メニューに戻る</a>";
    exit();
}

$sql = "SELECT * FROM diaries WHERE user_id=? AND diary_date=?";
$stmt = $dbh->prepare($sql);
$stmt->execute([$user_id, $date]);
$diary = $stmt->fetch(PDO::FETCH_ASSOC);

$item2 = $diary['item2'] ?? 0;
$item3 = $diary['item3'] ?? 0;
$item4 = $diary['item4'] ?? 0;
$item5 = $diary['item5'] ?? 0;
$comment = $diary['comment'] ?? '';

$title_date = date('n月j日', strtotime($date));
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>日記入力</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">満点ダイアリー</div>

<p class="welcome">ようこそ　<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>さん</p>

<div class="title"><?php echo $title_date; ?>の日記を入力してください</div>

<form method="post" action="diary_done.php">

<input type="hidden" name="diary_date" value="<?php echo $date; ?>">
<input type="hidden" name="item1" value="1">

<div class="form-area">

<label>
    <input type="checkbox" checked disabled>
    毎日日記を付ける
</label>

<label>
    <input type="checkbox" name="item2" value="1" <?php if ($item2) echo 'checked'; ?>>
    時間通りに起きる
</label>

<label>
    <input type="checkbox" name="item3" value="1" <?php if ($item3) echo 'checked'; ?>>
    遅刻をしない
</label>

<label>
    <input type="checkbox" name="item4" value="1" <?php if ($item4) echo 'checked'; ?>>
    栄養バランスを考えた食事をする
</label>

<label>
    <input type="checkbox" name="item5" value="1" <?php if ($item5) echo 'checked'; ?>>
    ちゃんとした挨拶をする
</label>

<p>今日の一言(空白可)</p>
<textarea name="comment"><?php echo htmlspecialchars($comment, ENT_QUOTES, 'UTF-8'); ?></textarea>

<input type="submit" value="送信">

</div>
</form>

<br>

<?php if ($date == $today): ?>
    <a href="diary_add.php?date=<?php echo $yesterday; ?>">←前日の日記を付ける</a><br>
<?php else: ?>
    <a href="diary_add.php?date=<?php echo $today; ?>">翌日の日記を付ける→</a><br>
<?php endif; ?>

<a href="menu.php">メニューに戻る</a>

</body>
</html>