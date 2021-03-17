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
} else if ($_POST['sub_but'] == "Usuń rekordy") {
    $q = "DELETE FROM Measurment";
    $no = 0;
    foreach ($_POST['delete_row'] as $value) {
        $q .= (($no++ === 0) ? " WHERE" : " OR") . " id = " . $value;
    }

    $query = oci_parse($conn, sprintf($q));
    $success = oci_execute($query, OCI_NO_AUTO_COMMIT);
    if ($success != true) {
        oci_rollback($conn);
    } else {
        oci_commit($conn);
    }
} else if ($_POST['sub_but'] == "Zapisz etykiety") {
    foreach ($_POST['change_label'] as $value) {
        $type = $value % 10;
        $value = floor($value / 10);
        $label = $value % 10;
        $value = floor($value / 10);
        if ($type === 1) continue;
        else if ($type === 2) {
            $q = 'DELETE FROM Labeling WHERE measurment=' . $value . ' AND label=' . $label;
        } else if ($type === 0) {
            $q = 'INSERT INTO Labeling(measurment, label) VALUES (' . $value . ',' . $label . ')';
        }
        $query = oci_parse($conn, sprintf($q));
        $success = oci_execute($query, OCI_NO_AUTO_COMMIT);
        if ($success != true) {
            oci_rollback($conn);
            echo "nie udalo sie";
        }
//             echo $q;
//             echo '<br>';
    }
    oci_commit($conn);
}
header('Location: ' . $_SESSION['back']);

?>