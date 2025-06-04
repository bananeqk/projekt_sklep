<div class="bg-light" id="sidebar-wrapper">
    <div class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold text-uppercase border-bottom switch logo">
        <img src="../zdjecia/sklep_logo.png" width="150px" id="logo-img"><br>Skibidi Firma
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
            <a href="#"
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
            <a href="#"
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
            <a href="#" class="list-group-item list-group-item-action bg-transparent second-text fw-bold switch">Lista
                produktów</a>
            <a href="#" class="list-group-item list-group-item-action bg-transparent second-text fw-bold switch">Dodaj
                produkt</a>
            <a href="#"
                class="list-group-item list-group-item-action bg-transparent second-text fw-bold switch">Kategorie</a>
        </div>
        <a href="#" class="list-group-item list-group-item-action bg-transparent second-text fw-bold switch">
            <i class="fas fa-chart-line me-2"></i>Analytics
        </a>
        <a href="#" class="list-group-item list-group-item-action bg-transparent second-text fw-bold switch">
            <i class="fas fa-paperclip me-2"></i>Reports
        </a>
        <a href="#" class="list-group-item list-group-item-action bg-transparent text-danger fw-bold switch">
            <i class="fas fa-power-off me-2"></i>Logout
        </a>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('toggle-darkmode');
    const body = document.body;
    const logo = document.getElementById('logo-img');

    function updateLogo() {
        if (!logo) return;
        if (body.classList.contains('darkmode')) {
            logo.src = '../zdjecia/sklep_logo_darkmode.png';
        } else {
            logo.src = '../zdjecia/sklep_logo.png';
        }
    }

    // Przywróć tryb z localStorage
    if (localStorage.getItem('darkmode') === 'true') {
        body.classList.add('darkmode');
    }
    updateLogo();

    if (btn) {
        btn.addEventListener('click', function () {
            body.classList.toggle('darkmode');
            localStorage.setItem('darkmode', body.classList.contains('darkmode'));
            setTimeout(updateLogo, 10);
        });
    }
});
</script>