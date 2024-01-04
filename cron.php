<?php
require_once("./config/config.php");

$result = $db->query("SELECT * FROM t_ng_check");
while ($row = mysqli_fetch_object($result)) {
    if (!$row->result) {
        $serial = $row->serial;

        $ngResult = $db->query("SELECT * FROM t_ng_data WHERE serial='" . $serial . "'");
        if ($row = mysqli_fetch_object($ngResult)) {
            $db->query("UPDATE t_ng_check SET result=5, `timestamp`=CURRENT_TIMESTAMP WHERE serial='" . $serial . "'");
        }
    }
}
