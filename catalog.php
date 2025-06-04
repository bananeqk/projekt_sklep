<?php session_start(); ?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="css/catalog.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&family=Syne:wght@400..800&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bitter:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

    <title>Main - strona</title>
</head>

<body>






    <!--Skrypty bootstrapa do uruchomienia m.in. karuzeli-->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-..."
        crossorigin="anonymous"></script>









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

    <body>
        <div class="container py-5" style="padding-top: 100px;">
            <!-- Top Bar -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">Kolekcja</h4>
                <form method="get" class="d-flex gap-2 align-items-center">
                    <span class="text-muted">Sortuj przez:</span>
                    <select name="sort" class="sort-btn form-select form-select-sm" onchange="this.form.submit()">
                        <option value="newest" <?= (!isset($_GET['sort']) || $_GET['sort'] == 'newest') ? 'selected' : '' ?>>Najnowsze</option>
                        <option value="price_asc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') ? 'selected' : '' ?>>Cena rosnąco</option>
                        <option value="price_desc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') ? 'selected' : '' ?>>Cena malejąco</option>
                        <option value="rating" <?= (isset($_GET['sort']) && $_GET['sort'] == 'rating') ? 'selected' : '' ?>>Ocena</option>
                        <option value="color_asc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'color_asc') ? 'selected' : '' ?>>Kolor A-Z</option>
                        <option value="color_desc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'color_desc') ? 'selected' : '' ?>>Kolor Z-A</option>
                    </select>
                </form>
            </div>

            <div class="row g-4">
                <!-- Filters Sidebar -->
                <div class="col-lg-3">
                    <form method="get" id="filtersForm">
                        <div class="filter-sidebar p-4 shadow-sm">
                            <div class="filter-group">
                                <h6 class="mb-3">Kategorie</h6>
                                <?php
                                require_once("misc/database.php");
                                $cat_result = mysqli_query($conn, "SELECT id, kategoria FROM kategorie");
                                while ($cat = mysqli_fetch_assoc($cat_result)) {
                                    $checked = (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'checked' : '';
                                    echo '<div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="category" value="' . $cat['id'] . '" id="cat' . $cat['id'] . '" ' . $checked . '>
                                            <label class="form-check-label" for="cat' . $cat['id'] . '">' . htmlspecialchars($cat['kategoria']) . '</label>
                                          </div>';
                                }
                                ?>
                            </div>
                            <div class="filter-group">
                                <h6 class="mb-3">Cena</h6>
                                <input type="range" class="form-range" min="0" max="1000"
                                    value="<?= isset($_GET['max_price']) ? intval($_GET['max_price']) : 1000 ?>"
                                    name="max_price" oninput="this.nextElementSibling.innerText=this.value+' zł'">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">0 zł</span>
                                    <span
                                        class="text-muted"><?= isset($_GET['max_price']) ? intval($_GET['max_price']) : 1000 ?>
                                        zł</span>
                                </div>
                            </div>
                            <div class="filter-group">
                                <h6 class="mb-3">Ocena</h6>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="rating" value="4" id="rating4"
                                        <?= (isset($_GET['rating']) && $_GET['rating'] == '4') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="rating4">
                                        <i class="bi bi-star-fill text-warning"></i> 4 & wyżej
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="rating" value="3" id="rating3"
                                        <?= (isset($_GET['rating']) && $_GET['rating'] == '3') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="rating3">
                                        <i class="bi bi-star-fill text-warning"></i> 3 & wyżej
                                    </label>
                                </div>
                            </div>
                            <div class="filter-group">
                                <h6 class="mb-3">Kolor</h6>
                                <?php
                                $color_result = mysqli_query($conn, "SELECT id, kolor FROM kolory");
                                while ($color = mysqli_fetch_assoc($color_result)) {
                                    $val = htmlspecialchars($color['id']);
                                    $name = htmlspecialchars($color['kolor']);
                                    $checked = (isset($_GET['color']) && $_GET['color'] == $val) ? 'checked' : '';
                                    echo '<div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="color" value="' . $val . '" id="color_' . $val . '" ' . $checked . '>
                                            <label class="form-check-label" for="color_' . $val . '">' . $name . '</label>
                                          </div>';
                                }
                                ?>
                            </div>
                            <button class="btn btn-outline-dark w-100" type="submit">Zastosuj filtr</button><br><br>
                            <a href="catalog.php" class="btn btn-dark w-100">Resetuj filtr</a>
                        </div>
                    </form>
                </div>
                <!-- Product Grid -->
                <div class="col-lg-9">
                    <div class="row g-4">
                        <?php
                        // Budowanie zapytania SQL z filtrami
                        $where = [];
                        if (isset($_GET['category']) && is_numeric($_GET['category'])) {
                            $where[] = "id_kategorii = " . intval($_GET['category']);
                        }
                        if (isset($_GET['max_price']) && is_numeric($_GET['max_price'])) {
                            $where[] = "cena <= " . floatval($_GET['max_price']);
                        }
                        if (isset($_GET['rating']) && is_numeric($_GET['rating'])) {
                            $where[] = "ocena >= " . intval($_GET['rating']);
                        }
                        if (isset($_GET['color']) && $_GET['color'] !== '') {
                            $where[] = "kolor_id = " . intval($_GET['color']);
                        }
                        $where_sql = $where ? "WHERE " . implode(" AND ", $where) : "";

                        // Sortowanie
                        $order = "ORDER BY id DESC";
                        if (isset($_GET['sort'])) {
                            if ($_GET['sort'] == 'price_asc')
                                $order = "ORDER BY cena ASC";
                            elseif ($_GET['sort'] == 'price_desc')
                                $order = "ORDER BY cena DESC";
                            elseif ($_GET['sort'] == 'rating')
                                $order = "ORDER BY ocena DESC";
                            elseif ($_GET['sort'] == 'color_asc')
                                $order = "ORDER BY kolor ASC";
                            elseif ($_GET['sort'] == 'color_desc')
                                $order = "ORDER BY kolor DESC";
                        }

                        $sql = "SELECT * FROM produkty $where_sql $order";
                        $result = mysqli_query($conn, $sql);
                        while ($row = mysqli_fetch_assoc($result)) {
                            $discount = (int) $row['znizka'];
                            $price = number_format($row['cena'], 2);
                            $final_price = $discount > 0 ? number_format($row['cena'] * (1 - $discount / 100), 2) : $price;
                            ?>
                            <div class="col-md-4">
                                <a href="product_card/product_card.php?id=<?= $row['id'] ?>"
                                    style="text-decoration:none;color:inherit;">
                                    <div class="product-card shadow-sm">
                                        <div class="position-relative">
                                            <img src="produkty/<?= htmlspecialchars($row['img_path']) ?>"
                                                class="product-image w-100" alt="Product">
                                            <?php if ($discount > 0): ?>
                                                <span class="discount-badge">-<?= $discount ?>%</span>
                                            <?php endif; ?>
                                            <button class="wishlist-btn" type="button" tabindex="-1">
                                                <i class="bi bi-heart"></i>
                                            </button>
                                        </div>
                                        <div class="p-3">
                                            <h5 class="mb-1 header"><?= htmlspecialchars($row['nazwa']) ?></h5>
                                            <h6 class="mb-1 header-child"><?= htmlspecialchars($row['opis']) ?></h6>
                                            <div class="rating-stars mb-2">
                                                <?php
                                                $rating = (float) $row['ocena'];
                                                for ($i = 1; $i <= 5; $i++) {
                                                    if ($rating >= $i) {
                                                        echo '<i class="bi bi-star-fill"></i>';
                                                    } elseif ($rating >= $i - 0.5) {
                                                        echo '<i class="bi bi-star-half"></i>';
                                                    } else {
                                                        echo '<i class="bi bi-star"></i>';
                                                    }
                                                }
                                                ?>
                                                <span class="text-muted ms-2">(<?= $rating ?>)</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="price"><?= $final_price ?> zł</span>
                                            </div>
                                            <?php if ($discount > 0): ?>
                                                <small class="text-secondary">Cena regularna: <?= $price ?> zł</small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer text-center">
            <div class="container">
                <p>Copyright &copy; 2025. Projekt na Aplikacje Internetowe.</p>
                <p>Wykonane przez <span class="text-danger">&hearts;</span> Szymon Garncarz i Szymon Małczok</p>
            </div>
        </footer>
        <!-- Bootstrap 5.3 JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>

</html>

</body>

</html>