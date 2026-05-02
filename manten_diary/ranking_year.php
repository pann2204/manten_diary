<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$year = $_GET['year'] ?? date('Y');
$page = $_GET['page'] ?? 1;

$prev = $year - 1;
$next = $year + 1;
$today_year = date('Y');

$limit = 10;
$offset = ($page - 1) * $limit;

// 🔥 total users count
$count_sql = "SELECT COUNT(*) FROM (
    SELECT d.user_id
    FROM diaries d
    WHERE YEAR(d.diary_date)=?
    GROUP BY d.user_id
) AS ranking_count";

$count_stmt = $dbh->prepare($count_sql);
$count_stmt->execute([$year]);
$total_users = $count_stmt->fetchColumn();

// 🔥 ranking data
$sql = "SELECT u.username, SUM(d.point) AS total
        FROM diaries d
        JOIN users u ON d.user_id = u.id
        WHERE YEAR(d.diary_date)=?
        GROUP BY d.user_id
        ORDER BY total DESC
        LIMIT $limit OFFSET $offset";

$stmt = $dbh->prepare($sql);
$stmt->execute([$year]);
$rankings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>年間ランキング</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">満点ダイアリー</div>

<p class="welcome">
ようこそ　<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>さん
</p>

<div class="title"><?php echo $year; ?>年の年間ランキング</div>

<!-- 年 navigation -->
<div class="calendar-nav">
    <a href="ranking_year.php?year=<?php echo $prev; ?>&page=1">←前年</a>

    <a href="ranking_year.php">今年</a>

    <?php if ($next <= $today_year): ?>
        <a href="ranking_year.php?year=<?php echo $next; ?>&page=1">翌年→</a>
    <?php else: ?>
        <span class="disabled">翌年→</span>
    <?php endif; ?>
</div>

<!-- 前の10位 -->
<?php if ($page > 1): ?>
<a href="ranking_year.php?year=<?php echo $year; ?>&page=<?php echo $page - 1; ?>">前の10位</a><br>
<?php endif; ?>

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

<!-- 次の10位 -->
<?php if ($offset + $limit < $total_users): ?>
<a href="ranking_year.php?year=<?php echo $year; ?>&page=<?php echo $page + 1; ?>">次の10位</a><br>
<?php endif; ?>

<br>

<a href="ranking_month.php">月間ランキングに戻る</a><br>
<a href="menu.php">メニューに戻る</a>

</body>
</html>