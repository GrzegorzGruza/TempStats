<?php
session_start();
if (isset($_SESSION['login'])) {
    unset($_SESSION['login']);
    $_SESSION['info_logowanie'] = "<a href='formularz-logowania'>Zaloguj się</a> lub <a href='formularz-rejestracji'>zarejestruj</a>";
    unset($_SESSION['blad_logowanie']);
}
session_destroy();
header('Location: strona-glowna');
?>