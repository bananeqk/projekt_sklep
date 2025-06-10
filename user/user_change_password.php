<?php
session_start();
require_once("../misc/database.php");
if (!isset($_SESSION["user"]["id"])) {
    header("Location: security.php?pwdmsg=Brak+autoryzacji");
    exit;
}
$uid = (int)$_SESSION["user"]["id"];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $current = $_POST["current_password"] ?? '';
    $new = $_POST["new_password"] ?? '';
    $confirm = $_POST["confirm_password"] ?? '';
    if (!$current || !$new || !$confirm) {
        header("Location: security.php?pwdmsg=Uzupełnij+wszystkie+pola");
        exit;
    }
    if ($new !== $confirm) {
        header("Location: security.php?pwdmsg=Hasła+nie+są+identyczne");
        exit;
    }
    // bierze hash z bazy i sprawdza czy aktualne haslo jest poprawne
    $q = mysqli_query($conn, "SELECT haslo FROM uzytkownik WHERE id=$uid");
    $row = mysqli_fetch_assoc($q);
    if (!$row || !password_verify($current, $row['haslo'])) {
        header("Location: security.php?pwdmsg=Niepoprawne+aktualne+hasło");
        exit;
    }
    // Zmień hasło
    $hash = password_hash($new, PASSWORD_BCRYPT);
    $stmt = mysqli_prepare($conn, "UPDATE uzytkownik SET haslo=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "si", $hash, $uid);
    mysqli_stmt_execute($stmt);

    // Dodaj wpis do dziennika aktywności
    $action = "Zmiana hasła";
    $details = "Hasło zostało zmienione";
    $log_stmt = mysqli_prepare($conn, "INSERT INTO user_activity_log (user_id, action, details) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($log_stmt, "iss", $uid, $action, $details);
    mysqli_stmt_execute($log_stmt);

    // wylogowuje uzytkownika po zmianie hasla
    session_unset();
    session_destroy();
    header("Location: ../logowanie/log.php?msg=haslo+zostalo+zmienione");
    exit;
}
header("Location: security.php?pwdmsg=Błąd");
exit;
