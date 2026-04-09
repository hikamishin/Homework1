<?php
session_start();
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["username"] == "CS2" && $_POST["password"] == "7355608") {
        $_SESSION["logged_in"] = true;
        header("Location: register.php");
        exit();
    } else {
        $error = "帳號或密碼錯誤！";
    }
}
?>

<html>
<head><title>CS2夏令營 - 登入</title></head>
<body>
    <h2><center>CS2夏令營登入</center></h2>
    <center>
        <form method="POST">
            帳號：<input type="text" name="username"><br><br>
            密碼：<input type="password" name="password"><br><br>
            <input type="submit" value="登入">
        </form>
        <?php if ($error != "") echo "<p style='color:red;'>$error</p>"; ?>
    </center>
</body>
</html>