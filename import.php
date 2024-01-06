<?php
require_once("./config/config.php");
require_once("functions.php");


//GET Default Process
$query = "SELECT * FROM {$tblCastProcesses} ORDER BY id ASC limit 1";
$result = $db->query($query);
$process = mysqli_fetch_object($result);
if($process) {
    $default_process = $process->id;
    $default_process_name = $process->process_name;
} else{
    $default_process = "";
    $default_process_name = "";
}
$data_path = 'data/'.$default_process_name;
$files = scandir($data_path, 1);
foreach($files as $file){
   // echo "<br>".$file;
    
    $file_parts = explode('.', $file);
    if(isset($file_parts['1'])){
    if(strtolower($file_parts['1']) == 'csv'){
        $file_name =  $file_parts['0'];
        $shift = 'shift'.substr($file_name, -1, 1);
        $date_y = substr($file_name, 1, 2);
        $date_m = substr($file_name, 3, 2);
        $date_d = substr($file_name, 5, 2);
        $graph_date = '20'.$date_y.'-'.$date_m.'-'.$date_d;
        $re = import_db_cron($graph_date, $data_path.'/'.$file, $default_process, $shift);
        rename($data_path.'/'.$file, $data_path.'/imported/'.$file);
    }
    }
}



/*if ($handle = opendir($path_data)) {

    while (false !== ($entry = readdir($handle))) {

        if ($entry != "." && $entry != "..") {

            echo  '<br>'."$entry\n";
        }
    }

    closedir($handle);
}
*/


die();

// Get Live graph date and shift
if ($current < $today . " " . $shift_settings[1]['start']) {
    $live_shift = "shift3";
    $live_date = $yesterday;
} else if ($current >= $today . " " . $shift_settings[1]['start'] && $current < $today . " " . $shift_settings[1]['end']) {
    $live_shift = "shift1";
    $live_date = $today;
} else if ($current >= $today . " " . $shift_settings[2]['start'] && $current < $today . " " . $shift_settings[2]['end']) {
    $live_shift = "shift2";
    $live_date = $today;
} else {
    $live_shift = "shift3";
    $live_date = $today;
}

//Get shift
if (isset($_POST['graph_shift'])) {
    $shift = $_POST['graph_shift'];
} else {
    $shift = $live_shift;
}

if (isset($_POST['graph_date'])) {
    $graph_date = convert_date_string($_POST['graph_date']);
} else {
    $graph_date = $live_date;
}

//GET Default Process
$query = "SELECT * FROM {$tblCastProcesses} ORDER BY id ASC limit 1";
$result = $db->query($query);
$process = mysqli_fetch_object($result);
if($process) {
    $default_process = $process->id;
    $default_process_name = $process->process_name;
} else{
    $default_process = "";
    $default_process_name = "";
}


if($default_process != "") {
    $re = import_db($graph_date, $default_process, $shift);
  //  echo $re;
}


?>
