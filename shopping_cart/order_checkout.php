<?php
session_start();
require_once("../misc/database.php");

$cart = isset($_SESSION["cart"]) ? $_SESSION["cart"] : [];
if (!$cart) {
    header("Location: shopping_cart.php");
    exit;
}

// sprawdzanie czy uzytkownik jest zalogowany jest to potrzebne zeby dla wygody uzytkownika nie musial wpisywac danych swoich jak email itd.
$user = isset($_SESSION["user"]) ? $_SESSION["user"] : null;
$uid = $user ? (int)$user["id"] : null;

$info = "";
$imie_nazwisko = $email = $telefon = $miasto = $ulica = $kod_pocztowy = "";

// o wlasnie tutaj jest to sprawdzanie
if ($user) {
    $imie_nazwisko = $user["imie_nazwisko"];
    $email = $user["email"];
    $telefon = isset($user["telefon"]) ? $user["telefon"] : "";
    $telefon_missing = empty($telefon);
} else {
    $telefon_missing = false;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Pobierz dane z formularza
    $imie_nazwisko = trim($_POST["imie_nazwisko"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $telefon = trim($_POST["telefon"] ?? "");
    $miasto = trim($_POST["miasto"] ?? "");
    $ulica = trim($_POST["ulica"] ?? "");
    $kod_pocztowy = trim($_POST["kod_pocztowy"] ?? "");

    // walidacja numeru zeby mial tylko cyfry
    if (!ctype_digit($telefon)) {
        $info = "Numer telefonu może zawierać tylko cyfry!";
    } elseif ($imie_nazwisko && $email && $telefon && $miasto && $ulica && $kod_pocztowy) {
        if ($user) {
            // jesli uzytkownik nie mial numeru to go po podaniu dodaje do bazy
            if ($telefon_missing) {
                $stmt_tel = mysqli_prepare($conn, "UPDATE uzytkownik SET telefon=? WHERE id=?");
                mysqli_stmt_bind_param($stmt_tel, "si", $telefon, $uid);
                mysqli_stmt_execute($stmt_tel);
                $_SESSION["user"]["telefon"] = $telefon;
            }
            // tworzy adres dla uzytkownika chyba ze jyz taki istnieje
            // mysqli_real_escape_string jest potrzebne zeby zabezpieczyc przed SQL Injection
            $adres_q = mysqli_query($conn, "SELECT id FROM adresy_uzytkownikow WHERE id_uzytkownika=$uid AND miasto='".mysqli_real_escape_string($conn, $miasto)."' AND ulica='".mysqli_real_escape_string($conn, $ulica)."' AND kod_pocztowy='".mysqli_real_escape_string($conn, $kod_pocztowy)."' LIMIT 1");
            if ($adres = mysqli_fetch_assoc($adres_q)) {
                $adres_id = $adres['id'];
            } else {
                $stmt = mysqli_prepare($conn, "INSERT INTO adresy_uzytkownikow (id_uzytkownika, miasto, ulica, kod_pocztowy) VALUES (?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "isss", $uid, $miasto, $ulica, $kod_pocztowy);
                mysqli_stmt_execute($stmt);
                $adres_id = mysqli_insert_id($conn);
            }
            // Zapisz zamówienie
            foreach ($cart as $pid => $qty) {
                $pid = (int)$pid;
                $qty = (int)$qty;
                $p_q = mysqli_query($conn, "SELECT nazwa, opis, cena FROM produkty WHERE id=$pid");
                $p = mysqli_fetch_assoc($p_q);
                if (!$p) continue;
                $nazwa = $p['nazwa'];
                $opis = $p['opis'];
                $koszt = $p['cena'] * $qty;

                // Dla zalogowanego
                $stmt = mysqli_prepare($conn, "INSERT INTO zamowienia (id_uzytkownika, id_adresu, id_produktu, nazwa, opis, data_zoenia, koszt) VALUES (?, ?, ?, ?, ?, NOW(), ?)");
                mysqli_stmt_bind_param($stmt, "iiissd", $uid, $adres_id, $pid, $nazwa, $opis, $koszt);
                mysqli_stmt_execute($stmt);
                $zamowienie_id = mysqli_insert_id($conn);
                // Dodaj do zamowienie_produkty
                $stmt2 = mysqli_prepare($conn, "INSERT INTO zamowienie_produkty (id_zamowienia, id_produktu, ilosc, cena) VALUES (?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt2, "iiid", $zamowienie_id, $pid, $qty, $p['cena']);
                mysqli_stmt_execute($stmt2);
            }
            // Resetuj koszyk w bazie i w sesji po złożeniu zamówienia
            mysqli_query($conn, "DELETE FROM koszyk WHERE id_uzytkownika = $uid");
            unset($_SESSION["cart"]);
            header("Location: ../user/user_profile.php?order=success");
            exit;
        } else {
            // Niezalogowany użytkownik: NIE zapisuj do bazy, tylko wyczyść koszyk i pokaż komunikat
            unset($_SESSION["cart"]);
            header("Location: ../index.php?order=success");
            exit;
        }
    } else {
        $info = "Wypełnij wszystkie pola!";
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <?php include("../structure/cart_structure/header.php"); ?>
    <style>
        .order-card { max-width: 600px; margin: 0 auto; border-radius: 18px; box-shadow: 0 2px 16px #eaeaea; background: #fff; }
        .order-card .form-label { font-weight: 500; }
        .order-card .form-control[readonly] { background: #f8f9fa; }
        .order-summary { background: #f7f7f7; border-radius: 12px; padding: 16px; margin-bottom: 24px; }
    </style>
</head>
<?php include("../structure/cart_structure/nav.php"); ?>
<body class="bg-light" style="padding-top: 110px;">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="order-card p-4 mb-4">
                <h2 class="mb-4 text-center">Podsumowanie zamówienia</h2>
                <?php if ($info): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($info) ?></div>
                <?php endif; ?>
                <form method="post" autocomplete="off">
                    <?php if ($user): ?>
                        <div class="mb-3">
                            <label class="form-label">Imię i nazwisko</label>
                            <input type="text" name="imie_nazwisko" class="form-control" value="<?= htmlspecialchars($imie_nazwisko) ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Telefon</label>
                            <?php if ($telefon_missing): ?>
                                <input type="text" name="telefon" class="form-control" value="<?= htmlspecialchars($telefon) ?>" required placeholder="Podaj numer telefonu">
                            <?php else: ?>
                                <input type="text" name="telefon" class="form-control" value="<?= htmlspecialchars($telefon) ?>" readonly>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="mb-3">
                            <label class="form-label">Imię i nazwisko</label>
                            <input type="text" name="imie_nazwisko" class="form-control" value="<?= htmlspecialchars($imie_nazwisko) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Telefon</label>
                            <input type="text" name="telefon" class="form-control" value="<?= htmlspecialchars($telefon) ?>" required>
                        </div>
                    <?php endif; ?>
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
                    <button type="submit" class="btn btn-dark w-100 mt-2">Zamawiam i płacę</button>
                    <a href="shopping_cart.php" class="btn btn-light w-100 mt-2">Wróć do koszyka</a>
                </form>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="order-summary mb-4">
                <h5 class="mb-3 text-center">Twoje produkty</h5>
                <ul class="list-group list-group-flush">
                    <?php foreach ($cart as $pid => $qty): 
                        $p_q = mysqli_query($conn, "SELECT nazwa, cena, img_path FROM produkty WHERE id=".(int)$pid);
                        $p = mysqli_fetch_assoc($p_q);
                        if (!$p) continue;
                    ?>
                    <li class="list-group-item d-flex align-items-center">
                        <img src="../zdjecia/produkty/<?= htmlspecialchars($p['img_path']) ?>" alt="<?= htmlspecialchars($p['nazwa']) ?>" style="width:54px;height:54px;object-fit:cover;border-radius:8px;margin-right:16px;">
                        <div class="flex-grow-1">
                            <div class="fw-semibold"><?= htmlspecialchars($p['nazwa']) ?></div>
                            <div class="text-muted small">x<?= $qty ?></div>
                        </div>
                        <div class="fw-bold ms-2"><?= number_format($p['cena'] * $qty, 2) ?> zł</div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php include("../structure/footer.php"); ?>
</body>
</html>
