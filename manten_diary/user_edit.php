<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM users WHERE id=?";
$stmt = $dbh->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$error = $_GET['error'] ?? '';
$message = '';

if ($error == 'name') {
    $message = 'ユーザ名を入力してください';
} elseif ($error == 'pass_empty') {
    $message = 'Password を入力してください';
} elseif ($error == 'pass2_empty') {
    $message = 'Password(再入力) を入力してください';
} elseif ($error == 'pass_mismatch') {
    $message = 'Password と Password(再入力)には同じものを入力してください';
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>ユーザ情報変更</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">満点ダイアリー</div>

<?php if ($message != ''): ?>
<p class="error">
<?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
</p>
<?php endif; ?>

<div class="title">ユーザ情報を変更してください</div>

<form method="post" action="user_edit_check.php">

<table class="form-table">
<tr>
<td>ユーザ名</td>
<td>
<input type="text" name="username"
value="<?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?>">
</td>
</tr>

<tr>
<td></td>
<td>※ユーザ名は画面上に表示されます</td>
</tr>

<tr>
<td>UserID</td>
<td>
<input type="text"
value="<?php echo htmlspecialchars($user['userid'], ENT_QUOTES, 'UTF-8'); ?>" disabled>
</td>
</tr>

<tr>
<td></td>
<td>※UserIDは変更できません</td>
</tr>

<tr>
<td>Password</td>
<td><input type="password" name="password"></td>
</tr>

<tr>
<td>Password(再入力)</td>
<td><input type="password" name="password2"></td>
</tr>
</table>

<input type="submit" value="登録" class="center-btn">

</form>

<br>
<a href="menu.php">メニューに戻る</a>

</body>
</html>