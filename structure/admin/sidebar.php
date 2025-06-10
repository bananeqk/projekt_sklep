<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
<div class="d-flex" id="wrapper">

    <div class="bg-light" id="sidebar-wrapper">
        <div
            class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold text-uppercase border-bottom switch logo">
            <img src="../zdjecia/logo/sklep_logo.png" width="150px" id="logo-img"><br>Skibidi Firma
        </div>
        <div class="list-group list-group-flush my-3">
            <!-- Dashboard z rozwijanym menu -->
            <a class="list-group-item list-group-item-action bg-transparent second-text active d-flex justify-content-between align-items-center"
                data-bs-toggle="collapse" href="#dashboardCollapse" role="button" aria-expanded="false"
                aria-controls="dashboardCollapse">
                <span><i class="fas fa-tachometer-alt me-2"></i>Panel Główny</span>
                <i class="fas fa-chevron-down"></i>
            </a>
            <div class="collapse ps-3" id="dashboardCollapse">
                <a href="profile.php"
                    class="list-group-item list-group-item-action bg-transparent second-text fw-bold switch">Profil</a>
            </div>
            <div class="collapse ps-3" id="dashboardCollapse">
                <a href="users.php"
                    class="list-group-item list-group-item-action bg-transparent second-text fw-bold switch">Użytkownicy</a>
            </div>
            <!-- Pozostałe zakładki -->
            <a class="list-group-item list-group-item-action bg-transparent second-text fw-bold d-flex justify-content-between align-items-center switch"
                data-bs-toggle="collapse" href="#projectsCollapse" role="button" aria-expanded="false"
                aria-controls="projectsCollapse">
                <span><i class="fas fa-project-diagram me-2"></i>Projekty</span>
                <i class="fas fa-chevron-down"></i>
            </a>
            <div class="collapse ps-3" id="projectsCollapse">
                <a href="carousel.php"
                    class="list-group-item list-group-item-action bg-transparent second-text fw-bold switch">Karuzela -
                    index.php</a>
            </div>
            <a class="list-group-item list-group-item-action bg-transparent second-text fw-bold d-flex justify-content-between align-items-center switch"
                data-bs-toggle="collapse" href="#productsCollapse" role="button" aria-expanded="false"
                aria-controls="productsCollapse">
                <span><i class="fas fa-gift me-2"></i>Produkty</span>
                <i class="fas fa-chevron-down"></i>
            </a>
            <div class="collapse ps-3" id="productsCollapse">
                <a href="products.php"
                    class="list-group-item list-group-item-action bg-transparent second-text fw-bold switch">Lista
                    produktów</a>
            </div>
            <a href="messages.php"
                class="list-group-item list-group-item-action bg-transparent second-text fw-bold switch">
                <i class="fas fa-paperclip me-2"></i>Wiadomości
            </a>
            <a href="../logowanie/logout.php"
                class="list-group-item list-group-item-action bg-transparent text-danger fw-bold switch">
                <i class="fas fa-power-off me-2"></i>Wyloguj się
            </a>
        </div>
    </div>