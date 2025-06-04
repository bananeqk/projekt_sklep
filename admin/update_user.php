<?php
session_start();
require_once("../misc/database.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["user_id"], $_POST["uprawnienia_id"])) {
    $user_id = (int) $_POST["user_id"];
    $new_permission_id = (int) $_POST["uprawnienia_id"];;

    // Sprawdź, czy użytkownik istnieje
    $check = mysqli_prepare($conn, "SELECT id FROM uzytkownik WHERE id = ?");
    mysqli_stmt_bind_param($check, "i", $user_id);
    mysqli_stmt_execute($check);
    $result = mysqli_stmt_get_result($check);

    if (mysqli_num_rows($result) > 0) {
        // Zaktualizuj uprawnienia w bazie
        $update = mysqli_prepare($conn, "UPDATE uzytkownik SET uprawnienia_id = ? WHERE id = ?");
        mysqli_stmt_bind_param($update, "ii", $new_permission_id, $user_id);
        mysqli_stmt_execute($update);

        header("Location: ../admin/users.php?updated=1");
        exit();
    } else {
        echo "Użytkownik nie istnieje.";
    }
} else {
    echo "Nieprawidłowe dane.";
}
?>
