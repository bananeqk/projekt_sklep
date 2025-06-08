<?php
session_start();
require_once("misc/database.php");

$carouselImages = [];
$result = $conn->query("SELECT img_path, caption_title, caption_text FROM karuzela ORDER BY id DESC");
if ($result) {
  while ($row = $result->fetch_assoc()) {
    $carouselImages[] = $row;
  }
}
?>

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

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

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
          <a href="uzytkownik_panel.php" class="button-1"><i class="bi bi-person-fill"></i></a>
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







  <!--Karuzela możliwość dodawania do galerii zdjęć za pomocą panelu admina w zakładce Karuzela-->
  <main>
    <div id="mainpage-carousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="2000">
      <div class="carousel-inner">
        <?php if (!empty($carouselImages)): ?>
          <?php foreach ($carouselImages as $i => $img): ?>
            <div class="carousel-item c-item<?php if ($i === 0)
              echo ' active'; ?>">
              <img class="d-block w-100 c-img img-fluid" src="carousel/<?php echo htmlspecialchars($img['img_path']); ?>"
                alt="Slide <?php echo $i + 1; ?>">
              <?php if ($img['caption_title'] || $img['caption_text']): ?>
                <div class="carousel-caption top-50 mt-0 carousel-text-top">
                  <?php if ($img['caption_text']): ?>
                    <p class="m-1"><?php echo htmlspecialchars($img['caption_text']); ?></p>
                  <?php endif; ?>
                  <?php if ($img['caption_title']): ?>
                    <h1 class="fw-bolder carousel-text"><?php echo htmlspecialchars($img['caption_title']); ?></h1>
                  <?php endif; ?>
                  <!-- Możesz dodać przycisk jeśli chcesz -->
                </div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <!-- Fallback: przykładowe zdjęcie jeśli brak w bazie -->
          <div class="carousel-item active c-item">
            <img class="d-block w-100 c-img img-fluid" src="zdjecia/blackjack_table.png" alt="First slide">
          </div>
        <?php endif; ?>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#mainpage-carousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#mainpage-carousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
      </button>
    </div>
  </main>






  <!--Sekcja informacji o firmie, możliwość dodawania dodatkowych kart za pomocą panelu admina w zakładce Informacje -->
  <section>
    <div class="container p-5">
      <div class="row g-5 mt-2">
        <div class="col-12 col-md-6 col-lg-4">
          <div class="card border-dark border-top-0">
            <img src="zdjecia/storage.png" width="150px" class="card-img-top">
            <div class="card-body">
              <h5 class="card-title text-center display-5">Nowoczesny Magazyn</h5>
              <p class="text-center card-text">
                W firmie Skibidi stawiamy na profesjonalizm na każdym etapie działalności, dlatego nasz magazyn został
                wyposażony zgodnie z najwyższymi standardami branżowymi. Posiadamy nowoczesny system składowania, który
                umożliwia szybki dostęp do wszystkich komponentów – od żetonów i kart, po stoły i specjalistyczną
                elektronikę. Dzięki przemyślanej logistyce i zaawansowanemu systemowi zarządzania towarem, realizujemy
                zamówienia hurtowe sprawnie i terminowo. Skibidi to pewność, że każdy element do kasyna dotrze na czas i
                w idealnym stanie.</p>
            </div>
          </div>
        </div>


        <div class="col-12 col-md-6 col-lg-4">
          <div class="card border-dark border-top-0">
            <img src="zdjecia/delivery.png" width="150px" class="card-img-top">
            <div class="card-body">
              <h5 class="card-title text-center display-5">Szybka Dostawa</h5>
              <p class="card-text text-center">
                W Skibidi przykładamy dużą wagę do terminowości i bezpieczeństwa dostaw. Korzystamy z usług sprawdzonych
                przewoźników, dzięki czemu każdy transport – od małych akcesoriów po duże elementy wyposażenia – dociera
                szybko i w nienaruszonym stanie. Realizujemy wysyłki hurtowe na terenie Polski i całej Europy,
                zapewniając elastyczne formy doręczenia i stały kontakt z klientem. Oferujemy możliwość śledzenia
                przesyłek, opcję ekspresowej wysyłki oraz ubezpieczenia towaru. Nasz zespół dba o każdy etap realizacji
                – od kompletacji po doręczenie – aby zapewnić pełne zadowolenie i maksymalne bezpieczeństwo.</p>
            </div>
          </div>
        </div>


        <div class="col-12 col-md-6 col-lg-4">
          <div class="card border-dark border-top-0">
            <img src="zdjecia/review.png" width="150px" class="card-img-top">
            <div class="card-body">
              <h5 class="card-title text-center display-5">4.2⭐ Opinii</h5>
              <p class="card-text text-center">
                Firma Skibidi od lat cieszy się zaufaniem klientów z całej Polski i Europy. Dzięki rzetelności,
                profesjonalnej obsłudze oraz niezawodności w realizacji zamówień, wypracowaliśmy sobie bardzo wysoką
                opinię w branży. Nasze produkty i usługi zyskały uznanie zarówno dużych operatorów kasyn, jak i
                mniejszych firm eventowych, które doceniają naszą elastyczność i indywidualne podejście do każdego
                zamówienia. Pozytywne opinie naszych partnerów są najlepszym dowodem na jakość świadczonych usług.
                Działamy na rynku od wielu lat, a zadowolenie klientów pozostaje naszym priorytetem.</p>
            </div>
          </div>
        </div>

  </section>






  <article>
    <div class="mb-4">
      <h6 class=" text-uppercase"></h6>
      <hr data-content="AND" class="hr-text">
    </div>
    <div class="container mt-5">
      <div class="row py-5">
        <div class="col-lg-8 m-auto text-center">
          <h1>Odkryj nasze produkty</h1>
          <h6 style="color: darksalmon;">oraz zajrzyj do naszego katalogu</h6>
        </div>
      </div>
    </div>
    <div class="container py-5">
      <div class="row">
        <div class="col-md-4">
          <div class="product-card shadow-sm">
            <div class="position-relative">
              <img src="zdjecia/test.avif" class="product-image w-100" alt="Product">
              <span class="discount-badge">-20%</span>
              <button class="wishlist-btn">
                <i class="bi bi-heart"></i>
              </button>
            </div>
            <div class="p-3">
              <h5 class="mb-1 header">The North Face</h5>
              <h6 class="mb-1 header-child">- Wireless Headphones</h6>
              <div class="rating-stars mb-2">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-half"></i>
                <span class="text-muted ms-2">(4.5)</span>
              </div>
              <div class="d-flex justify-content-between align-items-center">
                <span class="price">129.99 zł</span>
              </div>
              <small class="text-secondary">Cena regularna: 129.99 zł</small>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="product-card shadow-sm">
            <div class="position-relative">
              <img src="zdjecia/test.avif" class="product-image w-100" alt="Product">
              <span class="discount-badge">-20%</span>
              <button class="wishlist-btn">
                <i class="bi bi-heart"></i>
              </button>
            </div>
            <div class="p-3">
              <h5 class="mb-1 header">The North Face</h5>
              <h6 class="mb-1 header-child">- Wireless Headphones</h6>
              <div class="rating-stars mb-2">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-half"></i>
                <span class="text-muted ms-2">(4.5)</span>
              </div>
              <div class="d-flex justify-content-between align-items-center">
                <span class="price">129.99 zł</span>
              </div>
              <small class="text-secondary">Cena regularna: 129.99 zł</small>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="product-card shadow-sm">
            <div class="position-relative">
              <img src="zdjecia/test.avif" class="product-image w-100" alt="Product">
              <span class="discount-badge">-20%</span>
              <button class="wishlist-btn">
                <i class="bi bi-heart"></i>
              </button>
            </div>
            <div class="p-3">
              <h5 class="mb-1 header">The North Face</h5>
              <h6 class="mb-1 header-child">- Wireless Headphones</h6>
              <div class="rating-stars mb-2">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-half"></i>
                <span class="text-muted ms-2">(4.5)</span>
              </div>
              <div class="d-flex justify-content-between align-items-center">
                <span class="price">129.99 zł</span>
              </div>
              <small class="text-secondary">Cena regularna: 129.99 zł</small>
            </div>
          </div>
        </div>
      </div>
    </div>


  </article>
  <?php include 'adminDashboard/footer.php'; ?>
</body>

</html>