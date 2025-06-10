<?php
session_start();
require_once("../misc/database.php");

if (isset($_SESSION["user"]["id"])) {
    $user_id = $_SESSION["user"]["id"];
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["product_id"], $_POST["action"])) {
        $product_id = (int)$_POST["product_id"];
        switch ($_POST["action"]) {
            case "increase":
                mysqli_query($conn, "UPDATE koszyk SET ilosc = ilosc + 1 WHERE id_uzytkownika = $user_id AND id_produktu = $product_id");
                break;
            case "decrease":
                $res = mysqli_query($conn, "SELECT ilosc FROM koszyk WHERE id_uzytkownika = $user_id AND id_produktu = $product_id");
                $row = mysqli_fetch_assoc($res);
                if ($row && $row['ilosc'] > 1) {
                    mysqli_query($conn, "UPDATE koszyk SET ilosc = ilosc - 1 WHERE id_uzytkownika = $user_id AND id_produktu = $product_id");
                }
                break;
            case "remove":
                mysqli_query($conn, "DELETE FROM koszyk WHERE id_uzytkownika = $user_id AND id_produktu = $product_id");
                break;
        }
    }
} else {
    // Koszyk w sesji
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["product_id"], $_POST["action"])) {
        $product_id = (int)$_POST["product_id"];
        switch ($_POST["action"]) {
            case "increase":
                if (isset($_SESSION['cart'][$product_id])) {
                    $_SESSION['cart'][$product_id]++;
                }
                break;
            case "decrease":
                if (isset($_SESSION['cart'][$product_id]) && $_SESSION['cart'][$product_id] > 1) {
                    $_SESSION['cart'][$product_id]--;
                }
                break;
            case "remove":
                unset($_SESSION['cart'][$product_id]);
                break;
        }
    }
}
header("Location: shopping_cart.php");
exit;