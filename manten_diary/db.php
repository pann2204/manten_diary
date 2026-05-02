<?php
$dsn = 'mysql:dbname=manten_diary;host=localhost;charset=utf8';
$user = 'root';
$password = '';

$dbh = new PDO($dsn, $user, $password);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>