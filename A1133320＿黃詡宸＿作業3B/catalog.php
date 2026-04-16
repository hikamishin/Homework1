<?php
session_start();   

if (isset($_POST["Item"])) {
    $_SESSION["Quantity"] = $_POST["Quantity"];
    $id = $_POST["Item"];
    $_SESSION["ID"] = $id; 
    switch (strtoupper($id)) {
        case "S001":
            $_SESSION["Name"] = "10吋平板電腦";
            $_SESSION["Price"] = 12000;
            break;

        case "S002":
            $_SESSION["Name"] = "15.6吋筆記型電腦";
            $_SESSION["Price"] = 27000;
            break;

        case "S003":
            $_SESSION["Name"] = "iPhone智慧型手機";
            $_SESSION["Price"] = 21000;
            break;
    }

    header("Location: savecart.php"); 
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>商品目錄</title>
</head>
<body>
    <h2>商品目錄</h2>

    <form method="post" action="catalog.php">
        <p>
            <label>請選擇商品：</label>
            <select name="Item">
                <option value="S001">S001 - 10吋平板電腦 - 12000</option>
                <option value="S002">S002 - 15.6吋筆記型電腦 - 27000</option>
                <option value="S003">S003 - iPhone智慧型手機 - 21000</option>
            </select>
        </p>

        <p>
            <label>購買數量：</label>
            <input type="number" name="Quantity" value="1" min="1">
        </p>

        <p>
            <input type="submit" value="加入購物車">
            <a href="shoppingcart.php">檢視購物車</a>
        </p>
    </form>
</body>
</html>