<?php
session_start();
require_once("../misc/database.php");

$cart = $_SESSION["cart"] ?? [];
$product_data = [];
$total = 0;
$total_items = array_sum($cart);

if ($cart) {
    $ids = implode(",", array_map("intval", array_keys($cart)));
    $result = mysqli_query($conn, "SELECT * FROM produkty WHERE id IN ($ids)");
    while ($row = mysqli_fetch_assoc($result)) {
        $product_data[$row['id']] = $row;
    }
}
?>

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
    <style>
        .cart-wrapper {
            background-color:rgb(255, 255, 255);
            min-height: 100vh;
            padding: 40px 0;
        }

        .product-card {
            background: white;
            border-radius: 12px;
            transition: transform 0.2s;
        }

        .product-card:hover {
            transform: translateY(-2px);
        }

        .quantity-input {
            width: 60px;
            text-align: center;
            border: 1px solid #dee2e6;
            border-radius: 6px;
        }

        .product-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }

        .summary-card {
            background: white;
            border-radius: 12px;
            position: sticky;
            top: 20px;
        }

        .checkout-btn {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            border: none;
            transition: transform 0.2s;
        }

        .checkout-btn:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #4f46e5, #4338ca);
        }

        .remove-btn {
            color: #dc2626;
            cursor: pointer;
            transition: all 0.2s;
        }

        .remove-btn:hover {
            color: #991b1b;
        }

        .quantity-btn {
            width: 28px;
            height: 28px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            background: #f3f4f6;
            border: none;
            transition: all 0.2s;
        }

        .quantity-btn:hover {
            background: #e5e7eb;
        }

        .discount-badge {
            background: #dcfce7;
            color: #166534;
            font-size: 0.875rem;
            padding: 4px 8px;
            border-radius: 6px;
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
                            <a href="shopping_cart.php" class="button-1 mx-lg-2 my-2 my-lg-0"><i class="bi bi-cart"></i></a>
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
    </head>

    <body>
        <div class="cart-wrapper">
            <div class="container">
                <div class="row g-4">
                    <!-- Cart Items Section -->
                    <div class="col-lg-8">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="mb-0">Twój koszyk</h4>
                            <span class="text-muted">
                                <?php
                                // Popraw odmianę słowa "produkt" po polsku
                                if ($total_items == 1) {
                                    echo "1 produkt";
                                } elseif ($total_items % 10 >= 2 && $total_items % 10 <= 4 && ($total_items % 100 < 10 || $total_items % 100 >= 20)) {
                                    echo $total_items . " produkty";
                                } else {
                                    echo $total_items . " produktów";
                                }
                                ?>
                            </span>
                        </div>

                        <!-- Product Cards -->
                        <div class="d-flex flex-column gap-3">
                            <?php if (!$cart): ?>
                                <div class="alert alert-info">Koszyk jest pusty.</div>
                            <?php else: ?>
                                <?php foreach ($cart as $pid => $qty): ?>
                                    <?php if (!isset($product_data[$pid])) continue; $p = $product_data[$pid]; ?>
                                    <div class="product-card p-3 shadow-sm">
                                        <div class="row align-items-center">
                                            <div class="col-md-2">
                                                <img src="../produkty/<?= htmlspecialchars($p['img_path']) ?>" alt="Product" class="product-image">
                                            </div>
                                            <div class="col-md-4">
                                                <h6 class="mb-1"><?= htmlspecialchars($p['nazwa']) ?></h6>
                                                <p class="text-muted mb-0"><?= htmlspecialchars($p['opis']) ?></p>
                                            </div>
                                            <div class="col-md-3">
                                                <form method="post" action="update_cart.php" class="d-flex align-items-center gap-2">
                                                    <input type="hidden" name="product_id" value="<?= $pid ?>">
                                                    <input type="number" class="quantity-input" name="quantity" value="<?= $qty ?>" min="1" readonly>
                                                    <button class="quantity-btn" name="action" value="decrease" type="submit">-</button>
                                                    <button class="quantity-btn" name="action" value="increase" type="submit">+</button>
                                                </form>
                                            </div>
                                            <div class="col-md-2">
                                                <?php
                                                $price = $p['cena'] * (1 - $p['znizka']/100);
                                                $total += $price * $qty;
                                                ?>
                                                <span class="fw-bold"><?= number_format($price * $qty, 2) ?> zł</span>
                                            </div>
                                            <div class="col-md-1">
                                                <form method="post" action="update_cart.php" style="display:inline;">
                                                    <input type="hidden" name="product_id" value="<?= $pid ?>">
                                                    <button class="btn btn-link p-0 remove-btn" name="action" value="remove" title="Usuń">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Summary Section -->
                    <div class="col-lg-4">
                        <div class="summary-card p-4 shadow-sm">
                            <h5 class="mb-4">Podsumowanie</h5>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Suma</span>
                                <span><?= $total ?> zł</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-4">
                                <span class="fw-bold">Do zapłaty</span>
                                <span class="fw-bold"><?= $total ?> zł</span>
                            </div>
                            <button class="btn btn-primary checkout-btn w-100 mb-3"
                                <?php if (!$cart || !isset($_SESSION["user"])) ?>
                                onclick="window.location.href='order_checkout.php'; return false;">
                                Zamów
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function updateQuantity(productId, change) {
                const input = event.target.parentElement.querySelector('.quantity-input');
                let value = parseInt(input.value) + change;
                if (value >= 1) {
                    input.value = value;
                }
            }
        </script>
    </body>

</html>