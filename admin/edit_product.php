<?php
session_start();
require_once("../misc/database.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: products.php");
    exit;
}
$id = (int)$_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM produkty WHERE id = $id");
$product = mysqli_fetch_assoc($result);
if (!$product) {
    echo "Nie znaleziono produktu.";
    exit;
}

// Pobierz kategorie i kolory
$cat_result = mysqli_query($conn, "SELECT id, kategoria FROM kategorie");
$color_result = mysqli_query($conn, "SELECT id, kolor FROM kolory");
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <?php include("../structure/admin/head.php"); ?>
    <title>Edytuj produkt</title>
    <style>
        .edit-product-card {
            max-width: 600px;
            margin: 40px auto;
            border-radius: 18px;
            box-shadow: 0 10px 32px 0 rgba(60, 72, 88, .13);
            background: #fff;
            border: 1px solid #f1f3f6;
        }
        .edit-product-card .form-label {
            font-weight: 500;
        }
        .edit-product-card .card-header {
            background: #212529;
            color: #fff;
            border-radius: 18px 18px 0 0;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .edit-product-card .btn-primary {
            background: #212529;
            border: none;
        }
        .edit-product-card .btn-primary:hover {
            background: #343a40;
        }
        .edit-product-card .btn-secondary {
            background: #f8f9fa;
            color: #212529;
            border: 1px solid #dee2e6;
        }
        .edit-product-card img {
            border-radius: 10px;
            border: 1px solid #eee;
            background: #f8f9fa;
        }
    </style>
</head>
<body>
    <?php include("../structure/admin/sidebar.php"); ?>
    <div class="container">
        <div class="edit-product-card card mt-5">
            <div class="card-header">
                <i class="bi bi-pencil-square me-2"></i>Edytuj produkt
            </div>
            <div class="card-body p-4">
                <form action="update_product.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="edit_product" value="1">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label">Nazwa produktu</label>
                        <input type="text" name="product_name" class="form-control" value="<?= htmlspecialchars($product['nazwa']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Opis</label>
                        <input type="text" name="product_desc" class="form-control" value="<?= htmlspecialchars($product['opis']) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategoria</label>
                        <select name="product_category" class="form-select">
                            <?php while ($cat = mysqli_fetch_assoc($cat_result)): ?>
                                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $product['id_kategorii'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['kategoria']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ilość</label>
                        <input type="number" name="product_amount" class="form-control" value="<?= $product['ilosc'] ?>" min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cena</label>
                        <input type="number" step="0.01" name="product_price" class="form-control" value="<?= $product['cena'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Zniżka (%)</label>
                        <input type="number" step="1" min="0" max="100" name="product_discount" class="form-control" value="<?= $product['znizka'] ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ocena</label>
                        <input type="number" step="0.1" min="0" max="5" name="product_rating" class="form-control" value="<?= $product['ocena'] ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kolor</label>
                        <select name="product_color" class="form-select">
                            <?php
                            mysqli_data_seek($color_result, 0);
                            while ($color = mysqli_fetch_assoc($color_result)): ?>
                                <option value="<?= $color['id'] ?>" <?= $color['id'] == $product['kolor_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($color['kolor']) ?>
                                </option>
                            <?php endwhile; ?>
                            <option value="">Brak</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Zdjęcie (pozostaw puste, aby nie zmieniać)</label>
                        <input type="file" name="product_image" class="form-control">
                        <div class="mt-2 text-center">
                            <img src="../zdjecia/produkty/<?= htmlspecialchars($product['img_path']) ?>" width="120" alt="Aktualne zdjęcie">
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary flex-grow-1"><i class="bi bi-save me-1"></i>Zapisz zmiany</button>
                        <a href="products.php" class="btn btn-secondary flex-grow-1">Anuluj</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include("../structure/admin/script.php"); ?>
</body>
</html>
