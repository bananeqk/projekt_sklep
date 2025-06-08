<?php
session_start();
require_once("../misc/database.php");
if (!isset($_SESSION["user"])) {
    header("Location: ../logowanie/log.php");
    exit;
}
$cart = isset($_SESSION["cart"]) ? $_SESSION["cart"] : [];
if (!$cart) {
    header("Location: shopping_cart.php");
    exit;
}
$user = $_SESSION["user"];
$uid = (int)$user["id"];
$info = "";
$miasto = $ulica = $kod_pocztowy = "";
$payment = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $miasto = trim($_POST["miasto"] ?? "");
    $ulica = trim($_POST["ulica"] ?? "");
    $kod_pocztowy = trim($_POST["kod_pocztowy"] ?? "");
    $payment = trim($_POST["payment"] ?? "");
    if ($miasto && $ulica && $kod_pocztowy && $payment) {
        // Sprawdź czy adres już istnieje
        $adres_q = mysqli_query($conn, "SELECT id FROM adresy_uzytkownikow WHERE id_uzytkownika=$uid AND miasto='".mysqli_real_escape_string($conn, $miasto)."' AND ulica='".mysqli_real_escape_string($conn, $ulica)."' AND kod_pocztowy='".mysqli_real_escape_string($conn, $kod_pocztowy)."' LIMIT 1");
        if ($adres = mysqli_fetch_assoc($adres_q)) {
            $adres_id = $adres['id'];
        } else {
            // Dodaj nowy adres
            $stmt = mysqli_prepare($conn, "INSERT INTO adresy_uzytkownikow (id_uzytkownika, miasto, ulica, kod_pocztowy) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "isss", $uid, $miasto, $ulica, $kod_pocztowy);
            mysqli_stmt_execute($stmt);
            $adres_id = mysqli_insert_id($conn);
        }
        // Zapisz każde zamówienie jako osobny wpis dla każdego produktu
        foreach ($cart as $pid => $qty) {
            $pid = (int)$pid;
            $qty = (int)$qty;
            // Pobierz dane produktu
            $p_q = mysqli_query($conn, "SELECT nazwa, opis, cena FROM produkty WHERE id=$pid");
            $p = mysqli_fetch_assoc($p_q);
            if (!$p) {
                // Produkt nie istnieje, pomiń lub przerwij z komunikatem
                continue;
                // lub jeśli chcesz przerwać całe zamówienie:
                // $info = "Produkt o ID $pid nie istnieje w bazie.";
                // break;
            }
            $nazwa = $p['nazwa'];
            $opis = $p['opis'];
            $koszt = $p['cena'] * $qty;
            // Dodaj zamówienie
            $stmt = mysqli_prepare($conn, "INSERT INTO zamowienia (id_uzytkownika, id_adresu, id_produktu, nazwa, opis, data_zoenia, koszt) VALUES (?, ?, ?, ?, ?, NOW(), ?)");
            mysqli_stmt_bind_param($stmt, "iiissd", $uid, $adres_id, $pid, $nazwa, $opis, $koszt);
            mysqli_stmt_execute($stmt);
            $zamowienie_id = mysqli_insert_id($conn);
            // Dodaj do zamowienie_produkty
            $stmt2 = mysqli_prepare($conn, "INSERT INTO zamowienie_produkty (id_zamowienia, id_produktu, ilosc, cena) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt2, "iiid", $zamowienie_id, $pid, $qty, $p['cena']);
            mysqli_stmt_execute($stmt2);
        }
        unset($_SESSION["cart"]);
        header("Location: ../user/user_profile.php?order=success");
        exit;
    } else {
        $info = "Wypełnij wszystkie pola!";
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Zamówienie</title>
    <link href="../css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h2 class="mb-4">Zamówienie</h2>
    <?php if ($info): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($info) ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Miasto</label>
            <input type="text" name="miasto" class="form-control" value="<?= htmlspecialchars($miasto) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Ulica</label>
            <input type="text" name="ulica" class="form-control" value="<?= htmlspecialchars($ulica) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Kod pocztowy</label>
            <input type="text" name="kod_pocztowy" class="form-control" value="<?= htmlspecialchars($kod_pocztowy) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Metoda płatności</label>
            <select name="payment" class="form-select" required>
                <option value="">Wybierz...</option>
                <option value="przelew" <?= $payment=="przelew"?"selected":""; ?>>Przelew</option>
                <option value="pobranie" <?= $payment=="pobranie"?"selected":""; ?>>Płatność przy odbiorze</option>
                <option value="karta" <?= $payment=="karta"?"selected":""; ?>>Karta płatnicza</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Złóż zamówienie</button>
        <a href="shopping_cart.php" class="btn btn-secondary ms-2">Wróć do koszyka</a>
    </form>
</div>
</body>
</html>
