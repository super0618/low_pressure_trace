<?php
require_once("./config/config.php");
require_once("./functions.php");

$action = $_GET['action'];
if ($action == "" || $action == NULL) {
    echo "Action died";
    exit;
}

$defect_filter = isset($_GET['defect_filter']) ? $_GET['defect_filter'] : null;

if ($action == "Retrieval") {
    $page = $_GET['page'];

    $query_conditions = array();
    $query_conditions_sql = '';

    $engine_type = $_POST['engine_type'];
    $line = $_POST['line']; // date selected
    $group = $_POST['group'];
    $casting_machine_number = $_POST['casting_machine_number'];


    $item = $_POST['item'];
    $shift_no = $_POST['shift_no'];
    $select_id_no = $_POST['select_id_no'];
    $ng_judgements_only = $_POST['ng_judgements_only'];

    if ($engine_type != 'unspecified') { //date selected

        $query_conditions[] = " CastingHist.StyleCode= '{$engine_type}' ";
    }

    if ($line != 'unspecified') { //date selected

        $query_conditions[] = " CastingHist.LineNum= '{$line}' ";
    }
    if ($group != 'unspecified') { //date selected

        $query_conditions[] = " CastingHist.Group= '{$group}' ";
    }

    if (strlen($select_id_no) > 0 AND $select_id_no != '') { //date selected

        $query_conditions[] = " CastingHist.Serialnumber= '{$select_id_no}' ";
    }


    //$start_time = get_start_end_time($_POST['from_date'], $shift)['start'];
    //$end_time = get_start_end_time($_POST['to_date'], $shift)['end'];
    $start_time = convert_date_string($_POST['from_date']) . ' 00:00:00';
    $end_time = convert_date_string($_POST['to_date']) . ' 23:59:59';


    $order_asc = $_POST['order_asc'];
    $order_default = $_POST['order_default'];


    $query_conditions[] = " CastingHist.ProductTime >= '{$start_time}' ";
    $query_conditions[] = " CastingHist.ProductTime <= '{$end_time}' ";

    if ($shift_no != 'unspecified') { //shift selected

        //  $query_conditions[] = "shift = '{$shift_no}'";
    }

    $order_by = " ";
    if ($order_default == 'true') { //date selected

        $order_by = " ";
    } elseif ($order_asc == 'true') { //date selected

        $order_by = " ORDER BY CastingHist.ProductTime ASC ";
    } else {
        $order_by = " ORDER BY CastingHist.ProductTime DESC ";
    }

    if (count($query_conditions) > 0) {
        $query_conditions_sql = ' WHERE ' . implode(' AND ', $query_conditions);
    }
    // $query = "SELECT * FROM {$tblCastData} WHERE process_id = {$process_id}  ".$query_conditions_sql." ORDER BY created_at ".$order_by." ";

    $query = "SELECT    `CastingHist`.`Serialnumber`, 
                                `CarMaster`.`CarName` as ENGINE_TYPE,
                                `ClassMaster`.`ClassName`,
                                `CastingHist`.`InspectResult` as inprocess_judgment, 
                                CastingHist.`ProductTime`, 
                                CastingHist.`KindCode`, 
                                 
                                CastingHist.`PlantCode`, 
                                CastingHist.`LineNum`, 
                                CastingHist.`Duty`, 
                                CastingHist.`MachineNum`, 
                                CastingHist.`MoldNum`, 
                                CastingHist.`WorkerID`, 
                                CastingHist.`WorkerName`,  
                                CastingHist.`DutyShots`, 
                                CastingHist.`HaitoShots`, 
                                CastingHist.`NorokakiShots`, 
                                CastingHist.`TogataShots`, 
                                CastingHist.`TogataRevShots`, 
                                CastingHist.`BurnerShots`, 
                                CastingHist.`PressRoomNorokakiShots`, 
                                CastingHist.`HoldRoomNorokakiShots`, 
                                CastingHist.`FluxfeederShots`, 
                                CastingHist.`CastingCycleTime`, 
                                CastingHist.`CycleTime1`, 
                                CastingHist.`CycleTime2`, 
                                CastingHist.`InspecterID`, 
                                CastingHist.`InspecterName`, 
                                
                                CastingHist.`ErrorCode`, 
                                CastingHist.`ErrorContent`, 
                                CastingHist.`WorkNG`, 
                                CastingHist.`WorkNGLocation`, 
                                CastingHist.`WorkDate`, 
                                CastingHist.`WorkNGLocationName`  FROM {$tblCastingHist} AS CastingHist 
                                                            LEFT JOIN {$tblcarmaster} AS CarMaster
                                                            ON CastingHist.StyleCode = CarMaster.CarCode 
                                                            LEFT JOIN {$tblclassmaster} AS ClassMaster 
                                                            ON CastingHist.Group = ClassMaster.ClassCode 
                                                            " . $query_conditions_sql . "
                                                            
                                                             " . $order_by . " ";

    //echo $query;


} elseif ($action == "retrieve-quality-results") {
    $header = '';
    $page = $_GET['page'];

    $query_conditions = array();
    $query_conditions_sql = '';

    $engine_types = array();
    if(isset($_GET['result_target']) && $_GET['result_target'] == 'traceability') {
        if (isset($_GET['engine_type']) && !empty($_GET['engine_type'])) {
            array_push($engine_types, $_GET['engine_type']); // date selected
        }
    } else {
        if (isset($_GET['engine_type']) AND count($_GET['engine_type']) > 0) {

            foreach ($_GET['engine_type'] as $et) {
                $engine_types[] = substr($et, 0, 3);
            }
        }
    }


    $lines = array();
    if(isset($_GET['result_target']) && $_GET['result_target'] == 'traceability') {
        if (isset($_GET['line']) && !empty($_GET['line'])) {
            array_push($lines, $_GET['line']); // date selected
        }
    } else {
        if (isset($_GET['line']) && !empty($_GET['line'])) {
            $lines = $_GET['line']; // date selected
        }
    }

    $groups = array();
    if(isset($_GET['result_target']) && $_GET['result_target'] == 'traceability') {
        if (isset($_GET['group']) && !empty($_GET['group'])) {
            array_push($groups, $_GET['group']); // date selected
        }
    } else {
        if (isset($_GET['group']) && !empty($_GET['group'])) {
            $groups = $_GET['group'];
        }
    }

    $casting_machine_numbers = array();
    if(isset($_GET['result_target']) && $_GET['result_target'] == 'traceability') {
        if (isset($_GET['casting_machine_number']) && !empty($_GET['casting_machine_number'])) {
            array_push($casting_machine_numbers, $_GET['casting_machine_number']); // date selected
        }
    } else {
        if (isset($_GET['casting_machine_number']) && !empty($_GET['casting_machine_number'])) {
            $casting_machine_numbers = $_GET['casting_machine_number'];
        }
    }

    if (count($casting_machine_numbers) == 0) {
        $casting_machine_query = "SELECT * FROM {$tblcastingmaster}";
        $casting_machine_result = $db->query($casting_machine_query);
        while ($casting_machine_row = mysqli_fetch_object($casting_machine_result)) {

            $casting_machine_numbers[] = $casting_machine_row->CastingCode;

        }
    }

    $casting_machine_numbers = array_unique($casting_machine_numbers);

    $items = array();
    if(isset($_GET['result_target']) && $_GET['result_target'] == 'traceability') {
        if (isset($_GET['item'])) {
            array_push($items, $_GET['item']); // date selected
        }
    } else {
        if (isset($_GET['item'])) {
            $items = $_GET['item'];
        }
    }

    $shift_no = array();
    if(isset($_GET['result_target']) && $_GET['result_target'] == 'traceability') {
        if (isset($_GET['shift_no'])) {
            array_push($shift_no, $_GET['shift_no']); // date selected
        }
    } else {
        if (isset($_GET['shift_no'])) {
            $shift_no = $_GET['shift_no'];
        }
    }

    $workers = array();
    if (isset($_GET['workers'])) {
        $workers = $_GET['workers'];
    }

    $worker_defect_contents = array();
    if (isset($_GET['worker_defect_content'])) {
        $worker_defect_contents = $_GET['worker_defect_content'];
    }

    $error_contents = array();
    if (isset($_GET['error_content'])) {
        $error_contents = $_GET['error_content'];
    }

    $select_id_no = '';
    if (isset($_GET['select_id_no'])) {
        $select_id_no = $_GET['select_id_no'];
    }

    $ng_judgements_only = '';
    if (isset($_GET['ng_judgements_only'])) {
        $ng_judgements_only = $_GET['ng_judgements_only'];
        if($ng_judgements_only == "on")
            $query_conditions[] = " `Inspection result 1` = 0";
    }

    if (count($engine_types) > 0) { //date selected
        $engine_types_items = array();
        foreach ($engine_types as $engine_type) {
            $engine_types_items[] = "'" . $engine_type . "'";
        }
        // $query_conditions[] = " CastingHist.StyleCode IN ( ".implode(', ', $engine_types_items).") ";
    }

    if (count($lines) > 0) { //date selected
        $lines_items = array();
        foreach ($lines as $line) {
            $lines_items[] = "'" . $line . "'";
        }
        $query_conditions[] = " `Manufacturing line` IN ( " . implode(', ', $lines_items) . ") ";
    }

    if (count($groups) > 0) { //date selected
        $groups_items = array();
        foreach ($groups as $group) {
            $groups_items[] = "'" . $group . "'";
        }
        $query_conditions[] = " `Casting group` IN ( " . implode(', ', $groups_items) . ") ";
    }

    if (count($workers) > 0) { //worker selected
        $worker_items = array();
        foreach ($workers as $worker) {
            $worker_items[] = "'" . $worker . "'";
        }
        $query_conditions[] = " `Worker` IN ( " . implode(', ', $worker_items) . ") ";
    }

    if (count($worker_defect_contents) > 0) { //worker defect content selected
        $content_items = array();
        foreach ($worker_defect_contents as $content) {
            $content_items[] = "'" . $content . "'";
        }
        $query_conditions[] = " `Work defect content` IN ( " . implode(', ', $content_items) . ") ";
    }

    if (count($error_contents) > 0) { //error contents selected
        $error_items = array();
        foreach ($error_contents as $content) {
            $error_items[] = "'" . $content . "'";
        }
        $query_conditions[] = " `Error content 1` IN ( " . implode(', ', $error_items) . ") ";
    }

    if (strlen($select_id_no) > 0 AND $select_id_no != '') { //date selected
        if(strpos($select_id_no, ",")){
            $select_id_nos = explode(",", $select_id_no);
            $ids_query_string = "";
            foreach ($select_id_nos as $id_no){
                $id_no = $string = str_replace(' ', '', $id_no);
                if($ids_query_string == ""){
                    $ids_query_string .= "( SUBSTR(CHAR(`Serial No.7`),2,1) = '" . substr($id_no, -13, 1) . "' AND ";
                    $ids_query_string .= " SUBSTR(CHAR(`Serial No.7`),1,1) = '" . substr($id_no, -12, 1) . "' AND ";
                    $ids_query_string .= " SUBSTR(CHAR(`Serial No.6`),2,1) = '" . substr($id_no, -11, 1) . "' AND ";
                    $ids_query_string .= " SUBSTR(CHAR(`Serial No.6`),1,1) = '" . substr($id_no, -10, 1) . "' AND ";
                    $ids_query_string .= " SUBSTR(CHAR(`Serial No.5`),2,1) = '" . substr($id_no, -9, 1) . "' AND ";
                    $ids_query_string .= " SUBSTR(CHAR(`Serial No.5`),1,1) = '" . substr($id_no, -8, 1) . "' AND ";
                    $ids_query_string .= " SUBSTR(CHAR(`Serial No.4`),2,1) = '" . substr($id_no, -7, 1) . "' AND ";
                    $ids_query_string .= " SUBSTR(CHAR(`Serial No.4`),1,1) = '" . substr($id_no, -6, 1) . "' AND ";
                    $ids_query_string .= " SUBSTR(CHAR(`Serial No.3`),2,1) = '" . substr($id_no, -5, 1) . "' AND ";
                    $ids_query_string .= " SUBSTR(CHAR(`Serial No.3`),1,1) = '" . substr($id_no, -4, 1) . "' AND ";
                    $ids_query_string .= " SUBSTR(CHAR(`Serial No.2`),2,1) = '" . substr($id_no, -3, 1) . "' AND ";
                    $ids_query_string .= " SUBSTR(CHAR(`Serial No.2`),1,1) = '" . substr($id_no, -2, 1) . "' AND ";
                    $ids_query_string .= " SUBSTR(CHAR(`Serial No.1`),1,1) = '" . substr($id_no, -1, 1) . "' ) ";
                } else {
                    $ids_query_string .= " OR ( SUBSTR(CHAR(`Serial No.7`),2,1) = '" . substr($id_no, -13, 1) . "' AND ";
                    $ids_query_string .= " SUBSTR(CHAR(`Serial No.7`),1,1) = '" . substr($id_no, -12, 1) . "' AND ";
                    $ids_query_string .= " SUBSTR(CHAR(`Serial No.6`),2,1) = '" . substr($id_no, -11, 1) . "' AND ";
                    $ids_query_string .= " SUBSTR(CHAR(`Serial No.6`),1,1) = '" . substr($id_no, -10, 1) . "' AND ";
                    $ids_query_string .= " SUBSTR(CHAR(`Serial No.5`),2,1) = '" . substr($id_no, -9, 1) . "' AND ";
                    $ids_query_string .= " SUBSTR(CHAR(`Serial No.5`),1,1) = '" . substr($id_no, -8, 1) . "' AND ";
                    $ids_query_string .= " SUBSTR(CHAR(`Serial No.4`),2,1) = '" . substr($id_no, -7, 1) . "' AND ";
                    $ids_query_string .= " SUBSTR(CHAR(`Serial No.4`),1,1) = '" . substr($id_no, -6, 1) . "' AND ";
                    $ids_query_string .= " SUBSTR(CHAR(`Serial No.3`),2,1) = '" . substr($id_no, -5, 1) . "' AND ";
                    $ids_query_string .= " SUBSTR(CHAR(`Serial No.3`),1,1) = '" . substr($id_no, -4, 1) . "' AND ";
                    $ids_query_string .= " SUBSTR(CHAR(`Serial No.2`),2,1) = '" . substr($id_no, -3, 1) . "' AND ";
                    $ids_query_string .= " SUBSTR(CHAR(`Serial No.2`),1,1) = '" . substr($id_no, -2, 1) . "' AND ";
                    $ids_query_string .= " SUBSTR(CHAR(`Serial No.1`),1,1) = '" . substr($id_no, -1, 1) . "' ) ";
                }
            }

            $ids_query_string = " ( " . $ids_query_string . " ) ";
            $query_conditions[] = $ids_query_string;
        } else {
            $query_conditions[] = " SUBSTR(CHAR(`Serial No.7`),2,1) = '" . substr($select_id_no, -13, 1) . "' ";
            $query_conditions[] = " SUBSTR(CHAR(`Serial No.7`),1,1) = '" . substr($select_id_no, -12, 1) . "'";
            $query_conditions[] = " SUBSTR(CHAR(`Serial No.6`),2,1) = '" . substr($select_id_no, -11, 1) . "' ";
            $query_conditions[] = " SUBSTR(CHAR(`Serial No.6`),1,1) = '" . substr($select_id_no, -10, 1) . "' ";
            $query_conditions[] = " SUBSTR(CHAR(`Serial No.5`),2,1) = '" . substr($select_id_no, -9, 1) . "' ";
            $query_conditions[] = " SUBSTR(CHAR(`Serial No.5`),1,1) = '" . substr($select_id_no, -8, 1) . "' ";
            $query_conditions[] = " SUBSTR(CHAR(`Serial No.4`),2,1) = '" . substr($select_id_no, -7, 1) . "' ";
            $query_conditions[] = " SUBSTR(CHAR(`Serial No.4`),1,1) = '" . substr($select_id_no, -6, 1) . "' ";
            $query_conditions[] = " SUBSTR(CHAR(`Serial No.3`),2,1) = '" . substr($select_id_no, -5, 1) . "' ";
            $query_conditions[] = " SUBSTR(CHAR(`Serial No.3`),1,1) = '" . substr($select_id_no, -4, 1) . "' ";
            $query_conditions[] = " SUBSTR(CHAR(`Serial No.2`),2,1) = '" . substr($select_id_no, -3, 1) . "' ";
            $query_conditions[] = " SUBSTR(CHAR(`Serial No.2`),1,1) = '" . substr($select_id_no, -2, 1) . "' ";
            //$query_conditions[] = " SUBSTR(CHAR(`Serial No.1`),2,1) = '".substr($select_id_no, -2, 1)."' ";  to be removed
            $query_conditions[] = " SUBSTR(CHAR(`Serial No.1`),1,1) = '" . substr($select_id_no, -1, 1) . "' ";
        }


    }

    //$start_time = get_start_end_time($_GET['from_date'], $shift)['start'];
    //$end_time = get_start_end_time($_GET['to_date'], $shift)['end'];
    if ($shift_no != 'unspecified' && is_array($shift_no) && count($shift_no) > 0) { //shift selected
        $shifts = $shift_no[0];
        $start_time = '';
        $end_time = '';
        //first shift
        $q = "SELECT * FROM {$tbldutymaster} ORDER BY DutyCode ASC limit 1";
        $r = $db->query($q);
        $s = mysqli_fetch_object($r);
        $s_end_time = $s->StartTime;

        foreach ($shifts as $key => $shift) {
            //get start time
            if($key == 0) {
                $q = "SELECT * FROM {$tbldutymaster} WHERE DutyCode = {$shift}";
                $r = $db->query($q);
                $s = mysqli_fetch_object($r);
                if (isset($_GET['from_date'])) {
                    $start_time = $start_time = convert_date_string($_GET['from_date']) . " " . $s->StartTime;
                }
            }

            //get end time
            $q = "SELECT * FROM {$tbldutymaster} WHERE DutyCode > {$shift} limit 1";
            $r = $db->query($q);
            $s = mysqli_fetch_object($r);
            if($s) {
                if (isset($_GET['to_date'])) {
                    $end_time = convert_date_string($_GET['to_date']) . " " . $s->StartTime;
                }
            } else {
                if (isset($_GET['to_date'])) {
                    $end_time = convert_date_string($_GET['to_date']) . " " . $s_end_time;
                    $end_time = date('Y-m-d H:i:s', strtotime("+1 days", strtotime($end_time)));
                }
            }

        }
    } else {
        $start_time = '';
        if (isset($_GET['from_date'])) {
            $start_time = convert_date_string($_GET['from_date']) . ' 00:00:00';
        }
        $end_time = '';
        if (isset($_GET['to_date'])) {
            $end_time = convert_date_string($_GET['to_date']) . ' 23:59:59';
        }
    }

    $order_asc = '';
    if (isset($_GET['order_asc'])) {
        $order_asc = $_GET['order_asc'];
    }
    $order_default = '';
    if (isset($_GET['order_default'])) {
        $order_default = $_GET['order_default'];
    }


    $query_conditions[] = " `timestamp` >= '{$start_time}' ";
    $query_conditions[] = " `timestamp` <= '{$end_time}' ";

    if ($shift_no != 'unspecified') { //shift selected

        //  $query_conditions[] = "shift = '{$shift_no}'";
    }

    $order_by = " ";
    if ($order_default == 'true') { //date selected
        $order_by = " ";
    } elseif ($order_asc == 'true') { //date selected

        $order_by = " ORDER BY `timestamp` ASC ";
    } else {
        $order_by = " ORDER BY `timestamp` DESC ";
    }

    $query_conditions[] = " ( `Serial No.4` != '0' AND `Serial No.5` != '0' AND `Serial No.6` != '0' AND `Serial No.7` != '0' AND `Serial No.8` != '0' ) ";
    if (count($query_conditions) > 0) {
        $query_conditions_sql = ' WHERE ' . implode(' AND ', $query_conditions);
    }
    $defect_filter = $defect_filter ? " AND d.defect = '{$defect_filter}'" : '';

    $combined_serial_no_sql = "
        d.`Date/time` as date_time,
        d.id as defect_id,
        d.*
        ";

    $query = "(
        SELECT
        {$combined_serial_no_sql}
        FROM
        {$tblDefects} AS d
        {$query_conditions_sql}
        {$defect_filter}
        {$order_by}
    )";
