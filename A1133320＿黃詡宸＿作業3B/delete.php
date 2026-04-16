<?php
$id = $_GET["Id"] ?? "";

if ($id != "" && isset($_COOKIE[$id]) && is_array($_COOKIE[$id])) {
    foreach ($_COOKIE[$id] as $name => $value) {
        setcookie($id . "[" . $name . "]", "", time() - 3600);
    }
}

header("Location: shoppingcart.php");
exit;
?>