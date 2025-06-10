<?php
session_start();
if (isset($_SESSION["user"])) {
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("../structure/cart_structure/header.php"); ?>
    <title>login - strona</title>
</head>

<body>
    <?php include("../structure/cart_structure/nav.php"); ?>
    <main>
        <!--środek sekcji logowania (tło)-->
        <div class="container d-flex justify-content-center align-items-center min-vh-100">
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
                            <h2>Witamy w panelu rejestracji</h2>
                            <p>Cieszymy się, że będziemy mogli Cię poznać!</p>
                        </div>

                        <?php
                        // sprawdzanie czy formularz zostal caly wypelniony i wyslany
                        if (isset($_POST["submit"])) {
                            $fullName = $_POST["fullname"];
                            $email = $_POST["email"];
                            $password = $_POST["password"];
                            $repeatPassword = $_POST["repeat_password"];
                            
                            // szyfrowanie hasla 
                            $passwordHash = password_hash($password, PASSWORD_BCRYPT);

                            // tablica przechowuje bledy jesli jakies wystapia
                            $errors = array();
                            if (empty($fullName) or empty($email) or empty($password) or empty($repeatPassword)) {
                                array_push($errors, "Wszystkie pola muszą być wypełnione.");
                            }
                            // FILTER_VALIDATE_EMAIL sprawdza czy jest @ w mailu jednoczesnie sprawdza to formularz ale dodalem bo tak pisal chlop zeby lepiej miec XD
                            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                array_push($errors, "Niepoprawny email.");
                            }
                            if (strlen($password) < 8) {
                                array_push($errors, "Hasło musi mieć przynajmniej 8 znaków.");
                            }
                            if ($password !== $repeatPassword) {
                                array_push($errors, "Hasła nie są takie same.");
                            }

                            require_once("../misc/database.php");

                            // Sprawdzanie czy email jest w bazie
                            $sql = "SELECT * FROM uzytkownik WHERE email = ?";
                            $stmt = mysqli_stmt_init($conn);

                            // Przygotowanie zapytania jak coś to te bind_param, execute itd. sa zabezpieczeniem przed SQL Injection
                            // mysqli_stmt_prepare sprawdza czy zapytanie jest poprawne
                            // mysqli_stmt_bind_param wiąże zmienną $email z zapytaniem SQL
                            // mysqli_stmt_execute wykonuje zapytanie
                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                                array_push($errors, "Błąd przygotowania zapytania SQL.");
                            } else {
                                mysqli_stmt_bind_param($stmt, "s", $email);
                                mysqli_stmt_execute($stmt);
                                mysqli_stmt_store_result($stmt);
                                $rowCount = mysqli_stmt_num_rows($stmt);

                                if ($rowCount > 0) {
                                    array_push($errors, "Ktoś z takim e-mailem już posiada konto");
                                }
                            }

                            // jak jakis blad wyskoczy no to cie powiadomi
                            if (count($errors) > 0) {
                                foreach ($errors as $error) {
                                    echo "<div class='alert alert-danger mb-2 p-3'>$error</div>";
                                }
                            } else {
                                // poprawny insert do bazy zebys nie walnal czegos jakbys moze zmienial cos (to 1 to uprawnienia_id, uzytkownik ma 1 a admin 2)
                                $sql = "INSERT INTO uzytkownik (imie_nazwisko, email, haslo, uprawnienia_id) VALUES (?, ?, ?, 1)";
                                $stmt = mysqli_stmt_init($conn);

                                if (!mysqli_stmt_prepare($stmt, $sql)) {
                                    die("Błąd przygotowania zapytania: " . mysqli_error($conn));
                                } else {
                                    if ($passwordHash === false) {
                                        die("Błąd podczas hashowania hasła");
                                    }

                                    // kolejnosc parametrow (s - string i - int)
                                    mysqli_stmt_bind_param($stmt, "sss", $fullName, $email, $passwordHash);

                                    if (mysqli_stmt_execute($stmt)) {
                                        echo "<div class='alert alert-success'>Rejestracja zakończona sukcesem!</div>";
                                    } else {
                                        die("Błąd wykonania zapytania: " . mysqli_stmt_error($stmt));
                                    }
                                }
                            }
                        }
                        ?>
                        <form action="reg.php" method="post">

                            <div class="input-group mb-3 mt-3">
                                <input type="text" class="form-control form-control-lg bg-light fs-6" name="fullname"
                                    placeholder="Imię i Nazwisko">
                            </div>


                            <div class="input-group mb-3">
                                <input type="email" class="form-control form-control-lg bg-light fs-6" name="email"
                                    placeholder="Adres e-mail">
                            </div>


                            <div class="input-group mb-3">
                                <input type="password" class="form-control form-control-lg bg-light fs-6"
                                    name="password" placeholder="Hasło">
                            </div>


                            <div class="input-group mb-3">
                                <input type="password" class="form-control form-control-lg bg-light fs-6"
                                    name="repeat_password" placeholder="Potwierdź hasło">
                            </div>


                            <div class="input-group mb-3">
                                <input type="submit" class="button-1 w-100 fs-6 lh-sm" value="Zarejestruj się"
                                    name="submit">
                            </div>

                            <div class="row">
                                <small>Źle trafiłeś? Posiadasz już konto? <a href="../logowanie/log.php">Zaloguj
                                        się</a></small>
                            </div>

                        </form>



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