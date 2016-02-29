<?php
session_start();
$_SESSION['pass'] = $_POST['pass'];
$_SESSION['login'] = $_POST['login'];

header ('Location: /admin/index.html');
?>

