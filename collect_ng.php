<?php
require_once("./config/config.php");
/**
 * NG_DATA table generator
 */

$db->query("
    CREATE TABLE `lptrace2`.`t_ng_data`  (
      `id` int NOT NULL AUTO_INCREMENT,
      `serial` varchar(20) NOT NULL,
      PRIMARY KEY (`id`)
    )
    ");

$result = $db->query("SELECT COUNT(*) as count FROM t_ng_data");
$ngDataCount = 0;
while ($row = mysqli_fetch_object($result)) {
    $ngDataCount = $row->count;
}

if ($ngDataCount == 0) {
    foreach (['t_lp_quality_1', 't_lp_quality_2', 't_lp_quality_3', 't_lp_quality_4', 't_lp_quality_5', 't_lp_quality_6',] as $table) {
        $db->query("
            INSERT INTO t_ng_data (id, serial)
                SELECT NULL as id,
                    CONCAT('HZ',SUBSTR(CHAR(`Serial No.7`),2,1),
                    SUBSTR(CHAR(`Serial No.7`),1,1),
                    SUBSTR(CHAR(`Serial No.6`),2,1),
                    SUBSTR(CHAR(`Serial No.6`),1,1),
                    SUBSTR(CHAR(`Serial No.5`),2,1),
                    SUBSTR(CHAR(`Serial No.5`),1,1),
                    SUBSTR(CHAR(`Serial No.4`),2,1),
                    SUBSTR(CHAR(`Serial No.4`),1,1),
                    SUBSTR(CHAR(`Serial No.3`),2,1),
                    SUBSTR(CHAR(`Serial No.3`),1,1),
                    SUBSTR(CHAR(`Serial No.2`),2,1),
                    SUBSTR(CHAR(`Serial No.2`),1,1),
                    SUBSTR(CHAR(`Serial No.1`),2,1),
                    SUBSTR(CHAR(`Serial No.1`),1,1)) as serial
                FROM " . $table . " WHERE `Inspection result 1`=2");
    }
}