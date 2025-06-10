<?php
require_once("../misc/database.php");
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];
    // usuwanie produktu proste
    $stmt = mysqli_prepare($conn, "DELETE FROM produkty WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: products.php?deleted=1");
    exit;
}
header("Location: products.php?error=delete");
exit;
