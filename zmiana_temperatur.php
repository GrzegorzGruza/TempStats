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
            <span class="bigtitle">Zmiana temperatury na Ziemi</span>
            <div class="dottedline"></div>
            <table class="table_standard">
                <tbody>
                <tr>
                    <th>Rok</th>
                    <th>Średnia temperatura</th>
                    <th>Roczna zmiana temeratury</th>
                    <th>Łączna zmiana</th>
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
                                            SELECT to_char(Me.date_of, 'YYYY') d, AVG(Me.temp) avg_temp
                                                  FROM Measurment Me
                                                  WHERE to_char(Me.date_of, 'YYYY') < '2020'
                                                  GROUP BY to_char(Me.date_of, 'YYYY')
                                                  ORDER BY to_char(Me.date_of, 'YYYY')";

                    $stmt = oci_parse($conn, $q);
                    oci_execute($stmt, OCI_NO_AUTO_COMMIT);
                    $no = 0;
                    $last = 0;
                    $first = 0;
                    while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
                        if ($no++ === 0) {
                            $first = $row[AVG_TEMP];
                        }
                        $diff = round($row[AVG_TEMP] - $last, 3);
                        $width = ($row[AVG_TEMP] - 58) * 70;
                        echo '<tr>
                                                        <td>' . $row[D] . '</td>
                                                        <td>' . round($row[AVG_TEMP], 4) . '</td>
                                                        <td>
                                                            <div style="float: left;
                                                                 height: 30px;
                                                                 background-color: #f36742;
                                                                 width: ' . $width . 'px;"></div>
                                                            <div style="float: left; margin-left: 7px;">' . ($no === 1 ? "" : ($diff > 0 ? '+' : '') . $diff) . '</div>
                                                        </td>
                                                        <td>' . ($row[AVG_TEMP] - $first > 0 ? '+' : '') . round($row[AVG_TEMP] - $first, 4) . '</td>
                                                    </tr>';
                        $last = $row[AVG_TEMP];
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
