<?php
session_start();

$username  = $_POST['username'] ?? '';
$userid    = $_POST['userid'] ?? '';
$password  = $_POST['password'] ?? '';
$password2 = $_POST['password2'] ?? '';

$error = '';

if ($username == '') {
    $error = 'ユーザ名を入力してください';
} elseif ($userid == '') {
    $error = 'UserIDを入力してください';
} elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $userid)) {
    $error = 'UserIDは半角英数字記号で入力してください';
} elseif ($password == '') {
    $error = 'Passwordを入力してください';
} elseif ($password != $password2) {
    $error = 'PasswordとPassword(再入力)には同じものを入力してください';
}

if ($error != '') {
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>新規登録</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">満点ダイアリー</div>

<p class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>

<div class="title">必要事項を入力してください</div>

<form method="post" action="register_check.php">

<table class="form-table">
<tr>
<td>ユーザ名</td>
<td>
<input type="text" name="username"
value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>">
</td>
</tr>

<tr>
<td></td>
<td>※ユーザ名は画面上に表示されます</td>
</tr>

<tr>
<td>UserID</td>
<td>
<input type="text" name="userid"
value="<?php echo htmlspecialchars($userid, ENT_QUOTES, 'UTF-8'); ?>">
</td>
</tr>

<tr>
<td></td>
<td>※UserIDは半角英数字記号です<br>（記号は"_"のみ使用可能）</td>
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
<a href="index.php">インデックス画面に戻る</a>

</body>
</html>
<?php
    exit();
}

$_SESSION['reg_username'] = $username;
$_SESSION['reg_userid'] = $userid;
$_SESSION['reg_password'] = $password;

header('Location: register_done.php');
exit();
?>