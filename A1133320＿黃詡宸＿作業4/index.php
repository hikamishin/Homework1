<?php
require_once 'config.php';

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_email'])) {
    $email = trim($_POST['email']);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO emails (email) VALUES (:email)");
        $stmt->execute([':email' => $email]);

        if ($stmt->rowCount() > 0) {
            $msg = 'Email 已成功加入資料庫';
        } else {
            $msg = '此 Email 已存在，未重複加入';
        }
    } else {
        $msg = '請輸入正確的 Email 格式';
    }
}

$stmt = $pdo->query("SELECT no, email FROM emails ORDER BY no ASC");
$emailList = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>PHP 郵件寄送作業系統</title>
    <style>
        body {
            font-family: "Microsoft JhengHei", sans-serif;
            background: #eef2f7;
            margin: 0;
            padding: 30px;
        }

        .wrap {
            max-width: 960px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        }

        h1, h2 {
            color: #1f2d3d;
        }

        .msg {
            margin-bottom: 16px;
            padding: 12px;
            background: #e0f2fe;
            color: #0f172a;
            border-radius: 8px;
        }

        form {
            margin-bottom: 28px;
        }

        input, select, textarea, button {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 14px;
            box-sizing: border-box;
            font-size: 16px;
        }

        button {
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 10px;
            text-align: left;
        }

        th {
            background: #f3f4f6;
        }

        .note {
            color: #475569;
            font-size: 14px;
            margin-top: -6px;
            margin-bottom: 12px;
        }
    </style>
</head>
<body>
    <div class="wrap">
        <h1>PHP 郵件寄送作業系統</h1>

        <?php if ($msg !== ''): ?>
            <div class="msg"><?php echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <h2>1. 新增 Email 到資料庫</h2>
        <form method="post">
            <label>輸入 Email</label>
            <input type="email" name="email" placeholder="例如：test@example.com" required>
            <button type="submit" name="add_email">加入資料庫</button>
        </form>

        <h2>2. 設定寄送條件</h2>
        <form method="post" action="send.php">
            <label>寄送方式</label>
            <select name="send_mode" required>
                <option value="all">全部寄送</option>
                <option value="random">隨機寄送幾筆</option>
            </select>

            <label>隨機寄送數量</label>
            <input type="number" name="random_count" min="1" value="1">
            <div class="note">若選擇「全部寄送」，此欄位不會使用。</div>

            <label>每封郵件間隔秒數</label>
            <input type="number" name="delay" min="0" value="1" required>

            <label>郵件主旨</label>
            <input type="text" name="subject" placeholder="請輸入郵件主旨" required>

            <label>郵件內容</label>
            <textarea name="content" rows="6" placeholder="請輸入郵件內容" required></textarea>

            <button type="submit">開始寄送</button>
        </form>

        <h2>3. Email 清單</h2>
        <table>
            <tr>
                <th>No</th>
                <th>Email</th>
            </tr>
            <?php if (count($emailList) > 0): ?>
                <?php foreach ($emailList as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['no'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2">目前資料庫沒有 Email 資料</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>