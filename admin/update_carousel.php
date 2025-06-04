<?php
require_once("../misc/database.php");

if (isset($_POST["upload"])) {
    $image = $_FILES["image"]["name"];
    $caption_title = $_POST["caption_title"] ?? null;
    $caption_text = $_POST["caption_text"] ?? null;
    $target = "../carousel/" . basename($image);

    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target)) {
            $stmt = mysqli_prepare($conn, "INSERT INTO karuzela (img_path, caption_title, caption_text) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sss", $image, $caption_title, $caption_text);
            if (mysqli_stmt_execute($stmt)) {
                header("Location: carousel.php?status=success");
                exit();
            } else {
                header("Location: carousel.php?status=dberror");
                exit();
            }
        } else {
            header("Location: carousel.php?status=uploaderror");
            exit();
        }
    } else {
        header("Location: carousel.php?status=notimage");
        exit();
    }
}
?>