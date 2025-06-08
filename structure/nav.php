  <nav class="navbar navbar-expand-lg fixed-top bg-light">
    <div class="container-fluid">
      <a class="navbar-brand me-auto m-2" href="#">
        <img src="zdjecia/sklep_logo.png" width="70px">
      </a>
      <div class="offcanvas offcanvas-end" tabindex="2" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
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
          <a href="user/user_profile.php" class="button-1"><i class="bi bi-person-fill"></i></a>
        <?php endif; ?>

        <?php if ($_SESSION["user"]["uprawnienia_id"] == 2): ?>
          <a href="admin/profile.php" class="btn btn-danger"><i class="bi bi-person-fill"></i></a>
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