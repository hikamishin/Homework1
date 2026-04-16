<?php
include "check.php";

checkRole("teacher");
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>教師頁面</title>
</head>
<body>
    <h2>教師頁面</h2>

    <?php showCookieInfo(); ?>

    <p>登入帳號：<?php echo $_SESSION["username"]; ?></p>
    <p>使用者ID：<?php echo $_SESSION["user_id"]; ?></p>
    <p>角色：教師</p>

    <h3>教師功能：</h3>
    <ul>
        <li>查看班級學生名單</li>
        <li>上傳與管理課程資料</li>
        <li>批改作業與評分</li>
        <li>發布課堂公告</li>
    </ul>

    <a href="logout.php">登出</a>
</body>
</html>