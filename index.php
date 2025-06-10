<?php
session_start();
require_once("misc/database.php");

$carouselImages = [];
$result = mysqli_query($conn, "SELECT img_path, caption_title, caption_text FROM karuzela ORDER BY id DESC");
if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
    $carouselImages[] = $row;
  }
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
  <?php include 'structure/header.php'; ?>
  <title>Main - strona</title>
</head>

<body>
  <?php include 'structure/nav.php'; ?>

  <!--Karuzela możliwość dodawania do galerii zdjęć za pomocą panelu admina w zakładce Karuzela-->
  <main>
    <div id="mainpage-carousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="2500">
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
                </div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="carousel-item active c-item">
            <img class="d-block w-100 c-img img-fluid" src="zdjecia/blackjack_table.png" alt="First slide">
          </div>
        <?php endif; ?>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#mainpage-carousel" data-bs-slide="prev">
        <span aria-hidden="true" class="carousel-control-prev-icon"></span>
        <span class="visually-hidden">Poprzedni</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#mainpage-carousel" data-bs-slide="next">
        <span aria-hidden="true" class="carousel-control-next-icon"></span>
        <span class="visually-hidden">Następny</span>
      </button>
    </div>
  </main>






  <!--Sekcja informacji o firmie, możliwość dodawania dodatkowych kart za pomocą panelu admina w zakładce Informacje -->
  <section>
    <div class="container p-5">
      <div class="row g-5 mt-2">
        <div class="col-12 col-md-6 col-lg-4">
          <div class="card border-dark border-top-0">
            <img src="zdjecia/index/storage.png" width="150px" class="card-img-top">
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
            <img src="zdjecia/index/delivery.png" width="150px" class="card-img-top">
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
            <img src="zdjecia/index/review.png" width="150px" class="card-img-top">
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
      <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php
        // Pobierz 3 losowe produkty z katalogu
        $prod_res = mysqli_query($conn, "SELECT id, nazwa, opis, cena, znizka, ocena, img_path FROM produkty ORDER BY RAND() LIMIT 3");
        while ($prod = mysqli_fetch_assoc($prod_res)):
        ?>
        <div class="col d-flex">
          <div class="product-card shadow-sm h-100 d-flex flex-column">
            <div class="position-relative">
              <a href="product_card.php?id=<?= $prod['id'] ?>">
                <img src="zdjecia/produkty/<?= htmlspecialchars($prod['img_path']) ?>" class="product-image w-100" alt="<?= htmlspecialchars($prod['nazwa']) ?>">
              </a>
              <?php if ($prod['znizka'] > 0): ?>
                <span class="discount-badge bg-danger text-white position-absolute top-0 start-0 m-2 px-2 py-1 rounded">
                  -<?= (int)$prod['znizka'] ?>%
                </span>
              <?php endif; ?>
            </div>
            <div class="p-3 flex-grow-1 d-flex flex-column">
              <h5 class="mb-1 header"><?= htmlspecialchars($prod['nazwa']) ?></h5>
              <h6 class="mb-1 header-child"><?= htmlspecialchars($prod['opis']) ?></h6>
              <div class="rating-stars mb-2">
                <?php
                $ocena = (float)$prod['ocena'];
                for ($i = 1; $i <= 5; $i++) {
                  if ($ocena >= $i) echo '<i class="bi bi-star-fill"></i>';
                  elseif ($ocena >= $i - 0.5) echo '<i class="bi bi-star-half"></i>';
                  else echo '<i class="bi bi-star"></i>';
                }
                ?>
                <span class="text-muted ms-2">(<?= number_format($ocena, 1) ?>)</span>
              </div>
              <div class="mt-auto">
                <div class="d-flex justify-content-between align-items-center">
                  <span class="price<?= $prod['znizka'] > 0 ? ' text-danger fw-bold' : '' ?>">
                      <?= number_format($prod['cena'] * (1 - $prod['znizka']/100), 2) ?> zł
                  </span>
                </div>
                <small class="text-secondary">Cena regularna: <?= number_format($prod['cena'], 2) ?> zł</small>
              </div>
            </div>
          </div>
        </div>
        <?php endwhile; ?>
      </div>
    </div>
  </article>
  <?php include 'structure/footer.php'; ?>
</body>

</html>