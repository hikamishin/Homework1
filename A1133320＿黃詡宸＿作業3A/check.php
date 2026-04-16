<?php
session_start();

function checkLogin() {
    if (!isset($_SESSION["username"]) || !isset($_SESSION["role"])) {
        header("Location: login.php");
        exit();
    }
}

function checkRole($required_role) {
    checkLogin();
    if ($_SESSION["role"] != $required_role) {
        echo "您沒有權限瀏覽此頁面！";
        echo "<br><a href='login.php'>返回登入頁</a>";
        exit();
    }
}

function showCookieInfo() {
    if (isset($_COOKIE["user_id"])) {
        echo "Cookie 中儲存的使用者ID：" . $_COOKIE["user_id"];
    } else {
        echo "目前沒有儲存使用者ID的 Cookie";
    }
    echo " <a href='deletecookie.php'>刪除 Cookie</a>";
    echo "<br>";
}
?>