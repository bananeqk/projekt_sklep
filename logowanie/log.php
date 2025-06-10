<?php
session_start();
if (isset($_SESSION["user"])) {
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <?php include("../structure/cart_structure/header.php"); ?>
    <title>login - strona</title>
</head>

<body>
<?php include("../structure/cart_structure/nav.php"); ?>
    <main>
        <!--środek sekcji logowania (tło)-->
        <div class="container d-flex justify-content-center align-items-center min-vh-100">
            <!--form do logowania-->
            <div class="row border rounded-5 p-3 bg-white shadow box-area">

                <!--lewa strona jak obrazek czy inne coś tam damy-->
                <div class="col-md-6 rounded-5 d-flex justify-content-center align-items-center flex-column left-box">
                    <div class="featured-image mb-3">
                        <img src="../zdjecia/logo/skibidi_firma_log.png" alt="" class="img-fluid" width="750px">
                    </div>
                </div>
                <div class="col-md-6 right-box">
                    <div class="row align-items-center">
                        <div class="header-text mb-4">
                            <h2>Witaj w panelu logowania</h2>
                            <p>Cieszmy się, że wciąż do Nas wracasz!</p>
                        </div>

                        <?php
                        if (isset($_POST["submit"])) {
                            $email = $_POST["email"];
                            $password = $_POST["password"];

                            require_once("../misc/database.php");


                            // to co tlumaczylem w reg.php zabezpieczenie przed SQL Injection
                            $stmt = mysqli_prepare($conn, "SELECT * FROM uzytkownik WHERE email = ?");
                            mysqli_stmt_bind_param($stmt, "s", $email);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);

                            // te z $_SESSION["user"] to sa dane ktore beda w sesji przechowywane mozna je wykorzystac pozniej jak w panelu admina czy gdziekolwiek indziej
                            // jak cos to haslo jest w bazie w postaci hasha
                            if ($user = mysqli_fetch_assoc($result)) {
                                if (password_verify($password, $user["haslo"])) {
                                    $_SESSION["user"] = [
                                        "id" => $user["id"],
                                        "email" => $user["email"],
                                        "uprawnienia_id" => $user["uprawnienia_id"],
                                        "imie_nazwisko" => $user['imie_nazwisko'],
                                        "profil_img" => $user['profil_img'] ?? null
                                    ];
                                    header("Location: ../index.php");
                                    exit();
                                } else {
                                    echo "<div class='alert alert-danger'>Niepoprawne hasło</div>";
                                }
                            } else {
                                echo "<div class='alert alert-danger'>Nie znaleziono użytkownika</div>";
                            }
                        }
                        ?>
                        <form action="log.php" method="post">

                            <div class="input-group mb-3">
                                <input type="email" class="form-control form-control-lg bg-light fs-6" name="email"
                                    placeholder="Adres e-mail">
                            </div>

                            <div class="input-group mb-3">
                                <input type="password" class="form-control form-control-lg bg-light fs-6"
                                    name="password" placeholder="Hasło">
                            </div>

                            <div class="input-group mb-3">
                                <input type="submit" class="button-1 w-100 fs-6 lh-sm" value="Zaloguj się"
                                    name="submit">
                            </div>
                            <div class="row">
                                <small>Nie posiadasz konta? <a href="../logowanie/reg.php">Zarejestruj się</a></small>
                            </div>
                    </div>
                </div>

            </div>
        </div>
    </main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous"></script>
</body>

</html>