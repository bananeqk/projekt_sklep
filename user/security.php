<?php
session_start();
require_once("../misc/database.php");
if (!isset($_SESSION["user"]["id"])) {
    header("Location: user_profile.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="../css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="user_profile_style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&family=Syne:wght@400..800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

    <title>Main - strona</title>

    <style>
        body {
            padding-top: 100px !important;
            background: #fff !important;
        }

        @media (max-width: 991px) {
            body {
                padding-top: 120px !important;
            }
        }

        .banner-darken {
            position: relative;
            overflow: hidden;
            height: 240px;
        }

        .banner-darken::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            z-index: 1;
        }

        .banner-img-darken {
            width: 100%;
            height: 240px;
            object-fit: cover;
            border-radius: 15px;
            position: relative;
            z-index: 0;
        }

        .profile-pic-darken {
            width: 140px;
            height: 140px;
            object-fit: cover;
            border: 4px solid #fff;
            background: #fff;
        }
    </style>
</head>

<body>






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
                            <a class="nav-link mx-lg-2" aria-current="page" href="#">Strona główna</a>
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
                            <a href="#" class="button-1 mx-lg-2 my-2 my-lg-0"><i class="bi bi-cart"></i></a>
                        </li>

                    </ul>
                </div>
            </div>

            <?php if (isset($_SESSION["user"])): ?>
                <!-- Jeśli użytkownik ma uprawnienia_id = 1 -->
                <?php if ($_SESSION["user"]["uprawnienia_id"] == 1): ?>
                    <a href="uzytkownik_panel.php" class="button-1"><i class="bi bi-person-fill"></i></a>
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

    <div class="bg-light">
        <div class="container py-5">
            <div class="row">
                <!-- Profile Header -->
                <div class="col-12 mb-4">
                    <div class="profile-header position-relative mb-4 banner-darken"
                        style="overflow:hidden; height:240px;">
                        <img src="<?= isset($_SESSION["user"]["cover_img"]) && $_SESSION["user"]["cover_img"] ? '../' . htmlspecialchars($_SESSION["user"]["cover_img"]) : 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1200&q=80' ?>"
                            alt="Cover" class="banner-img-darken"
                            style="width:100%;height:240px;object-fit:cover;border-radius:15px;">
                    </div>
                    <div class="text-center">
                        <div class="position-relative d-inline-block">
                            <img src="<?= isset($_SESSION["user"]["profil_img"]) && $_SESSION["user"]["profil_img"] ? '../' . htmlspecialchars($_SESSION["user"]["profil_img"]) : 'https://img.icons8.com/bubbles/64/000000/user.png' ?>"
                                class="rounded-circle profile-pic profile-pic-darken" alt="Profile Picture"
                                style="object-fit:cover; width:140px; height:140px; border:4px solid #fff; background:#fff;">
                        </div>
                        <h3 class="mt-3 mb-1"><?= htmlspecialchars($_SESSION["user"]["imie_nazwisko"] ?? '') ?></h3>
                        <div class="d-flex justify-content-center gap-2 mb-4"></div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0">
                            <div class="row g-0">
                                <!-- Sidebar -->
                                <div class="col-lg-3 border-end">
                                    <div class="p-4">
                                        <div class="nav flex-column nav-pills">
                                            <a class="nav-link" href="user_profile.php"><i class="fas fa-user me-2"></i>Personal
                                                Info</a>
                                            <a class="nav-link active" href="security.php"><i class="fas fa-lock me-2"></i>Security
                                            </a>
                                            <a class="nav-link" href="user_profile.php#activity"><i
                                                    class="fas fa-chart-line me-2"></i>Activity</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Content Area -->
                                <div class="col-lg-9">
                                    <div class="p-4">
                                        <h4 class="mb-4">Zmiana hasła</h4>
                                        <?php if (isset($_GET['pwdmsg'])): ?>
                                            <div class="alert alert-info"><?= htmlspecialchars($_GET['pwdmsg']) ?></div>
                                        <?php endif; ?>
                                        <form method="post" action="user_change_password.php">
                                            <div class="mb-3">
                                                <label class="form-label">Aktualne hasło</label>
                                                <input type="password" name="current_password" class="form-control"
                                                    required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nowe hasło</label>
                                                <input type="password" name="new_password" class="form-control"
                                                    required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Powtórz nowe hasło</label>
                                                <input type="password" name="confirm_password" class="form-control"
                                                    required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Zmień hasło</button>
                                            <a href="user_profile.php" class="btn btn-secondary ms-2">Wróć do
                                                profilu</a>
                                        </form>
                                    </div>
                                </div>
                                <!-- end Content Area -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end Main Content -->
            </div>
        </div>
    </div>
</body>

</html>