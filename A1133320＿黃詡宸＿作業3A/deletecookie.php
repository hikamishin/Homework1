<?php
session_start();

setcookie("user_id", "", time() - 3600, "/");

$redirect = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "login.php";
header("Location: " . $redirect);
exit();
?>