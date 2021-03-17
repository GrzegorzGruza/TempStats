<?php
require_once "connect.php";

$conn = oci_connect($db_user, $db_password, $host);
if (!$conn) {
    echo "oci_connect failed\n";
    $e = oci_error();
    echo $e['message'];
} else {
    $stmt = oci_parse($conn, "SELECT fst_name, lst_name, login FROM Person");
    oci_execute($stmt, OCI_NO_AUTO_COMMIT);
    while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
        echo "<option>" . $row[LOGIN] . " (" . $row[FST_NAME] . " " . $row[LST_NAME] . ")</option>";
    }
}
?>