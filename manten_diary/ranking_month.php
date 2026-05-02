<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$year = $_GET['year'] ?? date('Y');
$month = $_GET['month'] ?? date('m');
$page = $_GET['page'] ?? 1;

$start = $year . '-' . $month . '-01';
$prev = date('Y-m', strtotime($start . ' -1 month'));
$next = date('Y-m', strtotime($start . ' +1 month'));
$today = date('Y-m');

$offset = ($page - 1) * 10;

$sql = "SELECT u.username, SUM(d.point) AS total
        FROM diaries d
        JOIN users u ON d.user_id = u.id
        WHERE YEAR(d.diary_date)=? AND MONTH(d.diary_date)=?
        GROUP BY d.user_id
        ORDER BY total DESC
        LIMIT 10 OFFSET $offset";

$stmt = $dbh->prepare($sql);
$stmt->execute([$year, $month]);
$rankings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>月間ランキング</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">満点ダイアリー</div>

<p class="welcome">
ようこそ　<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>さん
</p>

<div class="title"><?php echo $year; ?>年<?php echo (int)$month; ?>月の月間ランキング</div>

<div class="calendar-nav">
    <a href="ranking_month.php?year=<?php echo date('Y', strtotime($prev)); ?>&month=<?php echo date('m', strtotime($prev)); ?>">←前月</a>
    <a href="ranking_month.php">今月</a>

    <?php if ($next <= $today): ?>
        <a href="ranking_month.php?year=<?php echo date('Y', strtotime($next)); ?>&month=<?php echo date('m', strtotime($next)); ?>">翌月→</a>
    <?php else: ?>
        <span class="disabled">翌月→</span>
    <?php endif; ?>
</div>

<table class="ranking-table">
<tr>
<th>順位</th>
<th>ユーザ名</th>
<th>ポイント</th>
</tr>

<?php
$rank = $offset + 1;
foreach ($rankings as $row):
?>
<tr>
<td><?php echo $rank; ?></td>
<td><?php echo htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); ?></td>
<td><?php echo htmlspecialchars($row['total'], ENT_QUOTES, 'UTF-8'); ?></td>
</tr>
<?php
$rank++;
endforeach;
?>
</table>

<br>

<?php if ($page == 1): ?>
<a href="ranking_month.php?year=<?php echo $year; ?>&month=<?php echo $month; ?>&page=2">次の10位</a><br>
<?php else: ?>
<a href="ranking_month.php?year=<?php echo $year; ?>&month=<?php echo $month; ?>&page=1">前の10位</a><br>
<?php endif; ?>

<a href="ranking_year.php">年間ランキング</a><br>
<a href="menu.php">メニューに戻る</a>

</body>
</html>