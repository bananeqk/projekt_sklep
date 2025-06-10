<?php
session_start();
require_once("../misc/database.php");

// Zabezpieczenie: tylko admin (uprawnienia_id == 2)
if (!isset($_SESSION["user"]["uprawnienia_id"]) || $_SESSION["user"]["uprawnienia_id"] != 2) {
    header("Location: ../index.php");
    exit;
}

// dziadostwo w skrocie samo przygotowanie tego zajelo mi 2 dni z czego 1 dzien to szukanie na necie

// trim usuwa biale znaki z poczatku i konca stringa
if (isset($_POST['upload_product'])) {
    $name = trim($_POST['product_name']);
    $desc = trim($_POST['product_desc']);
    $id_kategorii = isset($_POST['product_category']) ? intval($_POST['product_category']) : 1;
    $ilosc = isset($_POST['product_amount']) ? intval($_POST['product_amount']) : 1;
    $price = floatval($_POST['product_price']);
    $discount = isset($_POST['product_discount']) ? intval($_POST['product_discount']) : 0;
    $rating = isset($_POST['product_rating']) ? floatval($_POST['product_rating']) : 0;
    $color_id = isset($_POST['product_color']) && $_POST['product_color'] !== '' ? intval($_POST['product_color']) : null;

    // osbluga pliku zeby ktos mi tu nie wgral nagle bomby 
    // PATHINFO_EXTENSION bierze rozszerzenie pliku
    $target_dir = "../zdjecia/produkty/";
    $img_name = basename($_FILES["product_image"]["name"]);
    $img_ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    if (!in_array($img_ext, $allowed)) {
        header("Location: products.php?error=invalid_file");
        exit;
    }
    $new_img_name = uniqid("prod_", true) . "." . $img_ext;
    $target_file = $target_dir . $new_img_name;

    if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
        // zapis do bazy z polem kolor_id (relacja)
        $stmt = mysqli_prepare($conn, "INSERT INTO produkty (nazwa, opis, id_kategorii, ilosc, cena, img_path, znizka, ocena, kolor_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssiidsidi", $name, $desc, $id_kategorii, $ilosc, $price, $new_img_name, $discount, $rating, $color_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("Location: products.php?added=1");
        exit;
    } else {
        header("Location: products.php?error=upload");
        exit;
    }
} elseif (isset($_POST['edit_product'], $_POST['product_id'])) {
    $id = (int)$_POST['product_id'];
    $name = trim($_POST['product_name']);
    $desc = trim($_POST['product_desc']);
    $id_kategorii = isset($_POST['product_category']) ? intval($_POST['product_category']) : 1;
    $ilosc = isset($_POST['product_amount']) ? intval($_POST['product_amount']) : 1;
    $price = floatval($_POST['product_price']);
    $discount = isset($_POST['product_discount']) ? intval($_POST['product_discount']) : 0;
    $rating = isset($_POST['product_rating']) ? floatval($_POST['product_rating']) : 0;
    $color_id = isset($_POST['product_color']) && $_POST['product_color'] !== '' ? intval($_POST['product_color']) : null;

    // obsluga pliku (jeśli podano nowe zdjęcie)
    $img_sql = "";
    if (isset($_FILES["product_image"]) && $_FILES["product_image"]["name"]) {
        $target_dir = "../zdjecia/produkty/";
        $img_name = basename($_FILES["product_image"]["name"]);
        // strtolower zamienia rozszerzenie na małe litery
        $img_ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (!in_array($img_ext, $allowed)) {
            header("Location: products.php?error=invalid_file");
            exit;
        }
        // zapisanie nowej nazwy pliku aby uniknąć konfliktów jesli beda dwies takie same nazwy
        $new_img_name = uniqid("prod_", true) . "." . $img_ext;
        $target_file = $target_dir . $new_img_name;
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
            $img_sql = ", img_path = '$new_img_name'";
        } else {
            header("Location: products.php?error=upload");
            exit;
        }
    }

    // aktualizowanie produktu
    $sql = "UPDATE produkty SET nazwa=?, opis=?, id_kategorii=?, ilosc=?, cena=?, znizka=?, ocena=?, kolor_id=? $img_sql WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssiidsdii", $name, $desc, $id_kategorii, $ilosc, $price, $discount, $rating, $color_id, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: products.php?edited=1");
    exit;
} else {
    header("Location: products.php");
    exit;
}