// echo "<pre>";
// print_r($query);
// echo "</pre>";
//
// echo $query;
// die;
}

$headers = array();
$export = $db->query($query);
$fields = mysqli_num_fields($export);
for ($i = 0; $i < $fields; $i++) {
    $header .= mysqli_fetch_field_direct($export, $i)->name . "\t";
    $headers[] = mysqli_fetch_field_direct($export, $i)->name;
}


$data = "";
$records = array();
while ($row = mysqli_fetch_row($export)) {

    $date_time = $row[1];
    $serial = $row[0];
    $is = 0;
    foreach ($records as $item) {
        if($item[0] == $serial) {
            $is ++;
        }
    }
    if($is == 0) {
        array_push($records, $row);


        $line = '';

        foreach ($row as $key => $value) {
            // echo ;
            if ($headers[$key] == 'serial') {
                $serial = $value;
            }
            if ((!isset($value)) || ($value == "")) {
                $value = "\t";
                if ($headers[$key] == 'Product model') {
                    //echo $index
                    $value = substr($serial, 1, 3);
                    $value = str_replace('"', '""', $value);
                    $value = '"' . $value . '"' . "\t";
                }
                if ($headers[$key] == 'Product type') {
                    //echo $index
                    $value = substr($serial, 0, 1);
                    $value = str_replace('"', '""', $value);
                    $value = '"' . $value . '"' . "\t";
                }
                if ($headers[$key] == 'Manufacturing line') {
                    //echo $index
                    $value = substr($serial, 5, 1);
                    $value = str_replace('"', '""', $value);
                    $value = '"' . $value . '"' . "\t";
                }
                if ($headers[$key] == 'Casting shift') {
                    //echo $index
                    $value = substr($serial, -3, 1);
                    $value = str_replace('"', '""', $value);
                    $value = '"' . $value . '"' . "\t";
                }
                if ($headers[$key] == 'Machine No.') {
                    //echo $index
                    $value = 'EDM-010' . substr($serial, 6, 1);
                    $value = str_replace('"', '""', $value);
                    $value = '"' . $value . '"' . "\t";
                }
                if ($headers[$key] == 'Manufacturing plant') {
                    //echo $index
                    $value = 'U';
                    $value = str_replace('"', '""', $value);
                    $value = '"' . $value . '"' . "\t";
                }
            } else {
                $value = str_replace('"', '""', $value);
                $value = '"' . $value . '"' . "\t";
            }
            $line .= $value;
        }
        $data .= trim($line) . "\n";
    }
}
$data = str_replace("\r", "", $data);

if ($data == "") {
    $data = "\n(0) Records Found!\n";
}

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=lptrace-export-" . time() .".xls");
header("Pragma: no-cache");
header("Expires: 0");

print "$header\n$data";
?>
