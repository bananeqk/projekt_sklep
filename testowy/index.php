<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&family=Syne:wght@400..800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="styles.css" />
    <title>Bootstap 5 Responsive Admin Dashboard</title>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="bg-white" id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold text-uppercase border-bottom">
                <img src="../zdjecia/sklep_logo.png" width="150px"><br>Skibidi Firma
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
                        class="list-group-item list-group-item-action bg-transparent second-text fw-bold">Użytkownicy</a>
                </div>
                <!-- Pozostałe zakładki -->
                <a class="list-group-item list-group-item-action bg-transparent second-text fw-bold d-flex justify-content-between align-items-center"
                    data-bs-toggle="collapse" href="#projectsCollapse" role="button" aria-expanded="false"
                    aria-controls="projectsCollapse">
                    <span><i class="fas fa-project-diagram me-2"></i>Projekty</span>
                    <i class="fas fa-chevron-down"></i>
                </a>
                <a class="list-group-item list-group-item-action bg-transparent second-text fw-bold d-flex justify-content-between align-items-center"
                    data-bs-toggle="collapse" href="#productsCollapse" role="button" aria-expanded="false"
                    aria-controls="productsCollapse">
                    <span><i class="fas fa-gift me-2"></i>Produkty</span>
                    <i class="fas fa-chevron-down"></i>
                </a>
                <div class="collapse ps-3" id="productsCollapse">
                    <a href="#" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">Lista
                        produktów</a>
                    <a href="#" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">Dodaj
                        produkt</a>
                    <a href="#"
                        class="list-group-item list-group-item-action bg-transparent second-text fw-bold">Kategorie</a>
                </div>
                <a href="#" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                    <i class="fas fa-chart-line me-2"></i>Analytics
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                    <i class="fas fa-paperclip me-2"></i>Reports
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-transparent text-danger fw-bold">
                    <i class="fas fa-power-off me-2"></i>Logout
                </a>
            </div>
        </div>


        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-transparent py-4 px-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-align-left primary-text fs-4 me-3" id="menu-toggle"></i>
                    <h2 class="fs-2 m-0">Użytkownicy</h2>
                </div>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle second-text fw-bold" href="#" id="navbarDropdown"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-2"></i>John Doe
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#">Profil</a></li>
                                <li><a class="dropdown-item" href="#">Ustawienia</a></li>
                                <li><a class="dropdown-item" href="#">Wyloguj się</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <?php
            require_once("../misc/database.php");
            $sql = "SELECT uzytkownik.id AS user_id, uzytkownik.imie_nazwisko, uzytkownik.email, uzytkownik.uprawnienia_id, uprawnienia.uprawnienia 
                FROM uzytkownik 
                JOIN uprawnienia ON uprawnienia.id = uzytkownik.uprawnienia_id";
            $result = mysqli_query($conn, $sql);
            if (!$result) {
                die("Błąd zapytania: " . mysqli_error($conn));
            }
            ?>
            <?php if (isset($_GET['deleted'])): ?>
                <div class="alert alert-success">Użytkownik został usunięty.</div>
            <?php endif; ?>

            <?php if (isset($_GET['updated'])): ?>
                <div class="alert alert-success">Uprawnienia zostały zaktualizowane.</div>
            <?php endif;
            ?>
            <div class="container-fluid px-4">
                <div class="card shadow-sm border-0 rounded-4 mt-4">
                    <div class="card-body">
                        <h4 class="mb-4 fw-bold text-secondary">Lista użytkowników</h4>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle user-table">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Imię i Nazwisko</th>
                                        <th scope="col">E-mail</th>
                                        <th scope="col">Uprawnienia</th>
                                        <th scope="col" class="text-end">Akcje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($user = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <th scope="row"><?= htmlspecialchars($user['user_id']) ?></th>
                                            <td><?= htmlspecialchars($user['imie_nazwisko']) ?></td>
                                            <td><?= htmlspecialchars($user['email']) ?></td>
                                            <td><?= htmlspecialchars($user['uprawnienia']) ?>
                                                <form method="post" action="update_user.php">
                                                    <input type="hidden" name="user_id" value="<?= $user["user_id"] ?>">
                                                    <select name="uprawnienia_id" class="form-select-sm">
                                                        <option value="1" <?= $user["uprawnienia_id"] == 1 ? 'selected' : '' ?>>Użytkownik
                                                        </option>
                                                        <option value="2" <?= $user["uprawnienia_id"] == 2 ? 'selected' : '' ?>>
                                                            Administrator</option>
                                                    </select>
                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-save"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="text-end">
                                                <a href="delete_user.php?id=<?= $user["user_id"] ?>"
                                                    class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash-alt"></i> (ID: <?= $user["user_id"] ?>)</a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Tabela użytkowników -->
            <!-- /#page-content-wrapper -->
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            var el = document.getElementById("wrapper");
            var toggleButton = document.getElementById("menu-toggle");

            toggleButton.onclick = function () {
                el.classList.toggle("toggled");
            };
        </script>
</body>

</html>