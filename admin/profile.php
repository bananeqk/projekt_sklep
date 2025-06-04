<?php
session_start();
require_once("../misc/database.php");
include '../adminDashboard/head.php';
include '../adminDashboard/sidebar.php';
?>
<div id="page-content-wrapper">
    <nav class="navbar navbar-expand-lg navbar-light bg-transparent py-4 px-4">
        <div class="d-flex align-items-center">
            <i class="fas fa-align-left primary-text fs-4 me-3 switch" id="menu-toggle"></i>
            <h2 class="fs-2 m-0 switch">Profil</h2>
        </div>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle second-text fw-bold switch" href="#" id="navbarDropdown"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user me-2"></i>
                        <?php if (isset($_SESSION["user"])): ?>
                            <?= htmlspecialchars($_SESSION["user"]["imie_nazwisko"]) ?>
                        <?php endif; ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#">Profil</a></li>
                        <li><a class="dropdown-item" href="#">Ustawienia</a></li>
                        <li><a class="dropdown-item" href="#">Wyloguj się</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container my-5 align-items-center justify-content-center">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-md-12">
                <div class="card shadow rounded-4">
                    <div class="row g-0">
                        <div
                            class="col-sm-4 bg-dark text-white text-center rounded-start-4 d-flex flex-column justify-content-center align-items-center py-4">
                            <form method="post" enctype="multipart/form-data" id="profile-img-form"
                                action="profile_update.php">
                                <label for="profile-img-input" style="cursor:pointer;">
                                    <img src="<?= isset($_SESSION["user"]["profil_img"]) && $_SESSION["user"]["profil_img"] ? '../' . htmlspecialchars($_SESSION["user"]["profil_img"]) : 'https://img.icons8.com/bubbles/64/000000/user.png' ?>"
                                        class="img-fluid rounded-circle mb-2" alt="User-Profile-Image"
                                        style="object-fit:cover; width:128px; height:128px; border:2px solid #fff; transition: box-shadow 0.2s;"
                                        onmouseover="this.style.boxShadow='0 0 0 4px #ffc107';"
                                        onmouseout="this.style.boxShadow='none';">
                                </label>
                                <input type="file" name="profil_img" id="profile-img-input" accept="image/*"
                                    class="d-none" onchange="document.getElementById('profile-img-form').submit();">
                                <?php if (isset($_GET['img']) && $_GET['img'] == 1): ?>
                                    <div class="alert alert-success mt-2 p-2">Zdjęcie zaktualizowane!</div>
                                <?php elseif (isset($_GET['img']) && $_GET['img'] == 0): ?>
                                    <div class="alert alert-danger mt-2 p-2">Błąd podczas aktualizacji zdjęcia!</div>
                                <?php endif; ?>
                            </form>
                            <h6 class="fw-bold mb-1">
                                <?= htmlspecialchars($_SESSION["user"]["imie_nazwisko"] ?? 'Brak danych') ?>
                            </h6>
                            <i class="bi bi-pencil-square mt-3"></i>
                        </div>
                        <div class="col-sm-8">
                            <div class="card-body">
                                <h6 class="mb-3 pb-2 border-bottom fw-bold">Informacje</h6>
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <p class="mb-1 fw-semibold">Email</p>
                                        <h6 class="text-muted fw-normal">
                                            <?= htmlspecialchars($_SESSION["user"]["email"] ?? 'Brak danych') ?>
                                        </h6>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <p class="mb-1 fw-semibold">Uprawnienia</p>
                                        <h6 class="text-muted fw-normal">
                                            <?php
                                            $role = 'Brak danych';
                                            if (isset($_SESSION["user"]["uprawnienia_id"])) {
                                                switch ($_SESSION["user"]["uprawnienia_id"]) {
                                                    case 1:
                                                        $role = 'Użytkownik';
                                                        break;
                                                    case 2:
                                                        $role = 'Administrator';
                                                        break;
                                                    // Dodaj kolejne role jeśli masz
                                                    default:
                                                        $role = 'Nieznana rola';
                                                }
                                            }
                                            echo $role;
                                            ?>
                                        </h6>
                                        <div class="d-flex align-items-center mt-3">
                                            <button id="toggle-darkmode" class="btn btn-outline-secondary w-100">
                                                <i class="bi bi-moon"></i> Przełącz tryb nocny
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

</html>