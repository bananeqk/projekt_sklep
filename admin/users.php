<?php
session_start();
include '../adminDashboard/head.php';
include '../adminDashboard/sidebar.php';
require_once("../misc/database.php");

// Pobierz listę uprawnień
$permissions = [];
$perm_result = mysqli_query($conn, "SELECT id, uprawnienia FROM uprawnienia");
while ($perm = mysqli_fetch_assoc($perm_result)) {
    $permissions[$perm['id']] = $perm['uprawnienia'];
}

// Pobierz użytkowników
$users_result = mysqli_query($conn, "SELECT u.id, u.imie_nazwisko, u.email, u.uprawnienia_id FROM uzytkownik u");
?>
<div id="page-content-wrapper">
    <nav class="navbar navbar-expand-lg navbar-light bg-transparent py-4 px-4">
        <div class="d-flex align-items-center">
            <h2 class="fs-2 m-0">Użytkownicy</h2>
        </div>
    </nav>
    <div class="container-fluid px-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm border-0 rounded-4 mt-4">
                    <div class="card-body">
                        <h5 class="fw-bold text-secondary mb-3">Lista użytkowników</h5>
                        <?php if (isset($_GET['updated'])): ?>
                            <div class="alert alert-success">Uprawnienia zostały zaktualizowane.</div>
                        <?php endif; ?>
                        <?php if (isset($_GET['deleted'])): ?>
                            <div class="alert alert-success">Użytkownik został usunięty.</div>
                        <?php endif; ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle user-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Imię i nazwisko</th>
                                        <th>Email</th>
                                        <th>Uprawnienia</th>
                                        <th>Akcje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($user = mysqli_fetch_assoc($users_result)) { ?>
                                        <tr>
                                            <td><?= htmlspecialchars($user['id']) ?></td>
                                            <td><?= htmlspecialchars($user['imie_nazwisko']) ?></td>
                                            <td><?= htmlspecialchars($user['email']) ?></td>
                                            <td>
                                                <form action="update_user.php" method="post" class="d-flex align-items-center gap-2 mb-0">
                                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                    <select name="uprawnienia_id" class="form-select form-select-sm" style="min-width:120px;">
                                                        <?php foreach ($permissions as $pid => $pname): ?>
                                                            <option value="<?= $pid ?>" <?= $user['uprawnienia_id'] == $pid ? 'selected' : '' ?>>
                                                                <?= htmlspecialchars($pname) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <button type="submit" class="btn btn-warning btn-sm">Zastosuj</button>
                                                </form>
                                            </td>
                                            <td>
                                                <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Na pewno usunąć użytkownika?');">
                                                    <i class="fas fa-trash-alt"></i> Usuń
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- Możesz dodać paginację jeśli użytkowników będzie dużo -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>