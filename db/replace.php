<?php


$path_to_file = 'Dump20181010.sql';
$file_contents = file_get_contents($path_to_file);
$file_contents = str_replace("utf8mb4_0900_ai_ci","utf8mb4_general_ci",$file_contents);
$file_contents = str_replace("datetime(6) DEFAULT NULL,","datetime DEFAULT NULL,",$file_contents);
$file_contents = str_replace("00.000000","00",$file_contents);
file_put_contents('Dump20181010_replacted-2.sql',$file_contents);

?>