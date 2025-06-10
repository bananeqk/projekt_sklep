<?php
session_start();
require_once("../misc/database.php");

// Zabezpieczenie: tylko admin (uprawnienia_id == 2)
if (!isset($_SESSION["user"]["uprawnienia_id"]) || $_SESSION["user"]["uprawnienia_id"] != 2) {
    header("Location: ../index.php");
    exit;
}

if (isset($_GET["id"])) {

    $id = (int) $_GET["id"];

    $check = mysqli_prepare($conn, "SELECT * FROM uzytkownik WHERE id = ?");
    mysqli_stmt_bind_param($check, "i", $id);
    mysqli_stmt_execute($check);
    $result = mysqli_stmt_get_result($check);

    if (mysqli_num_rows($result) > 0) {
        $stmt = mysqli_prepare($conn, "DELETE FROM uzytkownik WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);

        header("Location: ../admin/users.php?deleted=1");
        exit();
    } else {
        echo "Użytkownik nie istnieje.";
    }
} else {
    echo "Nieprawidłowe id.";
}
?>