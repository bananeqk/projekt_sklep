<?php
// sprawdzanie czy jest jakiś błąd wyświetla się najczęściej na początku strony jak się wejdzie na źródło strony
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once("misc/database.php");

$product = null;
$id = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = mysqli_query($conn, "SELECT p.*, k.kolor FROM produkty p LEFT JOIN kolory k ON p.kolor_id = k.id WHERE p.id = $id");
    $product = mysqli_fetch_assoc($result);
}
// może się zdarzyć sytuacja że nie znajdzie produktu, dodałem to bo był duży problem że w bazie danych był id = 9 a na stronie id = 3 na dole jest sprawdzanie dalej czy id istnieje w bazie czy taki produkt jest 
if (!$product) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Nie znaleziono produktu.</div></div>";
    exit;
}
// Przygotuj galerię (jeśli masz tylko jedno zdjęcie, powiel je)
$gallery = [$product['img_path']];

// dodawanie recenzji
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user']['id'], $_POST['ocena'], $_POST['tresc'])) {
    $user_id = (int) $_SESSION['user']['id'];
    // nie usuwać ręcznie produktów z bazy danych, bo może się zdarzyć że nie będzie id produktu
    if (!isset($id) || !is_numeric($id)) {
        die("<div class='alert alert-danger'>Błąd: Nieprawidłowy produkt.</div>");
    }
    $prod_id = (int) $id;
    $ocena = max(1, min(5, (int) $_POST['ocena']));
    $tresc = trim($_POST['tresc']);

    // sprawdzam tutaj czy produkt jest w bazie
    $prod_check = mysqli_query($conn, "SELECT id FROM produkty WHERE id = $prod_id");
    if (!$prod_check || mysqli_num_rows($prod_check) == 0) {
        die("<div class='alert alert-danger'>Błąd: Produkt nie istnieje w bazie.</div>");
    }

    // żeby nie dublować od użytkownika recenzji, sprawdzam czy już istnieje
    $rec_check = mysqli_prepare($conn, "SELECT id FROM recenzje WHERE id_uzytkownika=? AND id_produktu=?");
    mysqli_stmt_bind_param($rec_check, "ii", $user_id, $prod_id);
    mysqli_stmt_execute($rec_check);
    $rec_res = mysqli_stmt_get_result($rec_check);

    if (mysqli_num_rows($rec_res) == 0 && $tresc !== '') {
        $now = date('Y-m-d H:i:s');
        $rec_add = mysqli_prepare($conn, "INSERT INTO recenzje (id_uzytkownika, id_produktu, ocena, tresc, data) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($rec_add, "iiiss", $user_id, $prod_id, $ocena, $tresc, $now);
        if (!mysqli_stmt_execute($rec_add)) {
            die("<div class='alert alert-danger'>Błąd przy dodawaniu recenzji: " . htmlspecialchars(mysqli_stmt_error($rec_add)) . "</div>");
        }
        mysqli_stmt_close($rec_add);

        // przelicza średnią ocenę produktu
        $avg_q = mysqli_query($conn, "SELECT AVG(ocena) as avg_ocena FROM recenzje WHERE id_produktu = $prod_id");
        if ($avg_q && ($avg = mysqli_fetch_assoc($avg_q))) {
            $avg_ocena = round($avg['avg_ocena'], 1);
            mysqli_query($conn, "UPDATE produkty SET ocena = $avg_ocena WHERE id = $prod_id");
        }

        header("Location: product_card.php?id=$prod_id&rec=1");
        exit;
    }
    mysqli_stmt_close($rec_check);
}
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="css/catalog.css">
    <link rel="stylesheet" href="css/product_card.css">

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
            font-weight: 500;
            color: #22223b;
            margin-bottom: 0.2rem;
        }

        .product-zalando-subtitle {
            font-family: 'Rubik', sans-serif;
            font-size: 1.2rem;
            font-weight: bold;
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
            color: #efc91c;
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
    <?php include 'structure/nav.php'; ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="product-zalando-main">
                    <div class="row g-4">
                        <!-- Gallery -->
                        <div class="col-md-1 d-none d-md-flex flex-column align-items-center">
                            <?php foreach ($gallery as $i => $img): ?>
                                <img src="zdjecia/produkty/<?= htmlspecialchars($img) ?>"
                                    class="product-gallery-thumb<?= $i == 0 ? ' active' : '' ?>"
                                    onclick="changeMainImage(this)" alt="miniatura">
                            <?php endforeach; ?>
                        </div>
                        <!-- Main Image -->
                        <div class="col-md-5 d-flex align-items-center justify-content-center">
                            <img src="zdjecia/produkty/<?= htmlspecialchars($gallery[0]) ?>" id="mainProductImage"
                                class="product-gallery-main" alt="Produkt">
                        </div>
                        <!-- Details -->
                        <div class="col-md-6">
                            <div class="product-zalando-title"><?= htmlspecialchars($product['nazwa']) ?></div>
                            <div class="product-zalando-subtitle"><?= htmlspecialchars($product['opis']) ?></div>
                            <div class="product-zalando-rating" id="showReviewForm" style="cursor:pointer;"
                                title="Kliknij, aby dodać recenzję">
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
                            <?php
                            // Dodaj formularz recenzji tylko jeśli użytkownik jest zalogowany i nie dodał jeszcze recenzji
                            $show_review_form = false;
                            if (isset($_SESSION['user']['id'])) {
                                $user_id = (int)$_SESSION['user']['id'];
                                $prod_id = (int)$id;
                                $rec_res = mysqli_query($conn, "SELECT id FROM recenzje WHERE id_uzytkownika=$user_id AND id_produktu=$prod_id");
                                if ($rec_res && mysqli_num_rows($rec_res) == 0) {
                                    $show_review_form = true;
                                }
                            }
                            if ($show_review_form):
                            ?>
                            <form method="post" class="mb-4" id="reviewForm" style="display:none;">
                                <div class="mb-2">
                                    <label class="form-label">Twoja ocena:</label>
                                    <div>
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <input type="radio" class="btn-check" name="ocena" id="star<?= $i ?>" value="<?= $i ?>" <?= $i == 5 ? 'checked' : '' ?>>
                                            <label class="btn btn-sm btn-outline-warning" for="star<?= $i ?>"><i class="bi bi-star-fill"></i></label>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Twoja recenzja:</label>
                                    <textarea name="tresc" class="form-control" rows="3" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-dark">Dodaj recenzję</button>
                            </form>
                            <?php endif; ?>
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
                                            <img src="zdjecia/produkty/<?= htmlspecialchars($product['img_path']) ?>"
                                                class="product-gallery-thumb active" style="border:2px solid #000;"
                                                alt="miniatura">
                                            <?php
                                            $has_other = true;
                                        }
                                        ?>
                                        <a href="product_card.php?id=<?= $sim['id'] ?>" style="display:inline-block;">
                                            <img src="zdjecia/produkty/<?= htmlspecialchars($sim['img_path']) ?>"
                                                class="product-gallery-thumb" alt="miniatura">
                                        </a>
                                        <?php
                                    }
                                    if (!$has_other) {
                                        ?>
                                        <img src="zdjecia/produkty/<?= htmlspecialchars($product['img_path']) ?>"
                                            class="product-gallery-thumb active" style="border:2px solid #000;"
                                            alt="miniatura">
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <form method="post" action="/projekt_sklep/shopping_cart/add_to_cart.php" class="mb-2">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="product-zalando-btn-main">
                                    <i class="bi bi-cart-plus"></i> Dodaj do koszyka
                                </button>
                            </form>
                            <?php if (isset($_GET['added']) && $_GET['added'] == 1): ?>
                                <div class="alert alert-success mb-2">Produkt został dodany do koszyka!</div>
                            <?php endif; ?>
                            <?php if (isset($_GET['error']) && $_GET['error'] == 'Produkt_nie_istnieje'): ?>
                                <div class="alert alert-danger mb-2">Nie można dodać produktu do koszyka – produkt nie istnieje.</div>
                            <?php endif; ?>
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
                            <div class="mt-4">
                                <h5 class="mb-3" style="font-weight:700;">Recenzje produktu</h5>
                                <?php
                                // DEBUG: pokaż id produktu z URL i z bazy
                                echo "<!-- id z URL: $id, id z bazy: {$product['id']} -->";
                                // Używaj $id z URL, nie $product['id']
                                $rec_q = mysqli_query($conn, "SELECT r.*, u.imie_nazwisko FROM recenzje r JOIN uzytkownik u ON r.id_uzytkownika=u.id WHERE r.id_produktu=" . (int) $id . " ORDER BY r.data DESC");
                                if (!$rec_q) {
                                    echo "<div class='alert alert-danger'>Błąd SQL: " . htmlspecialchars(mysqli_error($conn)) . "</div>";
                                }
                                echo "<!-- Liczba recenzji: " . mysqli_num_rows($rec_q) . " -->";
                                if (mysqli_num_rows($rec_q) > 0):
                                    while ($rec = mysqli_fetch_assoc($rec_q)):
                                        ?>
                                        <div class="d-flex flex-row align-items-start mb-3" style="gap:12px;">
                                            <div
                                                style="width:44px;height:44px;border-radius:50%;background:#e5e7eb;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:18px;color:#555;">
                                                <?= mb_substr(htmlspecialchars($rec['imie_nazwisko']), 0, 1) ?>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center mb-1">
                                                    <span
                                                        style="font-weight:600;"><?= htmlspecialchars($rec['imie_nazwisko']) ?></span>
                                                    <span class="ms-3 text-warning" style="font-size:15px;">
                                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                                            <i
                                                                class="bi <?= $i <= $rec['ocena'] ? 'bi-star-fill' : 'bi-star' ?>"></i>
                                                        <?php endfor; ?>
                                                    </span>
                                                    <span class="ms-auto text-muted"
                                                        style="font-size:13px;"><?= htmlspecialchars($rec['data']) ?></span>
                                                </div>
                                                <div
                                                    style="background:#f8f9fa;border-radius:8px;padding:10px 14px;font-size:1.05rem;">
                                                    <?= nl2br(htmlspecialchars($rec['tresc'])) ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; else: ?>
                                    <div class="text-muted">Brak recenzji dla tego produktu.</div>
                                <?php endif; ?>
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
                            echo '<img src="zdjecia/produkty/' . htmlspecialchars($sim2['img_path']) . '" style="width:100px;height:100px;object-fit:cover;border-radius:8px;background:#f8f9fa;">';
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

        // formularz na recenzje po kliknieciu w ocene
        document.addEventListener('DOMContentLoaded', function () {
            var rating = document.getElementById('showReviewForm');
            var form = document.getElementById('reviewForm');
            if (rating && form) {
                rating.addEventListener('click', function () {
                    form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
                    if (form.style.display === 'block') {
                        form.scrollIntoView({ behavior: "smooth", block: "center" });
                    }
                });
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-..."
        crossorigin="anonymous"></script>
    <?php include 'structure/footer.php'; ?>
</body>

</html>