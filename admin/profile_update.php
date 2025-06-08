<?php
session_start();
require_once("../misc/database.php");
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["profil_img"])) {
    if (isset($_SESSION["user"]["id"])) {
        $user_id = $_SESSION["user"]["id"];
        $file = $_FILES["profil_img"];
        // to upload_err_ok jest po to zeby stwierdzic czy podczas przesylania nie wystapil blad
        if ($file["error"] === UPLOAD_ERR_OK) {

            // od lini 12 do 24 jest sprawdzanie czy plik: ma format jako zdjecie rozmiar (zeby ktos mi nie zasadzil zip bomby prosto do komputera) no i zeby wirusa nie wgrac
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_size = 2 * 1024 * 1024; // 2MB

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
            // to $ext jest po to aby pobrac rozszerzenie pliku i zmienic nazwe na unikalna zeby nie bylo konfliktow z innymi plikami
            // PATHINFO_EXTENSION pobiera rozszerzenie pliku
            $ext = pathinfo($file["name"], PATHINFO_EXTENSION);
            $filename = "profile_" . $user_id . "_" . time() . "." . $ext;
            $target = "../zdjecia/profiles/" . $filename;
            if (move_uploaded_file($file["tmp_name"], $target)) {
                $db_path = "zdjecia/profiles/" . $filename;
                $stmt = mysqli_prepare($conn, "UPDATE uzytkownik SET profil_img=? WHERE id=?");
                mysqli_stmt_bind_param($stmt, "si", $db_path, $user_id);
                mysqli_stmt_execute($stmt);
                $_SESSION["user"]["profil_img"] = $db_path;
                header("Location: profile.php?img=1");
                exit();
            } else {
                // Błąd przenoszenia pliku
                die("Błąd move_uploaded_file! Ścieżka docelowa: $target");
            }
        } else {
            // Błąd uploadu pliku
            echo ("Błąd uploadu pliku! Kod błędu: " . $file["error"]);
        }
    }
}
header("Location: profile.php?img=0");
exit();