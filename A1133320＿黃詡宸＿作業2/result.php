<?php
session_start();
if (!isset($_SESSION["logged_in"])) {
    header("Location: login.php");
    exit();
}
?>

<html>
<head><title>CS2夏令營 - 報名結果</title></head>
<body>
    <h2><center>✅ 報名成功！</center></h2>
    <center>
        <table border="1" cellpadding="10">
            <tr><td>姓名</td><td><?php echo $_POST["nName"]; ?></td></tr>
            <tr><td>性別</td><td><?php echo $_POST["mGender"]; ?></td></tr>
            <tr><td>年齡</td><td><?php echo $_POST["nAge"]; ?></td></tr>
            <tr><td>遊戲經驗</td><td><?php echo $_POST["mEx"]; ?></td></tr>
            <tr><td>其他意見</td><td><?php echo $_POST["nop"]; ?></td></tr>
        </table>
        <br>
        <form method="GET" action="register.php">
            <input type="submit" value="返回報名表">
        </form>

    </center>
</body>
</html>
