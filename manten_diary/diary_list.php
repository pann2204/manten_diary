<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$year = $_GET['year'] ?? date('Y');
$month = $_GET['month'] ?? date('m');

$first_day = $year . '-' . $month . '-01';
$prev = date('Y-m', strtotime($first_day . ' -1 month'));
$next = date('Y-m', strtotime($first_day . ' +1 month'));
$today = date('Y-m');

$sql = "SELECT * FROM diaries
        WHERE user_id=?
        AND YEAR(diary_date)=?
        AND MONTH(diary_date)=?
        ORDER BY diary_date ASC";
$stmt = $dbh->prepare($sql);
$stmt->execute([$user_id, $year, $month]);
$diaries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>日記リスト</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">満点ダイアリー</div>

<p class="welcome">
ようこそ　<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>さん
</p>

<div class="title">日付を選んでください</div>

<div class="calendar-nav">
    <a href="diary_list.php?year=<?php echo date('Y', strtotime($prev)); ?>&month=<?php echo date('m', strtotime($prev)); ?>">←前月</a>

    <a href="diary_list.php">今月</a>

    <?php if ($next <= $today): ?>
        <a href="diary_list.php?year=<?php echo date('Y', strtotime($next)); ?>&month=<?php echo date('m', strtotime($next)); ?>">翌月→</a>
    <?php else: ?>
        <span class="disabled">翌月→</span>
    <?php endif; ?>
</div>

<div class="calendar-title">
    <?php echo $year; ?>年<?php echo (int)$month; ?>月
</div>

<table class="diary-table">
<tr>
<th>日付</th>
<th>点数</th>
<th>今日の一言</th>
</tr>

<?php foreach ($diaries as $diary): ?>
<tr>
<td>
<a href="diary_view.php?id=<?php echo $diary['id']; ?>&date=<?php echo $diary['diary_date']; ?>">
<?php echo date('j', strtotime($diary['diary_date'])); ?>
</a>
</td>
<td><?php echo htmlspecialchars($diary['point'], ENT_QUOTES, 'UTF-8'); ?></td>
<td><?php echo htmlspecialchars($diary['comment'], ENT_QUOTES, 'UTF-8'); ?></td>
</tr>
<?php endforeach; ?>
</table>

<br>

<a href="diary_calendar.php?year=<?php echo $year; ?>&month=<?php echo $month; ?>">カレンダー表示に切り替える</a><br>
<a href="menu.php">メニューに戻る</a>

</body>
</html>