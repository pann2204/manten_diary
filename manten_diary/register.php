<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>新規登録</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">満点ダイアリー</div>

<div class="title">必要事項を入力してください</div>

<form method="post" action="register_check.php">
<table class="form-table">
<tr>
<td>ユーザ名</td>
<td><input type="text" name="username"></td>
</tr>
<tr>
<td></td>
<td>※ユーザ名は画面上に表示されます</td>
</tr>
<tr>
<td>UserID</td>
<td><input type="text" name="userid"></td>
</tr>
<tr>
<td></td>
<td>※UserIDは半角英数字記号です</td>
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
<br>
<input type="submit" value="登録" class="center-btn">
</form>

<br><br>
<a href="index.php">インデックス画面に戻る</a>

</body>
</html>