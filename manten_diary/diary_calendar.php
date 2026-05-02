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
$last_day = date('t', strtotime($first_day));
$start_week = date('w', strtotime($first_day));

$prev = date('Y-m', strtotime($first_day . ' -1 month'));
$next = date('Y-m', strtotime($first_day . ' +1 month'));
$today = date('Y-m');

$sql = "SELECT id, diary_date FROM diaries
        WHERE user_id = ?
        AND YEAR(diary_date) = ?
        AND MONTH(diary_date) = ?";
$stmt = $dbh->prepare($sql);
$stmt->execute([$user_id, $year, $month]);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$days = [];
foreach ($data as $d) {
    $day_num = (int)date('j', strtotime($d['diary_date']));
    $days[$day_num] = $d['id'];
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>日記カレンダー</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">満点ダイアリー</div>

<p class="welcome">
ようこそ　<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>さん
</p>

<div class="title">日付を選んでください</div>

<div class="calendar-nav">
    <a href="diary_calendar.php?year=<?php echo date('Y', strtotime($prev)); ?>&month=<?php echo date('m', strtotime($prev)); ?>">←前月</a>

    <a href="diary_calendar.php">今月</a>

    <?php if ($next <= $today): ?>
        <a href="diary_calendar.php?year=<?php echo date('Y', strtotime($next)); ?>&month=<?php echo date('m', strtotime($next)); ?>">翌月→</a>
    <?php else: ?>
        <span class="disabled">翌月→</span>
    <?php endif; ?>
</div>

<h3><?php echo $year; ?>年<?php echo (int)$month; ?>月</h3>

<table class="calendar-table">
<tr>
    <th>日</th>
    <th>月</th>
    <th>火</th>
    <th>水</th>
    <th>木</th>
    <th>金</th>
    <th>土</th>
</tr>

<tr>
<?php
for ($i = 0; $i < $start_week; $i++) {
    echo '<td></td>';
}

for ($day = 1; $day <= $last_day; $day++) {
    echo '<td>';

    if (isset($days[$day])) {
        echo '<a href="diary_view.php?id=' . $days[$day] . '">' . $day . '</a>';
    } else {
        echo $day;
    }

    echo '</td>';

    if (($day + $start_week) % 7 == 0) {
        echo '</tr><tr>';
    }
}
?>
</tr>
</table>

<br>


<a href="diary_list.php">リスト表示に切り替える</a><br>
<a href="menu.php">メニューに戻る</a>

</body>
</html>