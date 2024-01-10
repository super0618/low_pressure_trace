<?php
require_once("./config/config.php");
require_once("functions.php");

if($_GET['do']=='line-master'){
    include('masters/line-master.php');
}elseif($_GET['do']=='equipment-master'){
    include('masters/equipment-master.php');
}elseif($_GET['do']=='group-master'){
    include('masters/group-master.php');
}elseif($_GET['do']=='error-master'){
    include('masters/error-master.php');
}elseif($_GET['do']=='engine-type-master'){
    include('masters/engine-type-master.php');
}elseif($_GET['do']=='shift-master'){
    include('masters/shift-master.php');
}elseif($_GET['do']=='defect-master'){
    include('masters/defect-master.php');
}elseif($_GET['do']=='worker-master'){
    include('masters/worker-master.php');
}elseif($_GET['do']=='scale-master'){
    include('masters/scale-master.php');
}

?>
