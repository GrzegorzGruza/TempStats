<?php
session_start();
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <title>Rejestacja</title>
    <meta name="description" content="Rejestracja"/>
    <meta name="keywords" content=" zarejestruj, rejestracja"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>

    <link rel="stylesheet" href="rejestracja_style.css" type="text/css"/>

</head>

<body>

<div id="container">
    <form action="zarejestruj.php" method="post">

        <input type="text" placeholder="Imię" onfocus="this.placeholder=''" onblur="this.placeholder='Imię'" name="imie"
               class="nazwa" value=
            <?php
            if (isset($_SESSION['r_imie']))
                echo $_SESSION['r_imie'];
            ?>>

        <input type="text" placeholder="Nazwisko" onfocus="this.placeholder=''" onblur="this.placeholder='Nazwisko'"
               name="nazwisko" class="nazwa" value=
            <?php
            if (isset($_SESSION['r_nazwisko']))
                echo $_SESSION['r_nazwisko'];
            ?>>

        <div style="clear: both"></div>

        <div class="blad" id="blad_imie">
            <?php
            if (isset($_SESSION['blad_imie']))
                echo $_SESSION['blad_imie'];
            ?>
        </div>

        <!--------------------------------------------------------------------------------------------->

        <input type="text" placeholder="Wybierz nazwę użytkownika" onfocus="this.placeholder=''"
               onblur="this.placeholder='Wybierz nazwę użytkownika'"
               name="login" value=
            <?php
            if (isset($_SESSION['r_login']))
                echo $_SESSION['r_login'];
            ?>>

        <div class="blad" id="blad_login">
            <?php
            if (isset($_SESSION['blad_login']))
                echo $_SESSION['blad_login'];
            ?>
        </div>

        <!--------------------------------------------------------------------------------------------->

        <input type="password" placeholder="Utwórz hasło" onfocus="this.placeholder=''"
               onblur="this.placeholder='Utwórz hasło'" name="haslo1" value=
            <?php
            if (isset($_SESSION['r_haslo1']))
                echo $_SESSION['r_haslo1'];
            ?>>

        <input type="password" placeholder="Potwierdź hasło" onfocus="this.placeholder=''"
               onblur="this.placeholder='Potwierdź hasło'" name="haslo2" value=
            <?php
            if (isset($_SESSION['r_haslo2']))
                echo $_SESSION['r_haslo2'];
            ?>>

        <div class="blad" id="blad_haslo">
            <?php
            if (isset($_SESSION['blad_haslo']))
                echo $_SESSION['blad_haslo'];
            ?>
        </div>

        <!--------------------------------------------------------------------------------------------->

        <input type="submit" value="Zarejestruj">


    </form>
</div>

</body>
</html>