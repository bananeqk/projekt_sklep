<?php
session_start();
require_once("../misc/database.php");

if (!isset($_SESSION["user"]["uprawnienia_id"]) || $_SESSION["user"]["uprawnienia_id"] != 2) {
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
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
                <i class="fas fa-align-left primary-text fs-4 me-3" id="menu-toggle"></i>
                <h2 class="fs-2 m-0">Karuzela - index.php</h2>
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation">
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
                        <h4 class="carousel-form-title text-center">Dodaj nowy produkt</h4>
                        <form action="update_product.php" method="post" enctype="multipart/form-data" class="carousel-form">
                            <div class="mb-3">
                                <label for="product_image" class="form-label fw-bold">Wybierz zdjęcie <span class="text-danger">*</span></label>
                                <input type="file" name="product_image" id="product_image" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="product_name" class="form-label fw-bold">Nazwa produktu <span class="text-danger">*</span></label>
                                <input type="text" name="product_name" id="product_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="product_desc" class="form-label">Opis</label>
                                <input type="text" name="product_desc" id="product_desc" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="product_price" class="form-label fw-bold">Cena <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="product_price" id="product_price" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="product_category" class="form-label">Kategoria</label>
                                <select name="product_category" id="product_category" class="form-control">
                                    <?php
                                    require_once("../misc/database.php");
                                    $cat_result = mysqli_query($conn, "SELECT id, kategoria FROM kategorie");
                                    while ($cat = mysqli_fetch_assoc($cat_result)) {
                                        echo '<option value="' . $cat['id'] . '">' . htmlspecialchars($cat['kategoria']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="product_discount" class="form-label">Zniżka (%)</label>
                                <input type="number" step="1" min="0" max="100" name="product_discount" id="product_discount" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="product_color" class="form-label">Kolor</label>
                                <select name="product_color" id="product_color" class="form-control">
                                    <?php
                                    // Ppobieranie koloru
                                    $color_result = mysqli_query($conn, "SELECT id, kolor FROM kolory");
                                    while ($color = mysqli_fetch_assoc($color_result)) {
                                        $val = htmlspecialchars($color['id']);
                                        $name = htmlspecialchars($color['kolor']);
                                        echo '<option value="' . $val . '">' . $name . '</option>';
                                    }
                                    ?>
                                    <option value="">Brak</option>
                                </select>
                            </div>
                            <div class="d-grid">
                                <input type="submit" name="upload_product" class="btn btn-dark btn-block" value="Dodaj produkt">
                            </div>
                        </form>
                    </div>
                    <div class="card shadow-sm border-0 rounded-4 mt-4">
                        <div class="card-body">
                            <h5 class="fw-bold text-secondary mb-3">Lista produktów</h5>
                            <!-- wyszukiwarka po nazwie produktu -->
                            <form method="get" class="mb-3 d-flex gap-2">
                                <input type="text" name="search" class="form-control" placeholder="Szukaj po nazwie..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                                <button type="submit" class="btn btn-outline-dark">Szukaj</button>
                                <?php if (isset($_GET['search']) && $_GET['search'] !== ''): ?>
                                    <a href="products.php" class="btn btn-secondary">Wyczyść</a>
                                <?php endif; ?>
                            </form>
                            <?php
                            require_once("../misc/database.php");
                            $where = "";
                            $params = [];
                            $types = "";
                            if (isset($_GET['search']) && $_GET['search'] !== '') {
                                $search = '%' . $_GET['search'] . '%';
                                $where = "WHERE p.nazwa LIKE ?";
                                $params[] = $search;
                                $types .= "s";
                            }
                            $sql = "SELECT p.*, k.kategoria, c.kolor FROM produkty p
                                    LEFT JOIN kategorie k ON p.id_kategorii = k.id
                                    LEFT JOIN kolory c ON p.kolor_id = c.id
                                    $where";
                            // ten na dole warunek sprawdza czy cos zostalo wpisane w wyszukiwarke no jesli nie to normalnie pokazuje wszystkie produkty
                            if ($where) {
                                $stmt = mysqli_prepare($conn, $sql);
                                mysqli_stmt_bind_param($stmt, $types, ...$params);
                                mysqli_stmt_execute($stmt);
                                $result = mysqli_stmt_get_result($stmt);
                            } else {
                                $result = mysqli_query($conn, $sql);
                            }
                            ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">Zdjęcie</th>
                                            <th class="text-center">Nazwa</th>
                                            <th class="text-center">Opis</th>
                                            <th class="text-center">Cena</th>
                                            <th class="text-center">Kategoria</th>
                                            <th class="text-center">Zniżka</th>
                                            <th class="text-center">Ocena</th>
                                            <th class="text-center">Kolor</th>
                                            <th class="text-center">Edytuj</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                            <tr>
                                                <td class="text-center"><?= htmlspecialchars($row['id']) ?></td>
                                                <td class="text-center">
                                                    <img src="../zdjecia/produkty/<?= htmlspecialchars($row['img_path']) ?>" width="80px">
                                                </td>
                                                <td class="text-center"><?= htmlspecialchars($row['nazwa']) ?></td>
                                                <td class="text-center"><?= htmlspecialchars($row['opis']) ?></td>
                                                <td class="text-center"><?= htmlspecialchars($row['cena']) ?> zł</td>
                                                <td class="text-center"><?= htmlspecialchars($row['kategoria']) ?></td>
                                                <td class="text-center"><?= htmlspecialchars($row['znizka']) ?>%</td>
                                                <td class="text-center"><?= htmlspecialchars($row['ocena']) ?></td>
                                                <td class="text-center"><?= htmlspecialchars($row['kolor'] ?? '') ?></td>
                                                <td class="text-center">
                                                    <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn btn-dark btn-sm">
                                                        <i class="fas fa-edit"></i> Edytuj
                                                    </a>
                                                    <a href="delete_product.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                                                       onclick="return confirm('Na pewno usunąć produkt?');">
                                                        <i class="fas fa-trash-alt"></i> Usuń
                                                    </a>
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
<?php include("../structure/admin/script.php"); ?>
</body>

</html>