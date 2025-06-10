<?php
session_start();
require_once("../misc/database.php");

if (!isset($_SESSION["user"]["uprawnienia_id"]) || $_SESSION["user"]["uprawnienia_id"] != 2) {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["user_id"], $_POST["uprawnienia_id"])) {
    $user_id = (int) $_POST["user_id"];
    $new_permission_id = (int) $_POST["uprawnienia_id"];;

    // sprawdza czy taki uzytkownik istnieje
    $check = mysqli_prepare($conn, "SELECT id FROM uzytkownik WHERE id = ?");
    mysqli_stmt_bind_param($check, "i", $user_id);
    mysqli_stmt_execute($check);
    $result = mysqli_stmt_get_result($check);

    if (mysqli_num_rows($result) > 0) {
        // aktualizuje jego dane w bazie
        $update = mysqli_prepare($conn, "UPDATE uzytkownik SET uprawnienia_id = ? WHERE id = ?");
        mysqli_stmt_bind_param($update, "ii", $new_permission_id, $user_id);
        mysqli_stmt_execute($update);

        // jesli zmieniam uzytkownikowi uprawnienia_id, to aktualizuje sesje ciezko powiedziec czy to dziala i czy ma to sens (10.06 jednak dziala ma to sens)
        if (isset($_SESSION["user"]) && $_SESSION["user"]["id"] == $user_id) {
            $user_q = mysqli_query($conn, "SELECT * FROM uzytkownik WHERE id = $user_id");
            if ($user_q && $user_row = mysqli_fetch_assoc($user_q)) {
                $_SESSION["user"]["uprawnienia_id"] = $user_row["uprawnienia_id"];
            }
        }

        header("Location: ../admin/users.php?updated=1");
        exit();
    } else {
        echo "Użytkownik nie istnieje.";
    }
} else {
    echo "Nieprawidłowe dane.";
}
?>
