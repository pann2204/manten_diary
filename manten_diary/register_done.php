<?php
session_start();
require 'db.php';

$username = $_SESSION['reg_username'] ?? '';
$userid   = $_SESSION['reg_userid'] ?? '';
$password = $_SESSION['reg_password'] ?? '';

if ($username == '' || $userid == '' || $password == '') {
    header('Location: register.php');
    exit();
}

$sql = "SELECT COUNT(*) FROM users WHERE userid=?";
$stmt = $dbh->prepare($sql);
$stmt->execute([$userid]);
$count = $stmt->fetchColumn();

if ($count > 0) {
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>登録エラー</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">満点ダイアリー</div>

<p class="error">このUserIDはすでに使われています</p>

<div class="title">必要事項を入力してください</div>

<a href="register.php">戻る</a>

</body>
</html>
<?php
exit();
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users(username, userid, password) VALUES(?, ?, ?)";
$stmt = $dbh->prepare($sql);
$stmt->execute([$username, $userid, $hash]);

unset($_SESSION['reg_username']);
unset($_SESSION['reg_userid']);
unset($_SESSION['reg_password']);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>登録完了</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">満点ダイアリー</div>

<div class="title">登録しました</div>

<div class="link-area">
    <a href="index.php">インデックス画面に戻る</a><br>
    <a href="login.php">ログイン画面に進む</a>
</div>

</body>
</html>