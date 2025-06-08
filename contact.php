<?php
session_start();
require_once("misc/database.php");

$msg = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name_surname = trim($_POST["imie_nazwisko"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $subject = trim($_POST["temat"] ?? "");
    $message = trim($_POST["wiadomosc"] ?? "");

    // proste sprawdzenie czy pola nie są puste
    if ($name_surname && $email && $subject && $message) {
        $stmt = mysqli_prepare($conn, "INSERT INTO contact_messages (imie_nazwisko, email, temat, wiadomosc, data_stworzenia) VALUES (?, ?, ?, ?, NOW())");
        mysqli_stmt_bind_param($stmt, "ssss", $name_surname, $email, $subject, $message);
        if (mysqli_stmt_execute($stmt)) {
            $msg = "Wiadomość została wysłana!";
        } else {
            $msg = "Błąd podczas wysyłania wiadomości.";
        }
        mysqli_stmt_close($stmt);
    } else {
        $msg = "Wszystkie pola są wymagane.";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Kontakt</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .contact-wrapper {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 5px 30px rgba(0, 0, 0, 0.1);
        }

        .contact-info {
            background: linear-gradient(135deg,rgb(45, 47, 49),rgb(80, 85, 85));
            padding: 40px;
            color: white;
        }

        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }

        .contact-item:hover {
            transform: translateX(10px);
        }

        .contact-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .social-links {
            margin-top: 30px;
        }

        .social-icon {
            width: 35px;
            height: 35px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .social-icon:hover {
            background: white;
            transform: translateY(-3px);
        }

        .contact-form {
            padding: 40px;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 2px solid #eee;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color:rgb(0, 0, 0);
            box-shadow: none;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
        }

        .btn-submit {
            background: linear-gradient(135deg,rgb(45, 47, 49),rgb(80, 85, 85));
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .map-container {
            height: 200px;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 20px;
        }

        a {
            text-decoration: none;
        }

        .fa-instagram {
            color: #ff2688;
        }
    </style>
</head>

<body class="bg-light">
    <!--Nawigacja-->
    <nav class="navbar navbar-expand-lg fixed-top bg-light">
        <div class="container-fluid">
            <a class="navbar-brand me-auto m-2" href="#">
                <img src="zdjecia/sklep_logo.png" width="70px">
            </a>
            <div class="offcanvas offcanvas-end" tabindex="2" id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">
                        <img src="zdjecia/sklep_logo.png" width="70px">
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-center flex-grow-1 pe-3 align-items-center">
                        <li class="nav-item">
                            <a class="nav-link mx-lg-2" aria-current="page" href="index.php">Strona główna</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-lg-2" href="katalog.php">Katalog</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-lg-2" href="about.php">O firmie</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-lg-2" href="contact.php">Kontakt</a>
                        </li>
                        <li class="nav-item">
                            <a href="koszyk.php" class="button-1 mx-lg-2 my-2 my-lg-0"><i class="bi bi-cart"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
            <?php if (isset($_SESSION["user"])): ?>
                <?php if ($_SESSION["user"]["uprawnienia_id"] == 1): ?>
                    <a href="user/uzytkownik_panel.php" class="button-1"><i class="bi bi-person-fill"></i></a>
                <?php endif; ?>
                <?php if ($_SESSION["user"]["uprawnienia_id"] == 2): ?>
                    <a href="admin/admin.php" class="btn btn-danger"><i class="bi bi-person-fill"></i></a>
                <?php endif; ?>
            <?php else: ?>
                <a href="logowanie/log.php" class="button-1">Zaloguj się</a>
            <?php endif; ?>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
    <div class="container py-5" style="margin-top: 80px;">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="contact-wrapper">
                    <div class="row g-0">
                        <div class="col-md-5">
                            <div class="contact-info h-100">
                                <h3 class="mb-4">Zapoznajmy się</h3>
                                <p class="mb-4">Posiadasz jakieś pytania? Chcesz dowiedzieć się więcej o firmie? Lub masz z czymś problem? Napisz do nas!</p>

                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Adres siedziby</h6>
                                        <p class="mb-0">ul. Adama Mickiewicza 14<br>47-400 Racibórz</p>
                                    </div>
                                </div>

                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Telefon</h6>
                                        <p class="mb-0">997</p>
                                    </div>
                                </div>

                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">E-mail</h6>
                                        <p class="mb-0">skibidi@gmail.com</p>
                                    </div>
                                </div>

                                <div class="social-links">
                                    <h6 class="mb-3">Obserwuj Nas</h6>
                                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <div class="contact-form">
                                <h3 class="mb-4">Wyślij wiadomość</h3>
                                <?php if ($msg): ?>
                                    <div class="alert alert-info"><?= htmlspecialchars($msg) ?></div>
                                <?php endif; ?>
                                <form method="post" action="contact.php">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Imię i Nazwisko</label>
                                            <input type="text" class="form-control" name="imie_nazwisko" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Temat</label>
                                        <input type="text" class="form-control" name="temat" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label">Wiadomość</label>
                                        <textarea class="form-control" rows="5" name="wiadomosc" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-submit text-white">Wyślij wiadomość</button>
                                </form>

                                <div class="map-container mt-4">
                                    <iframe
                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d452.5043645045795!2d18.222843754822826!3d50.09094420845206!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4711681fc3d50641%3A0x7baf8ee50123658c!2sU%20Wojciecha.%20Ma%C5%82a%20gastronomia!5e0!3m2!1spl!2spl!4v1749405051218!5m2!1spl!2spl"
                                        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?= include("adminDashboard/footer.php"); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>