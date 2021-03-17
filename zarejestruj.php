<?php
session_start();
if (isset($_SESSION['login']))
    header('Location: index.php');

if (!isset($_SESSION['back']))
    $_SESSION['back'] = "index.php";

require_once "connect.php";

$conn = oci_connect($db_user, $db_password, $host);
if (!$conn) {
    echo "oci_connect failed\n";
    $e = oci_error();
    echo $e['message'];
} else {
    $imie = $_POST['imie'];
    $nazwisko = $_POST['nazwisko'];
    $login = $_POST['login'];
    $haslo1 = $_POST['haslo1'];
    $haslo2 = $_POST['haslo2'];

    $error_imie = false;
    $error_nazwisko = false;
    $error_login = false;
    $error_haslo = false;

    /**/
    $imie = htmlentities($imie, ENT_QUOTES, "UTF-8");
    /**/
    $nazwisko = htmlentities($nazwisko, ENT_QUOTES, "UTF-8");

    if (empty($imie)) {
        $_SESSION['blad_imie'] = "To pole nie może być puste";
        $error_imie = true;
    }
    if (empty($nazwisko)) {
        $_SESSION['blad_imie'] = "To pole nie może być puste";
        $error_nazwisko = true;
    }


    if (empty($login)) {
        $_SESSION['blad_login'] = "To pole nie może być puste";
        $error_login = true;
    } else if (strlen($login) < 5 || strlen($login) > 15) {
        $_SESSION['blad_login'] = "Nazwa użytkownika powinna mieć od 5 do 15 znaków. Spróbuj ponownie.";
        $error_login = true;
    } else if (ctype_alnum($login) == false) {
        $error_login = false;
        $_SESSION['blad_login'] = "Nick może składać się tylko z liter łacińskich i cyfr";
    }

    /**/
    $login = htmlentities($login, ENT_QUOTES, "UTF-8");

    $query_login = oci_parse($conn, sprintf("SELECT * FROM Person WHERE login = '%s'", $login));
    oci_execute($query_login);
    if (oci_fetch_all($query_login, $res) != 0) {
        $_SESSION['blad_login'] = "Ta nazwa użytkownika jest już zajęta. Wybierz inną.";
        $error_login = true;
    }

    if (empty($haslo1) or empty($haslo2)) {
        $_SESSION['blad_haslo'] = "To pole nie może być puste";
        $error_haslo = true;
    } else if (strlen($haslo1) < 5 || strlen($haslo1) > 20) {
        $_SESSION['blad_haslo'] = "Hasło musi posiadać od 5 do 20 znaków. to haslo ma: " . $haslo1 . " Spróbuj ponownie";
        $error_haslo = true;
    } else if ($haslo1 != $haslo2) {
        $_SESSION['blad_haslo'] = "Te hasła nie pasują do siebie. Spróbuj ponownie";
        $error_haslo = true;
    }

    if ($error_imie == true or $error_nazwisko == true or $error_login == true or $error_haslo == true) {

        if (!$error_imie) {
            $_SESSION['r_imie'] = $imie;
            unset($_SESSION['blad_imie']);
        } else {
            unset($_SESSION['r_imie']);
        }

        if (!$error_nazwisko) {
            $_SESSION['r_nazwisko'] = $nazwisko;
        } else {
            unset($_SESSION['r_nazwisko']);
        }


        if (!$error_imie && !$error_nazwisko) {
            unset($_SESSION['blad_imie']);
        }

        if (!$error_login) {
            $_SESSION['r_login'] = $login;
            unset($_SESSION['blad_login']);
        } else {
            unset($_SESSION['r_login']);
        }

        if (!$error_haslo) {
            $_SESSION['r_haslo1'] = $haslo1;
            $_SESSION['r_haslo2'] = $haslo2;
            unset($_SESSION['blad_haslo']);
        } else {
            unset($_SESSION['r_haslo1']);
            unset($_SESSION['r_haslo2']);
        }

        header('Location: rejestracja.php');
    } else {

        $haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);

        $query_login = oci_parse($conn, sprintf("INSERT INTO Person (fst_name, lst_name, login, pass)
                                                     VALUES ('%s', '%s', '%s', '%s')",
            $imie, $nazwisko, $login, $haslo_hash));
        oci_execute($query_login);
        if (oci_num_rows($query_login) == 1) {
            $_SESSION['info_logowanie'] = "Zalogowano jako: $login. <a href='wyloguj.php'>Wyloguj się</a>";
            $_SESSION['login'] = $login;
            unset($_SESSION['blad_logowanie']);
        }
        header('Location: ' . $_SESSION['back']);

    }

}

?>