<?php
session_start();
require_once("../misc/database.php");
if (!isset($_SESSION["user"]["id"])) {
    header("Location: /projekt_sklep/user/user_profile.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <?php include '../structure/cart_structure/header.php'; ?>
    <link rel="stylesheet" href="../css/user_profile_style.css">
    <title>Zabezpieczenie konta</title>
</head>

<body>
    <?php include '../structure/cart_structure/nav.php'; ?>
    <div class="container mt-3 mb-4">
        <div class="d-flex justify-content-end">
            <div class="dropdown">
                <a href="#" class="btn btn-outline-dark rounded-circle dropdown-toggle" id="userDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false" title="Konto">
                    <i class="bi bi-person-fill"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="user_profile.php"><i class="bi bi-person"></i> Panel użytkownika</a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-danger" href="../logowanie/logout.php"><i class="bi bi-box-arrow-right"></i>
                            Wyloguj się</a></li>
                </ul>
            </div>
        </div>
        <?php if (isset($_GET['pwdmsg']) && $_GET['pwdmsg']): ?>
            <div class="alert alert-dismissible fade show alert-warning shadow-sm mt-3" role="alert"
                style="max-width:600px;margin:auto;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?= htmlspecialchars($_GET['pwdmsg']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Zamknij"></button>
            </div>
        <?php endif; ?>
    </div>
    <div>
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
                                            <a class="nav-link" href="user_profile.php"><i class="fas fa-user me-2"></i>Informacje o
                                                koncie</a>
                                            <a class="nav-link active" href="security.php"><i class="fas fa-lock me-2"></i>Zabezpieczenie
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Content Area -->
                                <div class="col-lg-9">
                                    <div class="p-4">
                                        <h5 class="mb-4">Zmiana hasła</h5>
                                        <form method="post" action="/projekt_sklep/user/user_change_password.php">
                                            <div class="mb-3">
                                                <label class="form-label">Aktualne hasło</label>
                                                <input type="password" name="current_password" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nowe hasło</label>
                                                <input type="password" name="new_password" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Powtórz nowe hasło</label>
                                                <input type="password" name="confirm_password" class="form-control" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Zmień hasło</button>
                                            <a href="user_profile.php" class="btn btn-secondary ms-2">Wróć do profilu</a>
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
    <?= include '../structure/footer.php'; ?>
</body>

</html>