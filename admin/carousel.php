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
    <div class="container-fluid px-4">
        <div class="row justify-content-center">
            <?php if (isset($_GET['deleted'])): ?>
                <div class="alert alert-success">Zdjęcie zostało usunięte.</div>
            <?php endif; ?>
            <div class="col-lg-8">
                <div class="carousel-card shadow-sm border-0 rounded-4">
                    <h4 class="carousel-form-title text-center">Dodaj nowe zdjęcie do karuzeli</h4>
                    <form action="update_carousel.php" method="post" enctype="multipart/form-data"
                        class="carousel-form">
                        <div class="mb-3">
                            <label for="image" class="form-label fw-bold">Wybierz zdjęcie <span
                                    class="text-danger">*</span></label>
                            <input type="file" name="image" id="image" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="caption_title" class="form-label">Tytuł (opcjonalnie)</label>
                            <input type="text" name="caption_title" id="caption_title" class="form-control"
                                placeholder="Tytuł">
                        </div>
                        <div class="mb-3">
                            <label for="caption_text" class="form-label">Opis (opcjonalnie)</label>
                            <input type="text" name="caption_text" id="caption_text" class="form-control"
                                placeholder="Opis">
                        </div>
                        <div class="d-grid">
                            <input type="submit" name="upload" class="btn btn-warning btn-block"
                                value="Prześlij zdjęcie">
                        </div>
                    </form>
                </div>
                <div class="card shadow-sm border-0 rounded-4 mt-4">
                    <div class="card-body">
                        <h5 class="fw-bold text-secondary mb-3">Lista zdjęć w karuzeli</h5>
                        <?php
                        require_once("../misc/database.php");
                        $sql = "SELECT * FROM karuzela";
                        $result = mysqli_query($conn, $sql);
                        ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle carousel-table">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">ID</th>
                                        <th class="text-center">Zdjęcie</th>
                                        <th class="text-center">Tytuł</th>
                                        <th class="text-center">Opis</th>
                                        <th class="text-center">Edytuj</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                        <tr>
                                            <td class="text-center"><?= htmlspecialchars($row['id']) ?></td>
                                            <td class="text-center"><img src="../zdjecia/carousel/<?php echo $row['img_sciezka'] ?>"
                                                    width="120px" class="carousel-img-darken">
                                            </td>
                                            <td class="text-center"><?= htmlspecialchars($row['img_tytul']) ?>
                                            </td>
                                            <td class="text-center"><?= htmlspecialchars($row['img_tekst']) ?>
                                            </td>
                                            <td class="text-center">
                                                <a href="edit_carousel.php?id=<?= $row['id'] ?>"
                                                    class="btn btn-primary btn-sm"><i class="fas fa-edit"></i>
                                                    Edytuj</a>
                                                <a href="delete_img.php?id=<?= $row['id'] ?>"
                                                    class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i>
                                                    Usuń</a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php include "../structure/admin/script.php"; ?>
</body>

</html>