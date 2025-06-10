<?php session_start(); ?>

<!DOCTYPE html>
<html lang="pl">

<head>
  <?php include("structure/header.php"); ?>
  <title>O firmie</title>
</head>
<?php include("structure/nav.php"); ?>
        <main class="flex-shrink-0">
            <header class="py-5">
                <div class="container px-5">
                    <div class="row justify-content-center">
                        <div class="col-lg-8 col-xxl-6">
                            <div class="text-center my-5">
                                <h1 class="fw-bolder mb-3 pt-5">Nasza misja to uczynić kasyna bardziej eksluzywne</h1>
                                <p class="lead fw-normal text-muted mb-4">Skibidi Firma to dynamicznie rozwijające się przedsiębiorstwo, które powstało z pasji, kreatywności i chęci dostarczania innowacyjnych rozwiązań. Od początku naszej działalności stawiamy na wysoką jakość usług, profesjonalizm i partnerskie relacje z klientami.</p>
                                <a class="btn btn-dark btn-lg" href="#scroll-target">Poznaj naszą historię</a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <section class="py-5 bg-light" id="scroll-target">
                <div class="container px-5 my-5">
                    <div class="row gx-5 align-items-center">
                        <div class="col-lg-6"><img class="img-fluid rounded mb-5 mb-lg-0" src="zdjecia/index/team_4.jpg"/></div>
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
                        <div class="col-lg-6 order-first order-lg-last"><img class="img-fluid rounded mb-5 mb-lg-0" src="zdjecia/index/team_1.jpg"/></div>
                        <div class="col-lg-6">
                            <h2 class="fw-bolder">Zespół to podstawa</h2>
                            <p class="lead fw-normal text-muted mb-0">W Skibidi Firma wierzymy, że najlepsze rzeczy powstają wtedy, gdy łączy się kreatywność z luzem, a technologia z odrobiną dystansu do świata. Nasz zespół to dynamiczny duet: <br><br>Z przodu — skupiony strateg z ręką zawsze na pulsie kodu i innowacji; <br><br>Z tyłu — wizjoner z fryzurą godną frontmana rockowego zespołu i umysłem pełnym nieszablonowych pomysłów. <br><br><br>Poznaliśmy się przypadkiem, ale pracujemy razem celowo.</p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="py-5 bg-light">
                <div class="container px-5 my-5">
                    <div class="text-center">
                        <h2 class="fw-bolder">Drużyna Skibidi Firmy</h2>
                        <p class="lead fw-normal text-muted mb-5">Poznaj nasz fascynujący zespół</p>
                    </div>
                    <div class="row g-4 justify-content-center">
                        <div class="col-md-5">
                            <div class="card h-100 shadow-sm border-0">
                                <img src="zdjecia/index/team_2.jpg" class="card-img-top" alt="Szymon Garncarz" style="object-fit:cover;height:520px;">
                                <div class="card-body text-center">
                                    <h5 class="fw-bold mb-1">inż. dr hab. Szymon Garncarz</h5>
                                    <p class="text-muted mb-2">Front-end & Back-end Developer. Odpowiedzialny za architekturę aplikacji, kod PHP i integracje.</p>
                                </div>
                                <div class="card-footer bg-white border-0 text-center">
                                    <span><i class="fab fa-html5 text-warning"></i> <i class="fab fa-css3-alt text-primary"></i> <i class="fas fa-hippo text-primary-emphasis"></i> </i> Front-end & Back-end Developer</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="card h-100 shadow-sm border-0">
                                <img src="zdjecia/index/team_5.jpg" class="card-img-top" alt="Szymon Małczok" style="object-fit:cover;height:520px;">
                                <div class="card-body text-center">
                                    <h5 class="fw-bold mb-1">inż. prof. Szymon Małczok</h5>
                                    <p class="text-muted mb-2">SQL Developer, Database Enginner. Odpowiedzialny za architekturę bazy danych oraz przetwarzanie danych</p>
                                </div>
                                <div class="card-footer bg-white border-0 text-center">
                                    <span><i class="fas fa-database text-primary-emphasis"></i> <i class="fas fa-project-diagram text-warning"></i> SQL developer</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <?php include("structure/footer.php"); ?>
    </body>
</html>
