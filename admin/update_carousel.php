<?php
session_start();
require_once("../misc/database.php");

if (!isset($_SESSION["user"]["uprawnienia_id"]) || $_SESSION["user"]["uprawnienia_id"] != 2) {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["image"])) {
    $file = $_FILES["image"];
    $caption_title = $_POST["caption_title"] ?? null;
    $caption_text = $_POST["caption_text"] ?? null;

    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 10 * 1024 * 1024; // 2MB

    if ($file["error"] === UPLOAD_ERR_OK) {
        $file_mime = mime_content_type($file["tmp_name"]);
        if (!in_array($file_mime, $allowed_types)) {
            die("Nieprawidłowy typ pliku!");
        }
        if ($file["size"] > $max_size) {
            die("Plik jest za duży!");
        }
        if (!getimagesize($file["tmp_name"])) {
            die("Plik nie jest obrazem!");
        }
        $ext = pathinfo($file["name"], PATHINFO_EXTENSION);
        $filename = "carousel_" . $user_id . "_" . time() . "." . $ext;
        $target = "../zdjecia/carousel/" . $filename;
        $db_path = $filename;

        if (move_uploaded_file($file["tmp_name"], $target)) {
            $stmt = mysqli_prepare($conn, "INSERT INTO karuzela (img_sciezka, img_tytul, img_tekst) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sss", $db_path, $caption_title, $caption_text);
            if (mysqli_stmt_execute($stmt)) {
                header("Location: carousel.php?status=success");
                exit();
            } else {
                header("Location: carousel.php?status=dberror");
                exit();
            }
        } else {
            header("Location: carousel.php?status=uploaderror");
            exit();
        }
    } else {
        header("Location: carousel.php?status=uploadfail");
        exit();
    }
}
?>