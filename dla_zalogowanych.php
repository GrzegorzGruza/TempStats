<?php
session_start();

if (isset($_SESSION['login']))
    header('Location: strona-glowna');
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <title>Logowanie</title>
    <meta name="description" content="Logowanie"/>
    <meta name="keywords" content="filozofia, zaloguj, logowanie"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <link href="https://fonts.googleapis.com/css?family=Lato:400,900&amp;subset=latin-ext" rel="stylesheet">

    <link rel="stylesheet" href="logowanie_style.css" type="text/css"/>
    <link rel="stylesheet" href="font/css/fontello.css" type="text/css"/>

</head>

<body>

<div id="container">
    <div id="icon">
        <i class="icon-attention"></i> </br>
        ERROR 403
    </div>
    <div id="info">
        Ta strona jest widoczna tylko dla zalogowanych użytkowników</a>
    </div>

    <div id="zaloguj_zarejestruj">
        <a href="formularz-logowania"> Zaloguj się </a> </br>
        <a href="formularz-rejestracji"> Zarejestruj</a>
    </div>
</div>

</body>