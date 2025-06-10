<?php
session_start();
require_once("../misc/database.php");

if (!isset($_SESSION["user"]["uprawnienia_id"]) || $_SESSION["user"]["uprawnienia_id"] != 2) {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"], $_POST["caption_title"], $_POST["caption_text"])) {
    $id = (int) $_POST["id"];
    $caption_title = $_POST["caption_title"];
    $caption_text = $_POST["caption_text"];

    // tutaj sprawdzam czy taki wpis juz istnieje 
    $check = mysqli_prepare($conn, "SELECT id FROM karuzela WHERE id = ?");
    mysqli_stmt_bind_param($check, "i", $id);
    mysqli_stmt_execute($check);
    $result = mysqli_stmt_get_result($check);

    if (mysqli_num_rows($result) > 0) {
        // aktualizacja wpisu
        $update = mysqli_prepare($conn, "UPDATE karuzela SET caption_title = ?, caption_text = ? WHERE id = ?");
        mysqli_stmt_bind_param($update, "ssi", $caption_title, $caption_text, $id);
        mysqli_stmt_execute($update);

        header("Location: ../admin/carousel.php?updated=1");
        exit();
    } else {
        echo "Wpis nie istnieje.";
    }
} else {
    echo "Nieprawidłowe dane.";
}
?>