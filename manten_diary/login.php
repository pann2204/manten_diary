<?php
$error = $_GET['error'] ??'';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">満点ダイアリー</div>

<?php if ($error == '1'): ?>
<p class="error">UserIDまたはPasswordに誤りがあります</p>
<?php endif; ?>

<div class="title">ログインしてください</div>

<form method="post" action="login_check.php">
UserID <input type="text" name="userid"><br><br>
Password <input type="password" name="password"><br><br>
<input type="submit" value="ログイン">
</form>

<a href="register.php">新規登録</a>

</body>
</html>