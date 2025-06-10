<?php
session_start();
require_once("../misc/database.php");

if (!isset($_SESSION["user"]["uprawnienia_id"]) || $_SESSION["user"]["uprawnienia_id"] != 2) {
    header("Location: ../index.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // sprawdzenie, czy wpis o podanym ID istnieje
    $check = mysqli_prepare($conn, "SELECT * FROM karuzela WHERE id = ?");
    mysqli_stmt_bind_param($check, "i", $id);
    mysqli_stmt_execute($check);
    $result = mysqli_stmt_get_result($check);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "Wpis o podanym ID nie istnieje.";
        exit();
    }
} else {
    echo "Nie podano ID wpisu.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <?php include("../structure/admin/head.php"); ?>
    <title>Document</title>
</head>
<body>
    <?php include("../structure/admin/sidebar.php"); ?>
<div id="wrapper"></div>
<div class="container mt-5">
    <h2>Edytuj wpis w karuzeli</h2>
    <form method="post" action="form_carousel.php">
        <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
        <div class="mb-3">
            <label for="caption_title" class="form-label">Tytuł</label>
            <input type="text" name="caption_title" id="caption_title" class="form-control"
                value="<?= htmlspecialchars($row['img_tytul']) ?>">
        </div>
        <div class="mb-3">
            <label for="caption_text" class="form-label">Opis</label>
            <input type="text" name="caption_text" id="caption_text" class="form-control"
                value="<?= htmlspecialchars($row['img_tekst']) ?>">
        </div>
        <a href="form_carousel.php">
            <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
        </a>
        <a href="carousel.php" class="btn btn-secondary">Anuluj</a>
    </form>
</div>