<?php
require_once("../misc/database.php");

if (isset($_POST['upload_product'])) {
    $name = trim($_POST['product_name']);
    $desc = trim($_POST['product_desc']);
    $id_kategorii = isset($_POST['product_category']) ? intval($_POST['product_category']) : 1;
    $ilosc = isset($_POST['product_amount']) ? intval($_POST['product_amount']) : 1;
    $price = floatval($_POST['product_price']);
    $discount = isset($_POST['product_discount']) ? intval($_POST['product_discount']) : 0;
    $rating = isset($_POST['product_rating']) ? floatval($_POST['product_rating']) : 0;
    $color_id = isset($_POST['product_color']) && $_POST['product_color'] !== '' ? intval($_POST['product_color']) : null;

    // Obsługa pliku
    $target_dir = "../produkty/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
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
        // Zapis do bazy z polem kolor_id (relacja)
        $stmt = $conn->prepare("INSERT INTO produkty (nazwa, opis, id_kategorii, ilosc, cena, img_path, znizka, ocena, kolor_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiidsidi", $name, $desc, $id_kategorii, $ilosc, $price, $new_img_name, $discount, $rating, $color_id);
        $stmt->execute();
        $stmt->close();
        header("Location: products.php?added=1");
        exit;
    } else {
        header("Location: products.php?error=upload");
        exit;
    }
} else {
    header("Location: products.php");
    exit;
}
