<?php
session_start();
require_once("../misc/database.php");
if (!isset($_SESSION["user"]["id"])) {
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <?php include '../structure/cart_structure/header.php'; ?>
    <link rel="stylesheet" href="../css/user_profile_style.css">
    <title>Profil użytkownika</title>
</head>

<body>
    <?php include '../structure/cart_structure/nav.php'; ?>
    <div class="container mt-3 mb-4">
        <div class="d-flex justify-content-end">
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
                        <form method="post" enctype="multipart/form-data" id="cover-form"
                            action="user_profile_cover_update.php">
                            <label for="cover-input"
                                style="cursor:pointer; position:absolute; top:0; right:0; z-index:2; background:#fff; border-radius:6px; padding:6px 16px; margin:10px; border:1px solid #ccc; font-weight:500; box-shadow:0 2px 8px rgba(0,0,0,0.04);">
                                <i class="fas fa-edit me-2"></i>Edytuj baner
                            </label>
                            <input type="file" name="cover_img" id="cover-input" accept="image/*" class="d-none"
                                onchange="document.getElementById('cover-form').submit();">
                        </form>
                        <img src="<?= isset($_SESSION["user"]["cover_img"]) && $_SESSION["user"]["cover_img"] ? '../' . htmlspecialchars($_SESSION["user"]["cover_img"]) : 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1200&q=80' ?>"
                            alt="Cover" class="banner-img-darken"
                            style="width:100%;height:240px;object-fit:cover;border-radius:15px;">
                    </div>
                    <div class="text-center">
                        <div class="position-relative d-inline-block">
                            <form method="post" enctype="multipart/form-data" id="profile-img-form"
                                action="user_profile_img_update.php">
                                <label for="profile-img-input" style="cursor:pointer;">
                                    <img src="<?= isset($_SESSION["user"]["profil_img"]) && $_SESSION["user"]["profil_img"] ? '../' . htmlspecialchars($_SESSION["user"]["profil_img"]) : 'https://img.icons8.com/bubbles/64/000000/user.png' ?>"
                                        class="rounded-circle profile-pic profile-pic-darken" alt="Profile Picture"
                                        style="object-fit:cover; width:140px; height:140px; border:4px solid #fff; background:#fff;">
                                </label>
                                <input type="file" name="profil_img" id="profile-img-input" accept="image/*"
                                    class="d-none" onchange="document.getElementById('profile-img-form').submit();">
                            </form>
                        </div>
                        <h3 class="mt-3 mb-1"><?= htmlspecialchars($_SESSION["user"]["imie_nazwisko"] ?? '') ?></h3>
                        <div class="d-flex justify-content-center gap-2 mb-4"></div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0">
                            <div class="row g-0">
                                <div class="col-lg-3 border-end">
                                    <div class="p-4">
                                        <div class="nav flex-column nav-pills">
                                            <a class="nav-link active" href="user_profile.php"><i
                                                    class="fas fa-user me-2"></i>Informacje o koncie</a>
                                            <!-- Usunięto link do sekcji Security -->
                                            <a class="nav-link" href="security.php"><i
                                                    class="fas fa-shield-alt me-2"></i>Zabezpieczenie</a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Content Area -->
                                <div class="col-lg-9">
                                    <div class="p-4">
                                        <!-- Personal Information -->
                                        <div class="mb-4" id="personal-section">
                                            <h5 class="mb-4">Informacje osobiste</h5>
                                            <form method="post" action="user_profile_update.php">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Imię i nazwisko</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= htmlspecialchars($_SESSION["user"]["imie_nazwisko"] ?? '') ?>"
                                                            readonly>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Email</label>
                                                        <input type="email" class="form-control" name="email"
                                                            value="<?= htmlspecialchars($_SESSION["user"]["email"] ?? '') ?>">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Numer telefonu</label>
                                                        <input type="tel" class="form-control" name="telefon"
                                                            value="<?= htmlspecialchars($_SESSION["user"]["telefon"] ?? '') ?>">
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="mt-5">
                                            <h5 class="mb-4">Historia zamówień</h5>
                                            <?php if (isset($_SESSION["user"]["id"])): ?>
                                                <?php
                                                $uid = (int) $_SESSION["user"]["id"];
                                                // historia zamowien
                                                // pierwsza czesc dotyczy adresu i daty
                                                $zam_stmt = mysqli_prepare($conn, "SELECT z.*, a.miasto, a.ulica, a.kod_pocztowy FROM zamowienia z JOIN adresy_uzytkownikow a ON z.id_adresu=a.id WHERE z.id_uzytkownika=? ORDER BY z.data_zoenia DESC");
                                                mysqli_stmt_bind_param($zam_stmt, "i", $uid);
                                                mysqli_stmt_execute($zam_stmt);
                                                $zam_res = mysqli_stmt_get_result($zam_stmt);
                                                ?>
                                                <?php if (mysqli_num_rows($zam_res) > 0): ?>
                                                    <?php while ($zam = mysqli_fetch_assoc($zam_res)): ?>
                                                        <div class="mb-3 p-3 border rounded">
                                                            <div>
                                                                <b>Zamówienie #<?= (int) $zam['id'] ?></b> z dnia
                                                                <?= htmlspecialchars($zam['data_zoenia']) ?>
                                                            </div>
                                                            <div>
                                                                Adres: <?= htmlspecialchars($zam['ulica']) ?>,
                                                                <?= htmlspecialchars($zam['miasto']) ?>
                                                                <?= htmlspecialchars($zam['kod_pocztowy']) ?>
                                                            </div>
                                                            <?php
                                                            // druga czesc dotyczy produktow i pobierania informacji o nich
                                                            $zp_stmt = mysqli_prepare($conn, "SELECT p.nazwa, p.img_path, zp.ilosc, zp.cena FROM zamowienie_produkty zp JOIN produkty p ON zp.id_produktu=p.id WHERE zp.id_zamowienia=?");
                                                            mysqli_stmt_bind_param($zp_stmt, "i", $zam['id']);
                                                            mysqli_stmt_execute($zp_stmt);
                                                            $zp_res = mysqli_stmt_get_result($zp_stmt);
                                                            ?>
                                                            <?php if (mysqli_num_rows($zp_res) > 0): ?>
                                                                <ul class="mb-1">
                                                                    <?php while ($prod = mysqli_fetch_assoc($zp_res)): ?>
                                                                        <li class="d-flex align-items-center justify-content-between">
                                                                            <span>
                                                                                <?= htmlspecialchars($prod['nazwa']) ?> x
                                                                                <?= (int) $prod['ilosc'] ?>
                                                                                (<?= number_format($prod['cena'], 2) ?> zł/szt)
                                                                            </span>
                                                                            <?php if (!empty($prod['img_path'])): ?>
                                                                                <img src="../zdjecia/produkty/<?= htmlspecialchars($prod['img_path']) ?>"
                                                                                    alt=""
                                                                                    style="width:48px;height:48px;object-fit:cover;border-radius:6px;margin-left:12px;">
                                                                            <?php endif; ?>
                                                                        </li>
                                                                    <?php endwhile; ?>
                                                                </ul>
                                                            <?php else: ?>
                                                                <div class="text-muted">Brak produktów w zamówieniu.</div>
                                                            <?php endif;
                                                            mysqli_stmt_close($zp_stmt); ?>
                                                            <div>Koszt: <?= number_format($zam['koszt'], 2) ?> zł</div>
                                                        </div>
                                                    <?php endwhile; ?>
                                                <?php else: ?>
                                                    <div class="text-muted">Brak zamówień.</div>
                                                <?php endif;
                                                mysqli_stmt_close($zam_stmt); ?>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <h5 class="mb-4">Dziennik zdarzeń</h5>
                                            <?php if (isset($_SESSION["user"]["id"])): ?>
                                                <?php
                                                // dziennik zdarzen pokazuje wszystko jeszcze tylko musze dodac mozliwosc wyswietlania hasla
                                                $uid = (int) $_SESSION["user"]["id"];
                                                $per_page = 5;
                                                $log_page = isset($_GET['log_page']) ? max(1, (int) $_GET['log_page']) : 1;
                                                $offset = ($log_page - 1) * $per_page;

                                                $log_count_stmt = mysqli_prepare($conn, "SELECT COUNT(*) as cnt FROM dziennik WHERE user_id=?");
                                                mysqli_stmt_bind_param($log_count_stmt, "i", $uid);
                                                mysqli_stmt_execute($log_count_stmt);
                                                $log_count_res = mysqli_stmt_get_result($log_count_stmt);
                                                $log_count = mysqli_fetch_assoc($log_count_res)['cnt'] ?? 0;
                                                $log_pages = max(1, ceil($log_count / $per_page));
                                                mysqli_stmt_close($log_count_stmt);

                                                $log_stmt = mysqli_prepare($conn, "SELECT akcja, detale, data_stworzenia FROM dziennik WHERE user_id=? ORDER BY data_stworzenia DESC LIMIT ? OFFSET ?");
                                                mysqli_stmt_bind_param($log_stmt, "iii", $uid, $per_page, $offset);
                                                mysqli_stmt_execute($log_stmt);
                                                $log_res = mysqli_stmt_get_result($log_stmt);
                                                ?>
                                                <?php if (mysqli_num_rows($log_res) > 0): ?>
                                                    <?php while ($log = mysqli_fetch_assoc($log_res)): ?>
                                                        <div class="activity-item mb-3">
                                                            <h6 class="mb-1"><?= htmlspecialchars($log['akcja']) ?></h6>
                                                            <?php if ($log['detale']): ?>
                                                                <div class="text-muted small mb-1">
                                                                    <?= htmlspecialchars($log['detale']) ?>
                                                                </div>
                                                            <?php endif; ?>
                                                            <p class="text-muted small mb-0">
                                                                <?= htmlspecialchars($log['data_stworzenia']) ?>
                                                            </p>
                                                        </div>
                                                    <?php endwhile; ?>
                                                <?php else: ?>
                                                    <div class="text-muted">Brak zdarzeń.</div>
                                                <?php endif;
                                                mysqli_stmt_close($log_stmt); ?>
                                                <!-- PAGINATION NAV -->
                                                <?php if ($log_pages > 1): ?>
                                                    <nav aria-label="Paginacja dziennika zdarzeń">
                                                        <ul class="pagination justify-content-center mt-3">
                                                            <?php if ($log_page > 1): ?>
                                                                <li class="page-item">
                                                                    <a class="page-link" href="?log_page=<?= $log_page - 1 ?>"
                                                                        aria-label="Poprzednia">
                                                                        <span aria-hidden="true">&laquo;</span>
                                                                    </a>
                                                                </li>
                                                            <?php endif; ?>
                                                            <?php for ($i = 1; $i <= $log_pages; $i++): ?>
                                                                <li class="page-item<?= $i == $log_page ? ' active' : '' ?>">
                                                                    <a class="page-link" href="?log_page=<?= $i ?>"><?= $i ?></a>
                                                                </li>
                                                            <?php endfor; ?>
                                                            <?php if ($log_page < $log_pages): ?>
                                                                <li class="page-item">
                                                                    <a class="page-link" href="?log_page=<?= $log_page + 1 ?>"
                                                                        aria-label="Następna">
                                                                        <span aria-hidden="true">&raquo;</span>
                                                                    </a>
                                                                </li>
                                                            <?php endif; ?>
                                                        </ul>
                                                    </nav>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>

                                        <div class="mt-5">
                                            <h5 class="mb-4">Twoje recenzje produktów</h5>
                                            <?php
                                            // recenzje uzytkownika
                                            $rec_q = mysqli_query($conn, "SELECT r.*, p.nazwa FROM recenzje r JOIN produkty p ON r.id_produktu=p.id WHERE r.id_uzytkownika=" . (int) $_SESSION["user"]["id"] . " ORDER BY r.data DESC");
                                            if (mysqli_num_rows($rec_q) > 0):
                                                while ($rec = mysqli_fetch_assoc($rec_q)):
                                                    ?>
                                                    <div class="border rounded p-3 mb-3 bg-light">
                                                        <div class="d-flex align-items-center mb-2">
                                                            <strong><?= htmlspecialchars($rec['nazwa']) ?></strong>
                                                            <span class="ms-3 text-warning">
                                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                    <i
                                                                        class="bi <?= $i <= $rec['ocena'] ? 'bi-star-fill' : 'bi-star' ?>"></i>
                                                                <?php endfor; ?>
                                                            </span>
                                                            <span class="ms-auto text-muted"
                                                                style="font-size:13px;"><?= htmlspecialchars($rec['data']) ?></span>
                                                        </div>
                                                        <div><?= nl2br(htmlspecialchars($rec['tresc'])) ?></div>
                                                    </div>
                                                <?php endwhile; else: ?>
                                                <div class="text-muted">Nie dodałeś jeszcze żadnych recenzji.</div>
                                            <?php endif; ?>
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
    <?php include '../structure/footer.php'; ?>
</body>

</html>