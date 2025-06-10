<?php
session_start();
require_once("../misc/database.php");

$cart = [];
$product_data = [];
$total = 0;

if (isset($_SESSION["user"]["id"])) {
    // Zalogowany użytkownik: koszyk z bazy
    $user_id = $_SESSION["user"]["id"];
    $result = mysqli_query($conn, "SELECT * FROM koszyk WHERE id_uzytkownika = $user_id");
    while ($row = mysqli_fetch_assoc($result)) {
        $cart[$row['id_produktu']] = $row['ilosc'];
    }
} else {
    // Niezalogowany użytkownik: koszyk z sesji
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
    }
}

// Pobierz dane produktów
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
<?php include("../structure/cart_structure/header.php"); ?>

    <title>Main - strona</title>
    <style>
        body {
            padding-top: 110px !important; /* lub inna wartość zależnie od wysokości nawigacji */
        }
        .cart-wrapper {
            background-color:rgb(255, 255, 255);
            min-height: 70vh;
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
<?php include("../structure/cart_structure/nav.php"); ?>

    <body>
        <div class="cart-wrapper">
            <div class="container">
                <div class="row g-4">
                    <!-- Cart Items Section -->
                    <div class="col-lg-8">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="mb-0">Twój koszyk</h4>
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
                                                <img src="../zdjecia/produkty/<?= htmlspecialchars($p['img_path']) ?>" alt="Product" class="product-image">
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
                            <form method="post" action="to_checkout.php">
                                <button type="submit" class="btn btn-primary checkout-btn w-100 mb-3"
                                    <?php if (!$cart) echo 'disabled'; ?>>
                                    Zamów
                                </button>
                            </form>
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