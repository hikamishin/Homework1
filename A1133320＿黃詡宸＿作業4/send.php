<?php
require_once 'config.php';
require_once __DIR__ . '/PHPMailer/src/Exception.php';
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

set_time_limit(0);

$sendMode = $_POST['send_mode'] ?? 'all';
$randomCount = isset($_POST['random_count']) ? (int) $_POST['random_count'] : 1;
$delay = isset($_POST['delay']) ? (int) $_POST['delay'] : 1;
$subject = trim($_POST['subject'] ?? '');
$content = trim($_POST['content'] ?? '');

if ($subject === '' || $content === '') {
    die('主旨與內容不可為空');
}

if ($delay < 0) {
    $delay = 0;
}

if ($sendMode === 'random') {
    if ($randomCount < 1) {
        $randomCount = 1;
    }

    $stmt = $pdo->prepare("SELECT email FROM emails ORDER BY RAND() LIMIT :count");
    $stmt->bindValue(':count', $randomCount, PDO::PARAM_INT);
    $stmt->execute();
} else {
    $stmt = $pdo->query("SELECT email FROM emails ORDER BY no ASC");
}

$emails = $stmt->fetchAll(PDO::FETCH_COLUMN);
$total = count($emails);

if ($total === 0) {
    die('目前沒有可寄送的 Email');
}

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>寄送進度</title>
    <style>
        body {
            font-family: "Microsoft JhengHei", sans-serif;
            background: #eef2f7;
            margin: 0;
            padding: 30px;
        }

        .wrap {
            max-width: 860px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        }

        h1 {
            color: #1f2d3d;
        }

        .progress-box {
            width: 100%;
            background: #dbeafe;
            border-radius: 999px;
            overflow: hidden;
            margin: 20px 0;
        }

        .progress-bar {
            width: 0%;
            height: 30px;
            background: #2563eb;
            color: #fff;
            text-align: center;
            line-height: 30px;
            transition: width 0.3s ease;
        }

        .log {
            border: 1px solid #d1d5db;
            background: #f8fafc;
            height: 320px;
            overflow-y: auto;
            padding: 12px;
            line-height: 1.8;
        }

        a {
            display: inline-block;
            margin-top: 18px;
            color: #2563eb;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="wrap">
        <h1>寄送進度</h1>

        <div class="progress-box">
            <div class="progress-bar" id="progressBar">0%</div>
        </div>

        <div class="log" id="log">開始寄送郵件...<br></div>

<?php
ob_flush();
flush();

$sent = 0;

foreach ($emails as $email) {
    $mail = new PHPMailer(true);
    $statusText = '成功';

    try {
        $mail->isSMTP();
        $mail->Host = $smtpConfig['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $smtpConfig['username'];
        $mail->Password = $smtpConfig['password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $smtpConfig['port'];
        $mail->CharSet = 'UTF-8';

        $mail->setFrom($smtpConfig['from_email'], $smtpConfig['from_name']);
        $mail->addAddress($email);

        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body = $content;

        $mail->send();
    } catch (Exception $e) {
        $statusText = '失敗：' . $mail->ErrorInfo;
    }

    $sent++;
    $percent = round(($sent / $total) * 100);

    $safeEmail = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    $safeStatus = htmlspecialchars($statusText, ENT_QUOTES, 'UTF-8');

    echo "<script>
        document.getElementById('progressBar').style.width = '{$percent}%';
        document.getElementById('progressBar').innerText = '{$percent}%';
        document.getElementById('log').innerHTML += '寄送給 {$safeEmail} ：{$safeStatus}<br>';
    </script>";

    ob_flush();
    flush();

    sleep($delay);
}

echo "<script>
    document.getElementById('log').innerHTML += '<br>全部寄送完成，共 {$total} 筆。';
</script>";
?>
        <a href="index.php">回主畫面</a>
    </div>
</body>
</html>