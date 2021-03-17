<?php
session_start();
// 	if(isset($_SESSION['login']))
// 		header('Location: index.php');

if (!isset($_SESSION['back']))
    $_SESSION['back'] = "index.php";

require_once "connect.php";

$conn = oci_connect($db_user, $db_password, $host);
if (!$conn) {
    echo "oci_connect failed\n";
    $e = oci_error();
    echo $e['message'];
} else if (!isset($_POST['dataPocz'])) {
    header('Location: ' . $_SESSION['back']);
} else {
    $date1 = $_POST['dataPocz'];
    $city = $_POST['city_select'];
    $date_act = $date1;

    $q = "";

    foreach ($_POST['row'] as $i => $value) {
        if ($value != "") {
            $q = 'INSERT INTO Measurment (city_id, date_of, temp)' .
                ' VALUES (' . $city . ', to_date(\'' . $date_act . '\', \'yyyy-mm-dd\'), ' . $value . ')';
        }
        $date_act = date('Y-m-d', strtotime('+1 day', strtotime($date_act)));
        $query = oci_parse($conn, sprintf($q));
        $success = oci_execute($query, OCI_NO_AUTO_COMMIT);
        if ($success != true) {
//                 echo "nie udało sie";
            oci_rollback($conn);
        }
    }
    oci_commit($conn);
//             echo "udalo sie";
    header('Location: ' . $_SESSION['back']);
}

?>