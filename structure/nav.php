<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once('misc/database.php'); // poprawiona ścieżka

$cart_count = 0;
if (isset($_SESSION["user"])) {
    $user_id = $_SESSION["user"]["id"];
    $sql = "SELECT SUM(ilosc) AS total FROM koszyk WHERE id_uzytkownika = $user_id";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $cart_count = $row['total'] ?? 0;
    }
} elseif (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $cart_count = array_sum($_SESSION['cart']);
}
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
  crossorigin="anonymous"></script>
<nav class="navbar navbar-expand-lg fixed-top bg-light">
  <div class="container-fluid">
    <a class="navbar-brand me-auto m-2" href="index.php">
      <img src="zdjecia/logo/sklep_logo.png" width="70px">
    </a>
    <div class="offcanvas offcanvas-end" tabindex="2" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">
          <img src="zdjecia/logo/sklep_logo.png" width="70px">
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-center flex-grow-1 pe-3 align-items-center">
          <li class="nav-item">
            <a class="nav-link mx-lg-2" aria-current="page" href="index.php">Strona główna</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mx-lg-2" href="catalog.php">Katalog</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mx-lg-2" href="about.php">O firmie</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mx-lg-2" href="contact.php">Kontakt</a>
          </li>
          <li class="nav-item">
            <a href="shopping_cart/shopping_cart.php" class="button-1 mx-lg-2 my-2 my-lg-0 position-relative">
              <i class="bi bi-cart"></i>
              <?php if ($cart_count > 0): ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                  style="font-size:0.7em;">
                  <?= $cart_count ?>
                </span>
              <?php endif; ?>
            </a>
          </li>

        </ul>
      </div>
    </div>

    <?php if (isset($_SESSION["user"])): ?>
      <div class="dropdown d-inline-block">
        <a href="#" class="button-1 dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-person-fill"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
          <?php if ($_SESSION["user"]["uprawnienia_id"] == 1): ?>
            <li><a class="dropdown-item" href="user/user_profile.php"><i class="bi bi-person"></i> Panel użytkownika</a></li>
          <?php elseif ($_SESSION["user"]["uprawnienia_id"] == 2): ?>
            <li><a class="dropdown-item" href="admin/profile.php"><i class="bi bi-person"></i> Panel administratora</a></li>
          <?php endif; ?>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item text-danger" href="logowanie/logout.php"><i class="bi bi-box-arrow-right"></i> Wyloguj się</a></li>
        </ul>
      </div>
    <?php else: ?>
      <a href="logowanie/log.php" class="button-1">Zaloguj się</a>
    <?php endif; ?>

    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
      aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  </div>
</nav>