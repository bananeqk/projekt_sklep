<?php
session_start();
require_once("../misc/database.php");
if (!isset($_SESSION["user"]["id"])) {
    header("Location: user_profile.php");
    exit;
}
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["cover_img"])) {
    $user_id = $_SESSION["user"]["id"];
    $file = $_FILES["cover_img"];
    if ($file["error"] === UPLOAD_ERR_OK) {
        $ext = pathinfo($file["name"], PATHINFO_EXTENSION);
        $filename = "cover_" . $user_id . "_" . time() . "." . $ext;
        $target = "../zdjecia/covers/" . $filename;
        if (!is_dir("../zdjecia/covers/")) {
            mkdir("../zdjecia/covers/", 0777, true);
        }
        if (move_uploaded_file($file["tmp_name"], $target)) {
            $db_path = "zdjecia/covers/" . $filename;
            $stmt = mysqli_prepare($conn, "UPDATE uzytkownik SET cover_img=? WHERE id=?");
            mysqli_stmt_bind_param($stmt, "si", $db_path, $user_id);
            mysqli_stmt_execute($stmt);
            $_SESSION["user"]["cover_img"] = $db_path;
        }
    }
}
header("Location: user_profile.php");
exit;
