<?php
session_start();

if (!isset($_SESSION['login']))
    header('Location: dla_zalogowanych.php');
?>
<!DOCTYPE HTML>
<html lang="pl">
<meta charset="UTF-8"/>
<head>

    <title>TempStats</title>
    <meta name="descripion" content="Baza danych pomiarów temeratur"/>
    <meta name="keywords" content="temperature, database, dataset, climat, changes"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <link rel="stylesheet" href="style.css" type="text/css"/>
    <link rel="stylesheet" href="pola_wyboru.css" type="text/css"/>
    <link href="https://fonts.googleapis.com/css?family=Lato:400,900&amp;subset=latin-ext" rel="stylesheet">
</head>

<body>
<div id="wrapper">
    <a href="#" class="scrollup"></a>
    <div id="logo">TempStats</div>

    <div class="space_none"></div>

    <?php echo file_get_contents("menu.php"); ?>


    <div id="container">

        <?php echo file_get_contents("topbar_small.php"); ?>

        <?php echo file_get_contents("sidemenu.php"); ?>

        <div id="content">
            <span class="bigtitle">Dodawanie pomiarów</span>
            <div class="dottedline"></div>


            <form action="pomiary_dodaj.php" method="post">

                <!-- UZUPEŁNIANIE Z PHP -->
                <div class="input_label"> Wybierz miejscowość</div>
                <select id="input_long" name="city_select">
                    <?php include "list_city_and_over.php"; ?>
                </select>

                <div style="clear: both"></div>

                <div class="input_label"> Daty początka i końca pomiarów</div>
                <input type="date" name="dataPocz" id="dataPocz" onChange="change()">
                <input type="date" name="dataKon" id="dataKon" onChange="change()">

                <div style="clear: both"></div>


                <table id="maintable">
                    <tbody id="maintablebody">
                    </tbody>
                </table>

                <div style="clear: both"></div>

                <input type="submit" value="Dodaj pomiary">
            </form>

        </div>
    </div>

    <?php echo file_get_contents("footer.php"); ?>

</div>

<div id="login">
    <div id="login_content">
        <?php
        if (!isset($_SESSION['info_logowanie']))
            $_SESSION['info_logowanie'] = "<a href='formularz-logowania'>Zaloguj się</a> lub <a href='formularz-rejestracji'>zarejestruj</a>";
        echo $_SESSION['info_logowanie'];
        ?>
    </div>
</div>

<script src="jquery-3.2.0.min.js"></script>
<script src="jquery.scrollTo.min.js"></script>
<script src="scripts.js"></script>
<script src="pomiary_dodawanie.js"></script>


</body>
