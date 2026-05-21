<?php
$host = 'localhost';
$dbname = 'homework_mail';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die('資料庫連線失敗：' . $e->getMessage());
}

$smtpConfig = [
    'host' => 'smtp.gmail.com',
    'username' => '要的@gmail.com',
    'password' => '要的密碼',
    'port' => 587,
    'from_email' => '要的@gmail.com',
    'from_name' => 'PHP Mail Homework'
];
?>