<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$username  = $_POST['username'] ?? '';
$password  = $_POST['password'] ?? '';
$password2 = $_POST['password2'] ?? '';

if ($username == '') {
    header('Location: user_edit.php?error=name');
    exit();
}

if ($password == '' && $password2 != '') {
    header('Location: user_edit.php?error=pass_empty');
    exit();
}

if ($password != '' && $password2 == '') {
    header('Location: user_edit.php?error=pass2_empty');
    exit();
}

if ($password != '' && $password2 != '' && $password != $password2) {
    header('Location: user_edit.php?error=pass_mismatch');
    exit();
}

if ($password == '') {
    $sql = "UPDATE users SET username=? WHERE id=?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$username, $user_id]);
} else {
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "UPDATE users SET username=?, password=? WHERE id=?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$username, $hash, $user_id]);
}

$_SESSION['username'] = $username;

header('Location: user_edit_done.php');
exit();
?>