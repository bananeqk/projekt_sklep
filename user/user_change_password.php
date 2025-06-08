<?php
session_start();
require_once("../misc/database.php");
if (!isset($_SESSION["user"]["id"])) {
    header("Location: user_profile.php?pwdmsg=Brak+autoryzacji");
    exit;
}
$uid = (int)$_SESSION["user"]["id"];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $current = $_POST["current_password"] ?? '';
    $new = $_POST["new_password"] ?? '';
    $confirm = $_POST["confirm_password"] ?? '';
    if (!$current || !$new || !$confirm) {
        header("Location: user_profile.php?pwdmsg=Uzupełnij+wszystkie+pola#security-section");
        exit;
    }
    if ($new !== $confirm) {
        header("Location: user_profile.php?pwdmsg=Hasła+nie+są+identyczne#security-section");
        exit;
    }
    // Pobierz hash hasła z bazy
    $q = mysqli_query($conn, "SELECT haslo FROM uzytkownik WHERE id=$uid");
    $row = mysqli_fetch_assoc($q);
    if (!$row || !password_verify($current, $row['haslo'])) {
        header("Location: user_profile.php?pwdmsg=Niepoprawne+aktualne+hasło#security-section");
        exit;
    }
    // Zmień hasło
    $hash = password_hash($new, PASSWORD_DEFAULT);
    $stmt = mysqli_prepare($conn, "UPDATE uzytkownik SET haslo=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "si", $hash, $uid);
    mysqli_stmt_execute($stmt);
    header("Location: user_profile.php?pwdmsg=Hasło+zostało+zmienione#security-section");
    exit;
}
header("Location: user_profile.php?pwdmsg=Błąd#security-section");
exit;
