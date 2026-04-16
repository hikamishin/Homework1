<?php
session_start();

$error = "";

$users = [
    ["id" => "A001", "username" => "admin",   "password" => "admin123",   "role" => "admin"],
    ["id" => "T001", "username" => "teacher",  "password" => "teacher123", "role" => "teacher"],
    ["id" => "S001", "username" => "student",  "password" => "student123", "role" => "student"],
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $found = false;

    foreach ($users as $user) {
        if ($user["username"] == $username && $user["password"] == $password) {
            $found = true;

            $_SESSION["user_id"]  = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"]     = $user["role"];

            setcookie("user_id", $user["id"], time() + (7 * 24 * 60 * 60), "/");

            if ($user["role"] == "admin") {
                header("Location: admin.php");
            } elseif ($user["role"] == "teacher") {
                header("Location: teacher.php");
            } else {
                header("Location: student.php");
            }
            exit();
        }
    }

    if (!$found) {
        $error = "帳號或密碼錯誤！";
    }
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>登入</title>
</head>
<body>
    <h2>系統登入</h2>

    <?php if ($error != ""): ?>
        <p><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <label>帳號：</label>
        <input type="text" name="username" required>
        <br>
        <label>密碼：</label>
        <input type="password" name="password" required>
        <br><br>
        <input type="submit" value="登入">
    </form>

    <p>測試帳號：</p>
    <p>管理者：admin / admin123</p>
    <p>教師：teacher / teacher123</p>
    <p>學生：student / student123</p>
</body>
</html>