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
            <span class="bigtitle">Klimatogeniczne pory roku w Polsce</span>
            <div class="dottedline"></div>
            <table class="table_standard">
                <tbody>
                <tr>
                    <th>Rok</th>
                    <th>Zima/Przedwiośnie/Wiosna/Lato/Jesień/Przedzimie</th>
                </tr>
                <?php
                require_once "connect.php";

                $conn = oci_connect($db_user, $db_password, $host);
                if (!$conn) {
                    echo "oci_connect failed\n";
                    $e = oci_error();
                    echo $e['message'];
                } else {
                    $q = "
                                            select d,
                                                   sum(is_zima) zima,
                                                   sum(is_przedwiosnie) przedwiosnie,
                                                   sum(is_wiosna) wiosna,
                                                   sum(is_lato) lato,
                                                   sum(is_jesien) jesien,
                                                   sum(is_przedzimie) przedzimie

                                            from
                                                 (select
                                                     to_char(date_of, 'YYYY') d,
                                                     (case when temp <= 32 then 1 else 0 end) is_zima,
                                                     (case when 32 < temp and temp <= 41
                                                        and '01' <= to_char(date_of, 'MM')
                                                        and to_char(date_of, 'MM') <= '05'
                                                        then 1 else 0 end) is_przedwiosnie,
                                                     (case when 41 < temp and temp < 59
                                                        and '01' <= to_char(date_of, 'MM')
                                                        and to_char(date_of, 'MM') <= '05'
                                                        then 1 else 0 end) is_wiosna,
                                                     (case when 59 <= temp then 1 else 0 end) is_lato,
                                                     (case when 41 < temp and temp < 59
                                                        and '06' <= to_char(date_of, 'MM')
                                                        and to_char(date_of, 'MM') <= '12'
                                                        then 1 else 0 end) is_jesien,
                                                     (case when 32 < temp and temp <= 41
                                                        and '06' <= to_char(date_of, 'MM')
                                                        and to_char(date_of, 'MM') <= '12'
                                                        then 1 else 0 end) is_przedzimie

                                                 from MEASURMENT
                                                 where CITY_ID = 30)
                                            group by d
                                            order by d";

                    $stmt = oci_parse($conn, $q);
                    oci_execute($stmt, OCI_NO_AUTO_COMMIT);
                    while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
                        $zima = $row[ZIMA];
                        $przedwiosnie = $row[PRZEDWIOSNIE];
                        $wiosna = $row[WIOSNA];
                        $lato = $row[LATO];
                        $jesien = $row[JESIEN];
                        $przedzimie = $row[PRZEDZIMIE];
                        echo '<tr>
                                                        <td>' . $row[D] . '</td>
                                                        <td>
                                                            <div style="float: left; height: 30px;
                                                                 background-color: #4040D0;
                                                                 width: ' . $zima . 'px;">' . $row[ZIMA] . '</div>
                                                            <div style="float: left; height: 30px;
                                                                 background-color: #A0FFA0;
                                                                 width: ' . $przedwiosnie . 'px;">' . $row[PRZEDWIOSNIE] . '</div>
                                                            <div style="float: left; height: 30px;
                                                                 background-color: #40A040;
                                                                 width: ' . $wiosna . 'px;">' . $row[WIOSNA] . '</div>
                                                            <div style="float: left; height: 30px;
                                                                 background-color: #f36742;
                                                                 width: ' . $lato . 'px;">' . $row[LATO] . '</div>
                                                            <div style="float: left; height: 30px;
                                                                 background-color: #FFA040;
                                                                 width: ' . $jesien . 'px;">' . $row[JESIEN] . '</div>
                                                            <div style="float: left; height: 30px;
                                                                 background-color: #B0B0FF;
                                                                 width: ' . $przedzimie . 'px;">' . $row[PRZEDZIMIE] . '</div>
                                                        </td>
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
