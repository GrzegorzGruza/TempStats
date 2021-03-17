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
            <span class="bigtitle">Wyszukiwanie pomiaru</span>
            <div class="dottedline"></div>


            <form action="przeglad-danych" method="get" id="searchform">

                <div class="input_label">Wyszukiwany obszar</div>
                <select name="type_of_place" id="type_of_place" onChange="change()">
                    <option disabled selected value>-- wybierz --</option>
                    <option value="regiony">Region</option>
                    <option value="panstwa">Państwo</option>
                    <option value="stany">Stan</option>
                    <option value="miasta">Miasto</option>
                </select>

                <input type="text" id="place_select" name="place_select" onfocus="this
                                .placeholder=''" autocomplete="off"/>
                <datalist id="regiony">
                    <?php include "list_region.php"; ?>
                </datalist>

                <datalist id="panstwa">
                    <?php include "list_country.php"; ?>
                </datalist>

                <datalist id="stany">
                    <?php include "list_state.php"; ?>
                </datalist>

                <datalist id="miasta">
                    <?php include "list_city.php"; ?>
                </datalist>

                <div style="clear: both"></div>

                <div class="input_label"> Daty pomiaru</div>
                <input type="text" name="dataPocz" id="dataPocz"
                       placeholder="Nie wcześniej niż" onfocus="this.placeholder=''; this.type='date'"
                       onblur=" this.type='text'; this.placeholder='Nie wcześniej niż'">

                <input type="text" name="dataKon" id="dataKon"
                       placeholder="Nie później niż" onfocus="this.placeholder=''; this.type='date'"
                       onblur=" this.type='text'; this.placeholder='Nie później niż'">

                <div style="clear: both"></div>

                <div class="input_label"> Dopuszczalne etykiety</div>
                <?php
                require_once "connect.php";

                $conn = oci_connect($db_user, $db_password, $host);
                if (!$conn) {
                    echo "oci_connect failed\n";
                    $e = oci_error();
                    echo $e['message'];
                } else {
                    $stmt = oci_parse($conn, "SELECT id, name FROM label");
                    oci_execute($stmt, OCI_NO_AUTO_COMMIT);
                    while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
                        echo '<input type="checkbox"
                                                   class="checkbox_temp"
                                                   id="checkbox_label' . $row[ID] .
                            '" name="checkbox_label[]"
                                                   value="' . $row[ID] . '"
                                                   checked="">';
                        echo '<label for="checkbox_label' . $row[ID] .
                            '" class="checkbox_label">'
                            . $row[NAME]
                            . '</label><br>';

                    }
                }
                ?>
                <input type="checkbox" id="labelrequired" class="input_sublabel" name="labelrequired">
                <label for="labelrequired">Musi posiadać te etykiety</label><br>


                <input type="submit" value="Wyszukaj">
            </form>

            <div id="dottedLine_separator"></div>

            <form action="zmien_dane.php" id="changeform" method="post">
                <table class="table_standard">
                    <tbody>
                    <tr>
                        <th></th>
                        <th>Region</th>
                        <th>Państwo</th>
                        <th>Stan</th>
                        <th>Miejscowość</th>
                        <th>Data</th>
                        <th>Pomiar</th>
                        <th>Etykiety</th>
                    </tr>
                    <?php
                    require_once "connect.php";

                    $conn = oci_connect($db_user, $db_password, $host);
                    if (!$conn) {
                        echo "oci_connect failed\n";
                        $e = oci_error();
                        echo $e['message'];
                    } else if (!isset($_GET['dataPocz'])) {
                    } else {
                        $con_label = "";
                        $con_date1 = "";
                        $con_date2 = "";
                        $con_label = "";
                        $con_name = "";
                        if (isset($_GET['dataPocz']) && $_GET['dataPocz'] != "") {
                            $con_date1 = "date_of >= TO_DATE('" . $_GET['dataPocz'] . "','YYYY-MM-DD')";
                        }
                        if (isset($_GET['dataKon']) && $_GET['dataKon'] != "") {
                            $con_date2 = "date_of <= TO_DATE('" . $_GET['dataKon'] . "','YYYY-MM-DD')";
                        }
                        if (isset($_GET['type_of_place']) && $_GET['type_of_place'] != "+---+"
                            && isset($_GET['place_select']) && !empty($_GET['place_select'])) {
                            if ($_GET['type_of_place'] == 'regiony')
                                $con_name = "R_n='" . $_GET['place_select'] . "'";
                            if ($_GET['type_of_place'] == 'panstwa')
                                $con_name = "Co_n='" . $_GET['place_select'] . "'";
                            if ($_GET['type_of_place'] == 'stany')
                                $con_name = "Su_n='" . $_GET['place_select'] . "'";
                            if ($_GET['type_of_place'] == 'miasta')
                                $con_name = "Ci_n='" . $_GET['place_select'] . "'";
                        }
                        $q = '
                                                SELECT Re_n, Co_n, Su_n, Ci_n, Me.temp Me_temp, Me.date_of Me_date_of, Me.id Me_id
                                                FROM Measurment Me,
                                                     (
                                                         SELECT Re_n, Co_n, Su_n, Ci.name Ci_n, Ci.id Ci_id
                                                         FROM City Ci,
                                                              (
                                                                  SELECT Re_n, Co_n, Su.name Su_n, Co_id, Su.id Su_id
                                                                  FROM Substate Su
                                                                           RIGHT JOIN
                                                                       (
                                                                           SELECT Re.name Re_n, Co.name Co_n, Co.id Co_id
                                                                           FROM Region Re,
                                                                                Country Co
                                                                           WHERE Co.region = Re.id
                                                                       )
                                                                       ON Su.country = Co_id
                                                              )
                                                         WHERE Co_id = Ci.country
                                                            OR Su_id = Ci.substate
                                                     )
                                                WHERE Me.city_id = Ci_id';


                        $label_select = array_fill(1, 4, 0);
                        if (isset($_GET['checkbox_label'])) {
                            if (is_array($_GET['checkbox_label'])) {
                                foreach ($_GET['checkbox_label'] as $value) {
                                    $label_select[$value] = 1;
                                }
                            } else {
                                $value = $_GET['checkbox_label'];
                                $label_select[$value] = 1;
                            }
                        }

                        foreach ($label_select as $i => $item) {
                            if ($item === 0)
                                $con_label .= " AND IS_IN_LABELING(Me.id," . $i . ") = 0";
                        }
                        if (isset($_GET['labelrequired'])) {
                            foreach ($label_select as $i => $item) {
                                if ($item === 1)
                                    $con_label .= " AND IS_IN_LABELING(Me.id, " . $i . ") = 1";
                            }
                        }

                        if ($con_date1 != "")
                            $q .= " AND " . $con_date1;
                        if ($con_date2 != "")
                            $q .= " AND " . $con_date2;
                        if ($con_name != "")
                            $q .= " AND " . $con_name;
                        if ($con_label != "")
                            $q .= $con_label;

                        $q .= " ORDER BY date_of";
                        $q = "SELECT * FROM (" . $q . ") WHERE ROWNUM <= 101";
//                                                 echo $q;

                        $stmt = oci_parse($conn, $q);
                        oci_execute($stmt, OCI_NO_AUTO_COMMIT);
                        while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
                            echo '<tr>
                                                        <td> <input type="checkbox" name="delete_row[]" value="' . $row[ME_ID] . '"/>
                                                        </td>
                                                        <td>' . $row[RE_N] . '</td>
                                                        <td>' . $row[CO_N] . '</td>
                                                        <td>' . $row[SU_N] . '</td>
                                                        <td>' . $row[CI_N] . '</td>
                                                        <td>' . $row[ME_DATE_OF] . '</td>
                                                        <td>' . $row[ME_TEMP] . '</td>
                                                        <td>';

                            $l_q =
                                "SELECT id, name, CASE WHEN Labeling.measurment IS NULL THEN 0 ELSE 1 END is_in
                                                        FROM Label LEFT JOIN Labeling
                                                        ON Labeling.label = Label.id
                                                        AND Labeling.measurment =" . $row[ME_ID];
                            $l_stmt = oci_parse($conn, $l_q);
                            oci_execute($l_stmt, OCI_NO_AUTO_COMMIT);
                            while (($row_l = oci_fetch_array($l_stmt, OCI_BOTH))) {
                                if ($row_l[IS_IN] === '1') {
                                    echo '<input type="hidden"
                                                                       class="checkbox_temp" ' .
                                        'name="change_label[' . $row[ME_ID] . $row_l[ID] . ']" ' .
                                        'value=' . $row[ME_ID] . $row_l[ID] . '2' .
                                        ($row_l[IS_IN] === '-' ? " " : ' checked=""') . '>';
                                }
                                echo '<input type="checkbox"
                                                                   class="checkbox_temp"
                                                                   id="checkbox_label' . $row[ME_ID] . $row_l[ID] .
                                    '" name="change_label[' . $row[ME_ID] . $row_l[ID] . ']" ' .
                                    'value=' . $row[ME_ID] . $row_l[ID] . $row_l[IS_IN] .
                                    ($row_l[IS_IN] === '0' ? " " : ' checked=""') . '>';
                                echo '<label for="checkbox_label' . $row[ME_ID] . $row_l[ID] .
                                    '" class="checkbox_label">'
                                    . $row_l[NAME] . '</label>';
                                echo '<br>';
                            }

                            echo '</td>
                                                    </tr>';
                        }
                    }
                    ?>
                    </tbody>
                </table>

                <div style="clear: both"></div>

                <input type="submit" value="Usuń rekordy" name="sub_but">
                <input type="submit" value="Zapisz etykiety" name="sub_but">
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
<script src="select_type_of_place.js"></script>


</body>
