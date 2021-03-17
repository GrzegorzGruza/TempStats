<?php
session_start();
$_SESSION['back'] = "strona-glowna";
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
    <div id="logo">TempStats</div>

    <div class="space_none"></div>

    <?php echo file_get_contents("menu.php"); ?>


    <div id="container">
        <?php echo file_get_contents("topbar.php"); ?>

        <?php echo file_get_contents("sidemenu.php"); ?>

        <div id="content">
            <span class="bigtitle">Ranking ciepłoty państw</span>
            <div class="dottedline"></div>
            <table class="table_standard">
                <tbody>
                <tr>
                    <th>Lp.</th>
                    <th>Państwo</th>
                    <th>Średnia temperatura</th>
                </tr>
                <?php
                require_once "connect.php";

                $conn = oci_connect($db_user, $db_password, $host);
                if (!$conn) {
                    echo "oci_connect failed\n";
                    $e = oci_error();
                    echo $e['message'];
                } else {
                    $q = '
                                            SELECT Cou_name, avg(M.temp) avg_temp
                                            FROM Measurment M,
                                                 (
                                                     SELECT C.name C_name,
                                                            C.id   C_id,
                                                            Sub_name,
                                                            Cou_name
                                                     FROM City C,
                                                          (
                                                              SELECT Sub.name Sub_name,
                                                                     Cou.name Cou_name,
                                                                     Sub.id   Sub_id,
                                                                     Cou.id   Cou_id
                                                              FROM Substate Sub
                                                                       RIGHT JOIN Country Cou
                                                                                  ON Sub.country = Cou.id
                                                          )
                                                     WHERE C.substate = Sub_id
                                                        OR C.country = Cou_id
                                                 )
                                            WHERE M.city_id = C_id
                                            GROUP BY Cou_name
                                            ORDER BY avg_temp';

                    $stmt = oci_parse($conn, $q);
                    oci_execute($stmt, OCI_NO_AUTO_COMMIT);
                    $no = 1;
                    while (($row = oci_fetch_array($stmt, OCI_BOTH))) {

                        echo '<tr>
                                                        <td>' . $no++ . '.</td>
                                                        <td>' . $row[COU_NAME] . '</td>
                                                        <td>' . round($row[AVG_TEMP], 3) . '</td>
                                                    </tr>';
                    }

                }
                ?>
                </tbody>
            </table>
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


</body>
