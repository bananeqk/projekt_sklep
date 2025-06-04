<?php
session_start();
require_once("../misc/database.php");

$product = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = mysqli_query($conn, "SELECT * FROM produkty p JOIN kolory k ON p.kolor_id = k.id WHERE p.id = $id");
    $product = mysqli_fetch_assoc($result);
}
if (!$product) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Nie znaleziono produktu.</div></div>";
    exit;
}
// Przygotuj galerię (jeśli masz tylko jedno zdjęcie, powiel je)
$gallery = [$product['img_path']];
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="../css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/catalog.css">
    <link rel="stylesheet" href="../css/product_card.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;700&family=Bitter:wght@700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

    <title><?= htmlspecialchars($product['nazwa']) ?> - Produkt</title>
    <style>
        body {
            background: #ffffff;
            font-family: 'Rubik', 'Bitter', sans-serif;
        }

        .product-zalando-main {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 10px 32px 0 rgba(60, 72, 88, .13);
            padding: 2.5rem 2rem 2rem 2rem;
            margin-top: 0;
            margin-bottom: 40px;
            border: 1px solid #f1f3f6;
        }

        .product-gallery {
            display: flex;
            flex-direction: column;
            gap: 12px;
            align-items: center;
        }

        .product-gallery-thumb {
            width: 70px;
            height: 90px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid transparent;
            cursor: pointer;
            transition: border 0.2s;
        }

        .product-gallery-thumb.active,
        .product-gallery-thumb:hover {
            border: 2px solid #000;
        }

        .product-gallery-main {
            width: 350px;
            height: 420px;
            object-fit: cover;
            border-radius: 12px;
            background: #f8f9fa;
            box-shadow: 0 4px 24px 0 rgba(60, 72, 88, .10);
        }

        .product-zalando-title {
            font-family: 'Bitter', serif;
            font-size: 2.1rem;
            font-weight: 600;
            color: #22223b;
            margin-bottom: 0.2rem;
        }

        .product-zalando-subtitle {
            font-family: 'Rubik', sans-serif;
            font-size: 1.2rem;
            font-weight: 500;
            color: #22223b;
            margin-bottom: 1.2rem;
        }

        .product-zalando-rating {
            font-size: 1.2rem;
            color: #000;
            margin-bottom: 0.7rem;
        }

        .product-zalando-rating .bi {
            font-size: 16px;
            color: #000;
            vertical-align: middle;
        }

        .product-zalando-rating .bi-star-fill,
        .product-zalando-rating .bi-star-half {
            color: #000000;
        }

        .product-zalando-rating .bi-star {
            color: #bbb;
        }

        .product-zalando-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: #dc2626;
            font-family: 'Rubik', sans-serif;
        }

        .product-zalando-oldprice {
            font-size: 0.9rem;
            color: #888;
            text-decoration: line-through;
            margin-left: 10px;
        }

        .product-zalando-discount {
            background: #dc2626;
            color: #fff;
            font-size: 0.9rem;
            padding: 4px 12px;
            border-radius: 8px;
            margin-left: 10px;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .product-zalando-color {
            font-weight: 600;
            margin-top: 1.2rem;
            margin-bottom: 0.7rem;
        }

        .product-zalando-color-box {
            display: inline-block;
            width: 38px;
            height: 38px;
            border-radius: 8px;
            border: 2px solid #000;
            margin-right: 8px;
            background: #222;
            vertical-align: middle;
        }

        .product-zalando-desc {
            font-family: 'Rubik', sans-serif;
            color: #444;
            font-size: 1.08rem;
            margin-bottom: 1.2rem;
        }

        .product-zalando-size {
            margin: 1.2rem 0 1.2rem 0;
        }

        .product-zalando-size select {
            width: 100%;
            border-radius: 8px;
            border: 1px solid #bbb;
            padding: 0.7rem 1rem;
            font-size: 1.1rem;
        }

        .product-zalando-btn-main {
            width: 100%;
            background: #000;
            color: #fff;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1.1rem;
            padding: 0.9rem 0;
            border: none;
            margin-bottom: 0.7rem;
            transition: background 0.2s;
        }

        .product-zalando-btn-main:hover {
            background: #222;
        }

        .product-zalando-btn-wishlist {
            width: 100%;
            border: 2px solid #000;
            color: #000;
            background: #fff;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1.1rem;
            padding: 0.9rem 0;
            margin-bottom: 0.7rem;
            transition: background 0.2s, color 0.2s;
        }

        .product-zalando-btn-wishlist:hover {
            background: #000;
            color: #fff;
        }

        .product-zalando-info {
            background: #f3f4f6;
            border-radius: 10px;
            padding: 1rem 1.2rem;
            font-size: 1rem;
            margin-bottom: 1rem;
            color: #222;
        }

        .product-zalando-popular {
            background: #f3f4f6;
            border-radius: 10px;
            padding: 1rem 1.2rem;
            font-size: 1rem;
            color: #222;
            margin-top: 1.2rem;
        }

        @media (max-width: 991px) {
            .product-zalando-main {
                padding: 1.2rem 0.7rem 1.2rem 0.7rem;
            }

            .product-gallery-main {
                width: 100%;
                height: 300px;
            }
        }

        @media (max-width: 767px) {
            .product-gallery-main {
                width: 100%;
                height: 220px;
            }
        }
    </style>
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

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="product-zalando-main">
                    <div class="row g-4">
                        <!-- Gallery -->
                        <div class="col-md-1 d-none d-md-flex flex-column align-items-center">
                            <?php foreach ($gallery as $i => $img): ?>
                                <img src="../produkty/<?= htmlspecialchars($img) ?>"
                                    class="product-gallery-thumb<?= $i == 0 ? ' active' : '' ?>" onclick="changeMainImage(this)"
                                    alt="miniatura">
                            <?php endforeach; ?>
                        </div>
                        <!-- Main Image -->
                        <div class="col-md-5 d-flex align-items-center justify-content-center">
                            <img src="../produkty/<?= htmlspecialchars($gallery[0]) ?>" id="mainProductImage"
                                class="product-gallery-main" alt="Produkt">
                        </div>
                        <!-- Details -->
                        <div class="col-md-6">
                            <div class="product-zalando-title"><?= htmlspecialchars($product['nazwa']) ?></div>
                            <div class="product-zalando-subtitle"><?= htmlspecialchars($product['opis']) ?></div>
                            <div class="product-zalando-rating">
                                <?php
                                $rating = (float) $product['ocena'];
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
                                <span class="text-muted ms-2" style="font-size:13px;">(<?= $rating ?>)</span>
                            </div>
                            <div class="mb-2">
                                <span
                                    class="product-zalando-price"><?= number_format($product['cena'] * (1 - $product['znizka'] / 100), 2) ?>
                                    zł</span>
                                <?php if ($product['znizka'] > 0): ?>
                                    <span class="product-zalando-oldprice"><?= number_format($product['cena'], 2) ?>
                                        zł</span>
                                    <span class="product-zalando-discount">-<?= $product['znizka'] ?>%</span>
                                <?php endif; ?>
                            </div>
                            <div class="product-zalando-color mb-3">
                                Kolor:
                                <span
                                    style="font-weight:700;"><?= htmlspecialchars($product['kolor'] ?? 'brak') ?></span>
                                <div class="d-flex mt-2" style="gap:16px;">
                                    <?php
                                    // Pobierz inne produkty z tej samej kategorii, ale o innym kolorze
                                    $cat_id = intval($product['id_kategorii']);
                                    $current_color = intval($product['kolor_id']);
                                    $sim_res = mysqli_query($conn, "SELECT id, img_path, kolor_id FROM produkty WHERE id_kategorii=$cat_id AND id!={$product['id']} AND kolor_id!=$current_color LIMIT 3");
                                    $has_other = false;
                                    while ($sim = mysqli_fetch_assoc($sim_res)) {
                                        if (!$has_other) {
                                            ?>
                                            <img src="../produkty/<?= htmlspecialchars($product['img_path']) ?>"
                                                class="product-gallery-thumb active" style="border:2px solid #000;"
                                                alt="miniatura">
                                            <?php
                                            $has_other = true;
                                        }
                                        ?>
                                        <a href="product_card.php?id=<?= $sim['id'] ?>" style="display:inline-block;">
                                            <img src="../produkty/<?= htmlspecialchars($sim['img_path']) ?>"
                                                class="product-gallery-thumb" alt="miniatura">
                                        </a>
                                        <?php
                                    }
                                    if (!$has_other) {
                                        ?>
                                        <img src="../produkty/<?= htmlspecialchars($product['img_path']) ?>"
                                            class="product-gallery-thumb active" style="border:2px solid #000;"
                                            alt="miniatura">
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="product-zalando-size">
                                <select>
                                    <option>Wybierz rozmiar</option>
                                    <option>S</option>
                                    <option>M</option>
                                    <option>L</option>
                                    <option>XL</option>
                                </select>
                            </div>
                            <button class="product-zalando-btn-main"><i class="bi bi-cart-plus"></i> Dodaj do
                                koszyka</button>
                            <button class="product-zalando-btn-wishlist"><i class="bi bi-heart"></i></button>
                            <div class="product-zalando-info mt-3">
                                <i class="bi bi-truck"></i> Wysyłamy do 5-6 dni roboczych, zależności od lokalizacji.
                            </div>
                            <div class="product-zalando-popular">
                                <i class="bi bi-heart"></i> To jeden z naszych najpopularniejszych i najrzadziej
                                zwracanych artykułów
                            </div>
                            <!-- Akordeon z materiałem i szczegółami -->
                            <div class="mt-4">
                                <div class="accordion" id="productAccordion">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingMaterial">
                                            <button class="accordion-button collapsed fw-bold" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseMaterial"
                                                aria-expanded="false" aria-controls="collapseMaterial">
                                                Materiał i wskazówki pielęgnacyjne
                                            </button>
                                        </h2>
                                        <div id="collapseMaterial" class="accordion-collapse collapse"
                                            aria-labelledby="headingMaterial" data-bs-parent="#productAccordion">
                                            <div class="accordion-body">
                                                <!-- Tu możesz dodać dynamiczne dane z bazy, np. $product['material'] -->
                                                100% bawełna. Prać w 30°C. Nie wybielać.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingDetails">
                                            <button class="accordion-button collapsed fw-bold" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseDetails"
                                                aria-expanded="false" aria-controls="collapseDetails">
                                                Szczegóły produktu
                                            </button>
                                        </h2>
                                        <div id="collapseDetails" class="accordion-collapse collapse"
                                            aria-labelledby="headingDetails" data-bs-parent="#productAccordion">
                                            <div class="accordion-body">
                                                <!-- Tu możesz dodać dynamiczne dane z bazy, np. $product['szczegoly'] -->
                                                <ul class="mb-0">
                                                    <li>Wyprodukowano w Polsce</li>
                                                    <li>Unisex</li>
                                                    <li>Sezon: Wiosna/Lato</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Podobne produkty -->
                <div class="mt-5">
                    <h5 class="mb-3" style="font-weight:700;">Podobne produkty</h5>
                    <div class="d-flex flex-row gap-3">
                        <?php
                        $sim_res2 = mysqli_query($conn, "SELECT id, img_path, nazwa FROM produkty WHERE id_kategorii=$cat_id AND id!={$product['id']} LIMIT 3");
                        while ($sim2 = mysqli_fetch_assoc($sim_res2)) {
                            echo '<a href="product_card.php?id=' . $sim2['id'] . '" style="text-decoration:none;color:inherit;">';
                            echo '<div style="width:110px;text-align:center">';
                            echo '<img src="../produkty/' . htmlspecialchars($sim2['img_path']) . '" style="width:100px;height:100px;object-fit:cover;border-radius:8px;background:#f8f9fa;">';
                            echo '<div style="font-size:13px;margin-top:6px;">' . htmlspecialchars($sim2['nazwa']) . '</div>';
                            echo '</div></a>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function changeMainImage(el) {
            document.getElementById('mainProductImage').src = el.src;
            document.querySelectorAll('.product-gallery-thumb').forEach(t => t.classList.remove('active'));
            el.classList.add('active');
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-..."
        crossorigin="anonymous"></script>
    <?php include '../adminDashboard/footer.php'; ?>
</body>

</html>