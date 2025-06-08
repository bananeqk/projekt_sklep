<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["product_id"], $_POST["action"])) {
    $pid = (int)$_POST["product_id"];
    if (!isset($_SESSION["cart"][$pid])) {
        header("Location: shopping_cart.php");
        exit;
    }
    switch ($_POST["action"]) {
        case "increase":
            $_SESSION["cart"][$pid]++;
            break;
        case "decrease":
            if ($_SESSION["cart"][$pid] > 1) {
                $_SESSION["cart"][$pid]--;
            }
            break;
        case "remove":
            unset($_SESSION["cart"][$pid]);
            break;
    }
}
header("Location: shopping_cart.php");
exit;
