<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();
$cart_count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <?php include("structure/header.php"); ?>
    <link rel="stylesheet" href="css/catalog.css">

    <title>Main - strona</title>
    <style>
        body {
            padding-top: 80px !important;
        }

        @media (max-width: 991px) {
            body {
                padding-top: 100px !important;
            }
        }
    </style>
</head>

<body>
    <?= include("structure/nav.php"); ?>

    <body>
        <div class="container py-5">
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
                                <input type="range" class="form-range" min="0" max="5000"
                                    value="<?= isset($_GET['max_price']) ? intval($_GET['max_price']) : 5000 ?>"
                                    name="max_price" oninput="this.nextElementSibling.innerText=this.value+' zł'">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">0 zł</span>
                                    <span class="text-muted"><?= isset($_GET['max_price']) ? intval($_GET['max_price']) : 5000 ?> zł</span>
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
                                <div class="product-card shadow-sm">
                                    <a href="product_card.php?id=<?= $row['id'] ?>"
                                        style="text-decoration:none;color:inherit;">
                                        <div class="position-relative">
                                            <img src="zdjecia/produkty/<?= htmlspecialchars($row['img_path']) ?>"
                                                class="product-image w-100" alt="Product">
                                            <?php if ($discount > 0): ?>
                                                <span class="discount-badge">-<?= $discount ?>%</span>
                                            <?php endif; ?>
                                        </div>
                                    </a>
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
                                            <span class="price<?= $discount > 0 ? ' text-danger fw-bold' : '' ?>">
                                                <?= $final_price ?> zł
                                            </span>
                                        </div>
                                        <small class="text-secondary">Cena regularna: <?= $price ?> zł</small>
                                        <!-- Przycisk dodawania do koszyka (zawsze 1 sztuka) -->
                                        <form method="post" action="shopping_cart/add_to_cart.php" class="mt-2">
                                            <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-dark btn-sm w-100">
                                                <i class="bi bi-cart-plus"></i> Dodaj do koszyka
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php include("structure/footer.php"); ?>
    </body>

</html>