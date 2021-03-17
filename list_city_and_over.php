<?php
require_once "connect.php";

$conn = oci_connect($db_user, $db_password, $host);
if (!$conn) {
    echo "oci_connect failed\n";
    $e = oci_error();
    echo $e['message'];
} else {

    $q = "
           SELECT C.name C_name,
                  C.id C_id,
                  Sub_name,
                  Cou_name,
                  CASE
                      WHEN C.name IN
                           (SELECT DISTINCT A.name
                            FROM City A,
                                 City B
                            WHERE A.name = B.name
                              AND NOT A.id = B.id)
                          THEN 'false'
                      ELSE 'true' END is_unique
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
           ORDER BY C.name";
    $stmt = oci_parse($conn, $q);
    oci_execute($stmt, OCI_NO_AUTO_COMMIT);
    while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
        if ($row[IS_UNIQUE] == 'true') {
            echo '<option value="' . $row[C_ID] . '">' . $row[C_NAME] . '</option>';
        } else {
            echo '<option value="' . $row[C_ID] . '">' . $row[C_NAME] . ' (' .
                $row[COU_NAME] . ($row[SUB_NAME] === "" ? "" : ', ' . $row[SUB_NAME]) . ')</option>';
        }
    }
}
?>