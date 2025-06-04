<?php session_start(); ?>

<!DOCTYPE html>
<html lang="pl">

<head>
  <meta charset="UTF-8">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="css/style.css" rel="stylesheet">

  <link rel="preconnect" href="https://fonts.googleapis.com">

  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <link
    href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&family=Syne:wght@400..800&display=swap"
    rel="stylesheet">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

  <title>O firmie</title>
</head>
    <body class="d-flex flex-column">
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
        <main class="flex-shrink-0">
            <header class="py-5">
                <div class="container px-5">
                    <div class="row justify-content-center">
                        <div class="col-lg-8 col-xxl-6">
                            <div class="text-center my-5">
                                <h1 class="fw-bolder mb-3 pt-5">Nasza misja to uczynić kasyna bardziej eksluzywne</h1>
                                <p class="lead fw-normal text-muted mb-4">Skibidi Firma to dynamicznie rozwijające się przedsiębiorstwo, które powstało z pasji, kreatywności i chęci dostarczania innowacyjnych rozwiązań. Od początku naszej działalności stawiamy na wysoką jakość usług, profesjonalizm i partnerskie relacje z klientami.</p>
                                <a class="btn btn-primary btn-lg" href="#scroll-target">Poznaj naszą historię</a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <section class="py-5 bg-light" id="scroll-target">
                <div class="container px-5 my-5">
                    <div class="row gx-5 align-items-center">
                        <div class="col-lg-6"><img class="img-fluid rounded mb-5 mb-lg-0" src="" alt="..." /></div>
                        <div class="col-lg-6">
                            <h2 class="fw-bolder">Nasza drużyna</h2>
                            <p class="lead fw-normal text-muted mb-0">Nie jesteśmy zwykłym zespołem — jesteśmy drużyną, która działa jak dobrze naoliwiona maszyna. Każdy z nas wnosi coś unikalnego: wiedzę, kreatywność, zaangażowanie. Razem tworzymy środowisko, w którym pomysły zamieniają się w realne efekty, a wyzwania stają się szansą na rozwój.</p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="py-5">
                <div class="container px-5 my-5">
                    <div class="row gx-5 align-items-center">
                        <div class="col-lg-6 order-first order-lg-last"><img class="img-fluid rounded mb-5 mb-lg-0" src="" alt="..." /></div>
                        <div class="col-lg-6">
                            <h2 class="fw-bolder"></h2>
                            <p class="lead fw-normal text-muted mb-0"></p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="py-5 bg-light">
                <div class="container px-5 my-5">
                    <div class="text-center">
                        <h2 class="fw-bolder">Drużyna</h2>
                        <p class="lead fw-normal text-muted mb-5"></p>
                    </div>
                </div>
            </section>
        </main>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
