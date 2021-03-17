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
    $haslo = $_POST['haslo'];
    $login = $_POST['login'];

    $login = htmlentities($login, ENT_QUOTES, "UTF-8");

    $query_login = oci_parse($conn, sprintf("SELECT * FROM Person WHERE login = '%s'", $login));
    oci_execute($query_login);
    if (oci_fetch_all($query_login, $res, 0, -1, OCI_FETCHSTATEMENT_BY_ROW) == 1) {

        if (password_verify($haslo, $res[0]['PASS'])) {

            $_SESSION['info_logowanie'] = "Zalogowano jako: $login. <a href='wyloguj.php'>Wyloguj się</a>";
            $_SESSION['login'] = $_POST['login'];
            unset($_SESSION['blad_logowanie']);
            header('Location: ' . $_SESSION['back']);
        } else {
            $_SESSION['blad_logowanie'] = "<b>Podano nieprawidłowy login lub hasło</b>";
            header('Location: logowanie.php');
        }
    } else {
        $_SESSION['blad_logowanie'] = "<b>Podano nieprawidłowy login lub hasło</b>";
        header('Location: logowanie.php');
    }


}

?>