<?php
include "check.php";

checkRole("student");
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>學生頁面</title>
</head>
<body>
    <h2>學生頁面</h2>

    <?php showCookieInfo(); ?>

    <p>登入帳號：<?php echo $_SESSION["username"]; ?></p>
    <p>使用者ID：<?php echo $_SESSION["user_id"]; ?></p>
    <p>角色：學生</p>

    <h3>學生功能：</h3>
    <ul>
        <li>查看課程資料</li>
        <li>繳交作業</li>
        <li>查詢成績</li>
        <li>查看公告</li>
    </ul>

    <a href="logout.php">登出</a>
</body>
</html>