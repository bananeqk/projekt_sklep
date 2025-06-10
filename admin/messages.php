<?php
session_start();
require_once("../misc/database.php");

if (!isset($_SESSION["user"]["uprawnienia_id"]) || $_SESSION["user"]["uprawnienia_id"] != 2) {
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <?php include("../structure/admin/head.php"); ?>
    <title>Document</title>
</head>
<body>
    <?php include("../structure/admin/sidebar.php"); ?>
<div id="wrapper">
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
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 fw-bold second-text text-dark"> 
                <i class="fas fa-user me-2"></i>
                <?php if (isset($_SESSION["user"])): ?>
                    <?= htmlspecialchars($_SESSION["user"]["imie_nazwisko"]) ?>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <div class="container py-5">
        <h2 class="mb-4"><i class="fas fa-envelope-open-text me-2"></i>Wiadomości od użytkowników</h2>
        <?php
        $res = mysqli_query($conn, "SELECT * FROM wiadomosci ORDER BY data_stworzenia DESC");
        if (mysqli_num_rows($res) > 0):
            while ($row = mysqli_fetch_assoc($res)):
                ?>
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-dark text-white d-flex align-items-center"
                        style="border-radius: .5rem .5rem 0 0;">
                        <i class="fa-solid fas fa-user fa-2x me-3"></i>
                        <div>
                            <div class="fw-bold"><?= htmlspecialchars($row['imie_nazwisko']) ?></div>
                            <small>
                                <i class="fa-solid fas fa-at"></i> <?= htmlspecialchars($row['email']) ?>
                                &nbsp;|&nbsp;
                                <i class="fa-solid fas fa-calendar-alt"></i> <?= htmlspecialchars($row['data_stworzenia']) ?>
                            </small>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <span class="badge bg-success text-dark"><i
                                    class="fa-solid fas fa-sign me-1"></i><?= htmlspecialchars($row['temat']) ?></span>
                        </div>
                        <div style="white-space: pre-line;"><?= htmlspecialchars($row['wiadomosc']) ?></div>
                    </div>
                </div>
            <?php endwhile; else: ?>
            <div class="alert alert-info mt-4">Brak wiadomości od użytkowników.</div>
        <?php endif; ?>
    </div>
</div>
<?php include '../adminDashboard/script.php'; ?>
</body>
</html>
