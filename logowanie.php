<?php
session_start();
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <title>Philosophia</title>
    <meta name="description" content="Logowanie"/>
    <meta name="keywords" content="filozofia, zaloguj, logowanie"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>

    <link rel="stylesheet" href="logowanie_style.css" type="text/css"/>

</head>

<body>

<div id="container">
    <form action="zaloguj.php" method="post">

        <input type="text" placeholder="login" onfocus="this.placeholder=''" onblur="this.placeholder='login'"
               name="login">

        <input type="password" placeholder="hasło" onfocus="this.placeholder=''" onblur="this.placeholder='hasło'"
               name="haslo">

        <input type="submit" value="Zaloguj się">


        <div id="blad">
            <?php
            if (isset($_SESSION['blad_logowanie']))
                echo $_SESSION['blad_logowanie'];
            ?>
        </div>

    </form>
</div>

</body>
</html>