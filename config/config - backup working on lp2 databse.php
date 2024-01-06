<?php
$DB_HOST        = "localhost";
$DB_USER        = "root";
$DB_PASSWORD    = "123123";
$DB_NAME        = "lptrace2";

//Tables
$tblCastingHist     = "t_casting_hist";
$tblcarmaster       = "t_carmaster";
$tbllinemaster      = "t_linemaster";
$tblclassmaster     ="t_classmaster";
$tblcastingmaster   ="t_castingmaster";
$tblequipmaster     ="t_equipmaster";
$tblDefectsSetting  = "defects_setting";
$tblDefects         = "t_defects";

$tblerrormaster     ="t_err_mst";
$tbldutymaster      ="t_dutymaster";
$tbldefectmaster    = "t_ng_mst";
$tblworkermaster    ="t_worker_mst";
$tblscalemaster     ="t_scalemaster";

$tblLPQuality       = "t_lp_quality";


$db = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);
// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}