<?php
session_start();
require_once("../misc/database.php");

$product_id = isset($_POST["product_id"]) ? (int)$_POST["product_id"] : 0;
$quantity = isset($_POST["quantity"]) && (int)$_POST["quantity"] > 0 ? (int)$_POST["quantity"] : 1;

// Sprawdź, czy produkt istnieje w bazie
$prod_check = mysqli_query($conn, "SELECT id FROM produkty WHERE id = $product_id");
if (!$prod_check || mysqli_num_rows($prod_check) == 0) {
    header("Location: ../catalog.php?error=Produkt_nie_istnieje");
    exit;
}

if (isset($_SESSION["user"]["id"])) {
    // Zalogowany użytkownik - koszyk w bazie
    $user_id = $_SESSION["user"]["id"];
    $sql = "SELECT ilosc FROM koszyk WHERE id_uzytkownika = $user_id AND id_produktu = $product_id";
    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        $new_qty = $row['ilosc'] + $quantity;
        mysqli_query($conn, "UPDATE koszyk SET ilosc = $new_qty WHERE id_uzytkownika = $user_id AND id_produktu = $product_id");
    } else {
        mysqli_query($conn, "INSERT INTO koszyk (id_uzytkownika, id_produktu, ilosc) VALUES ($user_id, $product_id, $quantity)");
    }
} else {
    // Niezalogowany użytkownik - koszyk w sesji
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Przekierowanie z powrotem na stronę produktu z komunikatem
if (isset($_SERVER["HTTP_REFERER"]) && strpos($_SERVER["HTTP_REFERER"], "product_card.php") !== false) {
    header("Location: " . strtok($_SERVER["HTTP_REFERER"], '?') . "?id=$product_id&added=1");
    exit;
}
header("Location: " . ($_SERVER["HTTP_REFERER"] ?? "../catalog.php"));
exit;