<?php
session_start();
if (isset($_SESSION["user"])) {
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/style.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&family=Syne:wght@400..800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <title>login - strona</title>
</head>

<body>

    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand me-auto" href="#">
                <img src="../zdjecia/sklep_logo.png" width="70px">
            </a>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Logo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-center flex-grow-1 pe-3 align-items-center">
                        <li class="nav-item">
                            <a class="nav-link mx-lg-2" aria-current="page" href="../index.php">Strona główna</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-lg-2" href="#">Katalog</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-lg-2" href="#">O firmie</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-lg-2" href="#">Kontakt</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="button-1 my-lg-0 mx-lg-2"><i class="bi bi-cart"></i></a>
                        </li>

                    </ul>
                </div>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
    <main>
        <!--środek sekcji logowania (tło)-->
        <div class="container d-flex justify-content-center align-items-center min-vh-100">
            <!--form do logowania-->
            <div class="row border rounded-5 p-3 bg-white shadow box-area">

                <!--lewa strona jak obrazek czy inne coś tam damy-->
                <div class="col-md-6 rounded-5 d-flex justify-content-center align-items-center flex-column left-box">
                    <div class="featured-image mb-3">
                        <img src="../zdjecia/skibidi_firma_log.png" alt="" class="img-fluid" width="750px">
                    </div>
                </div>
                <div class="col-md-6 right-box">
                    <div class="row align-items-center">
                        <div class="header-text mb-4">
                            <h2>Witaj kolego</h2>
                            <p>ciesze sie ze mamy taka skibidi sigme jak ty</p>
                        </div>

                        <?php
                        if (isset($_POST["submit"])) {
                            $email = $_POST["email"];
                            $password = $_POST["password"];

                            require_once("../misc/database.php");


                            $stmt = mysqli_prepare($conn, "SELECT * FROM uzytkownik WHERE email = ?");
                            mysqli_stmt_bind_param($stmt, "s", $email);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);

                            if ($user = mysqli_fetch_assoc($result)) {
                                if (password_verify($password, $user["haslo"])) {
                                    session_start();
                                    $_SESSION["user"] = [
                                        "id" => $user["id"],
                                        "email" => $user["email"],
                                        "uprawnienia_id" => $user["uprawnienia_id"],
                                        "imie_nazwisko" => $user['imie_nazwisko'],
                                        "profil_img" => $user['profil_img'] ?? null
                                    ];
                                    header("Location: ../index.php");
                                    exit();
                                } else {
                                    echo "<div class='alert alert-danger'>Niepoprawne hasło</div>";
                                }
                            } else {
                                echo "<div class='alert alert-danger'>Nie znaleziono użytkownika</div>";
                            }
                        }
                        ?>
                        <form action="log.php" method="post">

                            <div class="input-group mb-3">
                                <input type="email" class="form-control form-control-lg bg-light fs-6" name="email"
                                    placeholder="Adres e-mail">
                            </div>

                            <div class="input-group mb-3">
                                <input type="password" class="form-control form-control-lg bg-light fs-6"
                                    name="password" placeholder="Hasło trzasło">
                            </div>

                            <div class="input-group mb-3">
                                <input type="submit" class="button-1 w-100 fs-6 lh-sm" value="Zaloguj się"
                                    name="submit">
                            </div>
                            <div class="row">
                                <small>Nie posiadasz konta? <a href="../logowanie/reg.php">Zarejestruj się</a></small>
                            </div>
                    </div>
                </div>

            </div>
        </div>
    </main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous"></script>
</body>

</html>