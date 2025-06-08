<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["product_id"])) {
    $product_id = (int)$_POST["product_id"];
    // Obsłuż zarówno katalog jak i product_card (może być quantity lub nie)
    $quantity = isset($_POST["quantity"]) && (int)$_POST["quantity"] > 0 ? (int)$_POST["quantity"] : 1;
    if (!isset($_SESSION["cart"])) {
        $_SESSION["cart"] = [];
    }
    if (isset($_SESSION["cart"][$product_id])) {
        $_SESSION["cart"][$product_id] += $quantity;
    } else {
        $_SESSION["cart"][$product_id] = $quantity;
    }
    // Przekierowanie zależnie od źródła (product_card lub katalog)
    if (isset($_SERVER["HTTP_REFERER"])) {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    } else {
        header("Location: catalog.php?cart=added");
    }
    exit;
}
header("Location: catalog.php");
exit;
