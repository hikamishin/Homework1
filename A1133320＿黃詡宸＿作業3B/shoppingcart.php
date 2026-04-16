<?php
$total = 0;
$flag = true;
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>購物車</title>
</head>
<body>
    <h2>購物車內容</h2>

    <p>
        <a href="catalog.php">繼續購物</a>
    </p>

    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>功能</th>
            <th>商品編號</th>
            <th>商品名稱</th>
            <th>價格</th>
            <th>數量</th>
            <th>小計</th>
        </tr>

        <?php
        foreach ($_COOKIE as $arr => $value) {
            if (isset($_COOKIE[$arr]) && is_array($_COOKIE[$arr])) {
                if ($flag) {
                    $flag = false;
                    $color = "#FF99CC";
                } else {
                    $flag = true;
                    $color = "#99FFCC";
                }

                echo "<tr bgcolor='{$color}'>";
                echo "<td><a href='delete.php?Id={$arr}'>刪除</a></td>";

                $price = 0;
                $quantity = 0;
                $id = "";
                $name = "";

                foreach ($_COOKIE[$arr] as $field => $fieldValue) {
                    if ($field == "ID") {
                        $id = $fieldValue;
                    }
                    if ($field == "Name") {
                        $name = $fieldValue;
                    }
                    if ($field == "Price") {
                        $price = $fieldValue;
                    }
                    if ($field == "Quantity") {
                        $quantity = $fieldValue;
                    }
                }

                $subtotal = $price * $quantity;

                echo "<td>{$id}</td>";
                echo "<td>{$name}</td>";
                echo "<td>{$price}</td>";
                echo "<td>{$quantity}</td>";
                echo "<td>{$subtotal}</td>";
                echo "</tr>";

                $total += $subtotal;
            }
        }
        ?>

        <tr>
            <td colspan="5" align="right"><strong>總金額</strong></td>
            <td><strong><?php echo $total; ?></strong></td>
        </tr>
    </table>
</body>
</html>