<?php
session_start();
require 'db.php';

$userid = $_POST['userid'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $dbh->prepare("SELECT * FROM users WHERE userid = ?");
$stmt->execute([$userid]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];

    header('Location: menu.php');
    exit();
}

// login fail
header('Location: login.php?error=1');
exit();
?>