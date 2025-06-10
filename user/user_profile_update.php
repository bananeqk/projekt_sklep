<?php
session_start();
require_once("../misc/database.php");
if (!isset($_SESSION["user"]["id"])) {
    header("Location: /projekt_sklep/user/user_profile.php");
    exit;
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = (int)$_SESSION["user"]["id"];
    $phone = isset($_POST["telefon"]) ? str_replace(' ', '', trim($_POST["telefon"])) : '';
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : '';
    $log_stmt = mysqli_prepare($conn, "INSERT INTO dziennik (user_id, akcja, detale) VALUES (?, ?, ?)");

    // sprawdzanie czy email sie zmienil
    if ($email !== '' && $email !== $_SESSION["user"]["email"]) {
        $stmt = mysqli_prepare($conn, "UPDATE uzytkownik SET email=?, telefon=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "ssi", $email, $phone, $user_id);
        mysqli_stmt_execute($stmt);
        $_SESSION["user"]["email"] = $email;
        $_SESSION["user"]["telefon"] = $phone;
        // Logowanie zmiany emaila
        $action = "Zmiana emaila";
        $details = "Nowy email: $email";
        mysqli_stmt_bind_param($log_stmt, "iss", $user_id, $action, $details);
        mysqli_stmt_execute($log_stmt);
    } elseif ($phone !== '' && $phone !== ($_SESSION["user"]["telefon"] ?? '')) {
        $stmt = mysqli_prepare($conn, "UPDATE uzytkownik SET telefon=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "si", $phone, $user_id);
        mysqli_stmt_execute($stmt);
        $_SESSION["user"]["telefon"] = $phone;
        // zmiana telefonu
        $action = "Zmiana telefonu";
        $details = "Nowy numer: $phone";
        mysqli_stmt_bind_param($log_stmt, "iss", $user_id, $action, $details);
        mysqli_stmt_execute($log_stmt);
    }
}
header("Location: /projekt_sklep/user/user_profile.php");
exit;
