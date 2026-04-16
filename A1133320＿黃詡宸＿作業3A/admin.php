<?php
include "check.php";

checkRole("admin");
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>管理者頁面</title>
</head>
<body>
    <h2>管理者頁面</h2>

    <?php showCookieInfo(); ?>

    <p>登入帳號：<?php echo $_SESSION["username"]; ?></p>
    <p>使用者ID：<?php echo $_SESSION["user_id"]; ?></p>
    <p>角色：管理者</p>

    <h3>管理者功能：</h3>
    <ul>
        <li>管理所有使用者帳號</li>
        <li>查看系統日誌</li>
        <li>設定系統參數</li>
        <li>管理課程與班級</li>
    </ul>

    <a href="logout.php">登出</a>
</body>
</html>