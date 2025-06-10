<?php
session_start();
require_once("../misc/database.php");

if (isset($_SESSION["user"]["id"])) {
    // Zalogowany: pobierz koszyk z bazy i zapisz do sesji
    $user_id = $_SESSION["user"]["id"];
    $cart = [];
    $result = mysqli_query($conn, "SELECT * FROM koszyk WHERE id_uzytkownika = $user_id");
    while ($row = mysqli_fetch_assoc($result)) {
        $cart[$row['id_produktu']] = $row['ilosc'];
    }
    $_SESSION["cart"] = $cart;
} else {
    // Niezalogowany: koszyk już jest w $_SESSION['cart']
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
        header("Location: shopping_cart.php");
        exit;
    }
    // $_SESSION['cart'] już ustawione
}
header("Location: order_checkout.php");
exit;
