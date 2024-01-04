<?php
require_once("./config/config.php");
require_once("./functions.php");

$action = $_GET['action'];
if ($action == "" || $action == NULL) {
    echo "Action died";
    exit;
}
?>
<div class="row">
    <?php

    if ($action == "Retrieval") {

        $page = $_GET['page'];

        $query_conditions = array();
        $query_conditions_sql = '';
        $engine_types = array();

        if (isset($_GET['engine_type'])) {
            $engine_types = $_GET['engine_type'];
        }

        $lines = array();
        if (isset($_GET['result_target']) && $_GET['result_target'] == 'traceability') {
            if (isset($_GET['line'])) {
                array_push($lines, $_GET['line']); // date selected
            }
        } else {
            if (isset($_GET['line'])) {
                $lines = $_GET['line']; // date selected
            }
        }

        $groups = array();
        if (isset($_GET['result_target']) && $_GET['result_target'] == 'traceability') {
            if (isset($_GET['group'])) {
                array_push($groups, $_GET['group']); // date selected
            }
        } else {
            if (isset($_GET['group'])) {
                $groups = $_GET['group'];
            }
        }

        $casting_machine_numbers = array();
        if (isset($_GET['result_target']) && $_GET['result_target'] == 'traceability') {
            if (isset($_GET['casting_machine_number'])) {
                array_push($casting_machine_numbers, $_GET['casting_machine_number']); // date selected
            }
        } else {
            if (isset($_GET['casting_machine_number'])) {
                $casting_machine_numbers = $_GET['casting_machine_number'];
            }
        }

        $items = array();
        if (isset($_GET['result_target']) && $_GET['result_target'] == 'traceability') {
            if (isset($_GET['item'])) {
                array_push($items, $_GET['item']); // date selected
            }
        } else {
            if (isset($_GET['item'])) {
                $items = $_GET['item'];
            }
        }

        $shift_no = array();
        if (isset($_GET['result_target']) && $_GET['result_target'] == 'traceability') {
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
            if ($ng_judgements_only == "on")
                $query_conditions[] = " `Inspection result 1` = 2";
        }

        $query_conditions[] = "`Inspection result 1` > 0";
        
        if (count($engine_types) > 0) { //date selected
            $engine_types_items = array();
            foreach ($engine_types as $engine_type) {
                $engine_types_items[] = "'" . $engine_type . "'";
            }
            $query_conditions[] = " CastingHist.StyleCode IN ( " . implode(', ', $engine_types_items) . ") ";
        }

        if (count($lines) > 0) { //date selected
            $lines_items = array();
            foreach ($lines as $line) {
                $lines_items[] = "'" . $line . "'";
            }
            $query_conditions[] = " CastingHist.LineNum IN ( " . implode(', ', $lines_items) . ") ";
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

        if (count($groups) > 0) { //date selected
            $groups_items = array();
            foreach ($groups as $group) {
                $groups_items[] = "'" . $group . "'";
            }
            $query_conditions[] = " CastingHist.Group IN ( " . implode(', ', $groups_items) . ") ";
        }

        if (strlen($select_id_no) > 0 and $select_id_no != '') { //date selected

            $query_conditions[] = " CastingHist.Serialnumber= '{$select_id_no}' ";
        }


        $start_time = '';
        if (isset($_GET['from_date'])) {
            $start_time = $_GET['from_date'] . ' 00:00:00';
        }
        $end_time = '';
        if (isset($_GET['to_date'])) {
            $end_time = $_GET['to_date'] . ' 23:59:59';
        }
        $order_asc = '';
        if (isset($_GET['order_asc'])) {
            $order_asc = $_GET['order_asc'];
        }
        $order_default = '';
        if (isset($_GET['order_default'])) {
            $order_default = $_GET['order_default'];
        }


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

        $count_query = "SELECT    count(*) as count  FROM {$tblCastingHist} AS CastingHist 
                                                            LEFT JOIN {$tblcarmaster} AS CarMaster
                                                            ON CastingHist.StyleCode = CarMaster.CarCode 
                                                            LEFT JOIN {$tblclassmaster} AS ClassMaster 
                                                            ON CastingHist.Group = ClassMaster.ClassCode 
                                                            " . $query_conditions_sql . "
                                                            
                                                             " . $order_by . " ";
        $result = $db->query($count_query);
        $count_data = array();
        while ($row = mysqli_fetch_object($result)) {
            $total_entries = $row->count;
        }

        echo pagination2($total_entries, 50, $page, 'results.php?' . str_replace('page=' . $page, '', http_build_query($_GET)) . '&page=');
        echo '<div class="pull-right" style="padding-top:20px;">';

        echo '<a class="btn btn-primary" href="results-export.php?' . http_build_query($_GET) . '">Export Data</a>';
        echo '</div>';
        echo '</div>';

        echo '<div class="row">';

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
                                                            
                                                             " . $order_by . " LIMIT  " . (($page - 1) * 50) . ", 50";


        $result = $db->query($query);
        $data = array();
        while ($row = mysqli_fetch_object($result)) {
            array_push($data, $row);
        }


    ?>

        <table class="table table-bordered table-striped  row-border order-column" id="data_table" style="width:100%">
            <thead>
                <?php
                echo "<tr>";
                if (isset($data[0]) && count($data[0]) > 0) {
                    foreach ($data[0] as $key => $column) {
                        if ($key == 'Serialnumber' or $key == 'ENGINE_TYPE' or $key == 'ClassName' or $key == 'inprocess_judgment') {
                            echo '<th  rowspan="2" style="background-color:white;">';
                            echo strtoupper(str_replace("_", " ", $key));
                            echo "</th>";
                        }
                    }
                    echo '<th colspan="5">Casting Process</th>';
                    echo '<th colspan="4">Inspection 1 Process</th>';
                    echo '<th colspan="4">Heat Treatment (Solution) Process</th>';
                    echo '<th colspan="4">Heat Treatment (aging) Process</th>';
                    echo '<th colspan="4">Hardness Measurement</th>';
                    echo '<th colspan="5">WJ tester Process</th>';
                    echo '<th colspan="4">Inspection 2 Process</th>';
                    echo "<tr>";

                    foreach ($data[0] as $key => $column) {
                        if ($key != 'Serialnumber' and $key != 'ENGINE_TYPE' and $key != 'ClassName' and $key != 'inprocess_judgment') {
                            echo '<th>' . strtoupper(str_replace("_", " ", $key)) . '</th>';
                        }
                    }
                    echo "</tr>";
                }

                echo "</tr>";
                ?>
            </thead>
            <tbody>
                <?php
                if (count($data) > 0) {
                    foreach ($data as $row) {
                        echo "<tr>";
                        foreach ($row as $index => $column) {
                            if ($index != "id" && $index != "process_id") {
                                echo '<td >';
                                if ($index == "shot_date") {
                                    $column = convert_date_string($column);
                                } elseif ($index == "inprocess_judgment") {
                                    if ($column == '1') {
                                        $column = 'OK';
                                    }
                                }
                                echo $column;

                                echo "</td>";
                            }
                        }
                        echo "</tr>";
                    }
                }

                ?>
            </tbody>
        </table>
    <?php

    } else if ($action == "retrieve-quality-results") {


        $page = $_GET['page'];

        $query_conditions = array();
        $query_conditions_sql = '';

        $engine_types = array();
        if (isset($_GET['result_target']) && $_GET['result_target'] == 'traceability') {
            if (isset($_GET['engine_type']) && !empty($_GET['engine_type'])) {
                array_push($engine_types, $_GET['engine_type']); // date selected
            }
        } else {
            if (isset($_GET['engine_type']) and count($_GET['engine_type']) > 0) {
                foreach ($_GET['engine_type'] as $et) {
                    //$engine_types[] = substr($et, 0, 3);
                    $engine_types[] = $et;
                }
            }
        }


        $lines = array();
        if (isset($_GET['result_target']) && $_GET['result_target'] == 'traceability') {
            if (isset($_GET['line']) && !empty($_GET['line'])) {
                array_push($lines, $_GET['line']); // date selected
            }
        } else {
            if (isset($_GET['line']) && !empty($_GET['line'])) {
                $lines = $_GET['line']; // date selected
            }
        }

        $groups = array();
        if (isset($_GET['result_target']) && $_GET['result_target'] == 'traceability') {
            if (isset($_GET['group']) && !empty($_GET['group'])) {
                array_push($groups, $_GET['group']); // date selected
            }
        } else {
            if (isset($_GET['group']) && !empty($_GET['group'])) {
                $groups = $_GET['group'];
            }
        }

        $casting_machine_numbers = array();
        if (isset($_GET['result_target']) && $_GET['result_target'] == 'traceability') {
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

        $items = 50;
        if (isset($_GET['result_target']) && $_GET['result_target'] == 'traceability') {
            if (isset($_GET['item'])) {
                $items = $_GET['item'];
            }
        } else {
            if (isset($_GET['item'])) {
                $items = $_GET['item'];
            }
        }

        $shift_no = array();
        if (isset($_GET['result_target']) && $_GET['result_target'] == 'traceability') {
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
            if ($ng_judgements_only == "on")
                $query_conditions[] = " `Inspection result 1` = 2";
        }
        
        /*if (count($engine_types) > 0) { //date selected
            $engine_types_items = array();
            foreach ($engine_types as $engine_type) {
                $engine_types_items[] = "'" . $engine_type . "'";
            }
            // $query_conditions[] = " CastingHist.StyleCode IN ( ".implode(', ', $engine_types_items).") ";
        }*/

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

        if (strlen($select_id_no) > 0 and $select_id_no != '') { //date selected
            if (strpos($select_id_no, ",")) {
                $select_id_nos = explode(",", $select_id_no);
                $ids_query_string = "";
                foreach ($select_id_nos as $id_no) {
                    $id_no = $string = str_replace(' ', '', $id_no);
                    if ($ids_query_string == "") {
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
                if ($key == 0) {
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
                if ($s) {
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

        $query_conditions[] = " `Date/time` >= '{$start_time}' ";
        $query_conditions[] = " `Date/time` <= '{$end_time}' ";

        $order_by = " ";
        if ($order_default == 'true') { //date selected
            $order_by = " ";
        } elseif ($order_asc == 'true') { //date selected

            $order_by = " ORDER BY `Date/time` ASC ";
        } else {
            $order_by = " ORDER BY `Date/time` DESC ";
        }

        $query_conditions[] = " ( `Serial No.4` != '0' AND `Serial No.5` != '0' AND `Serial No.6` != '0' AND `Serial No.7` != '0' AND `Serial No.8` != '0' ) ";
        if (count($query_conditions) > 0) {
            $query_conditions_sql = ' WHERE ' . implode(' AND ', $query_conditions);
        }
        // $query = "SELECT * FROM {$tblCastData} WHERE process_id = {$process_id}  ".$query_conditions_sql." ORDER BY created_at ".$order_by." ";

        $results_query = array();

        foreach ($casting_machine_numbers as $casting_machine_number) {

            $tblLPQuality_[$casting_machine_number] = $tblLPQuality . '_' . $casting_machine_number;

            $combined_serial_no_sql = " CONCAT('HZ',SUBSTR(CHAR(`Serial No.7`),2,1),
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
                                                SUBSTR(CHAR(`Serial No.1`),1,1)) as serial, 
                                                `Date/time` AS `Date/time`, 
                                                `Date/time` AS date_time, 
                                                `Product model` AS `Product model`, 
                                                `Product type` AS `Product type`, 
                                                `Inspection result 1` as `Inspection result 1`,
                                                {$tblLPQuality_[$casting_machine_number]}.* ";

            /*$results_query[] = " (SELECT   " . $combined_serial_no_sql . " FROM {$tblLPQuality_{$casting_machine_number}}
                                                            " . $query_conditions_sql . "
                                                            
                                                             " . $order_by . " LIMIT  " . (($page - 1) * $items) . ", $items) ";*/

            $results_query[] = " (SELECT   " . $combined_serial_no_sql . " FROM {$tblLPQuality_[$casting_machine_number]} 
                                                            " . $query_conditions_sql . ") ";
        }

        $total_entries = 0;
        $data = array();
        $results_union_query = implode(' UNION ', $results_query);
        $result = $db->query($results_union_query);

        while ($row = mysqli_fetch_object($result)) {
            //  print_r($row);
            $date_time = $row->date_time;
            $serial = $row->serial;
            $is = -1;
            foreach ($data as $key => $item) {
                if ($item->serial == $serial) {
                    $is = $key;
                }
            }
            $engine_type = substr($row->serial, 1, 3);
            if($is != -1) {
                unset($data[$is]);
            }
            if(in_array($engine_type, $engine_types) || count($engine_types) == 0)
                array_push($data, $row);
        }

        $total_entries = count($data);
        
        if($order_default == 'true') { 
            usort($data, function($a, $b) {
                $dateA = strtotime($a->date_time);
                $dateB = strtotime($b->date_time);
            
                return $dateB - $dateA; // Sort in descending order
            });
        }
        //replace data
        //workers
        $workers = array();
        $worker_query = "SELECT * FROM {$tblworkermaster}";
        $worker_result = $db->query($worker_query);
        while ($worker_row = mysqli_fetch_object($worker_result)) {

            $workers[$worker_row->WorkerID] = $worker_row->WorkerName;
        }
        //  print_r($workers);
        //worker defect content
        $work_defect_contents = array();
        $work_defect_content_query = "SELECT * FROM {$tbldefectmaster}";
        $work_defect_content_result = $db->query($work_defect_content_query);
        while ($work_defect_content_row = mysqli_fetch_object($work_defect_content_result)) {

            $work_defect_contents[$work_defect_content_row->NG_Code] = $work_defect_content_row->NG_Content;
        }

        //$error_code_masters
        $error_code_masters = array();
        $error_code_master_query = "SELECT * FROM {$tblerrormaster}";
        $error_code_master_result = $db->query($error_code_master_query);
        while ($error_code_master_row = mysqli_fetch_object($error_code_master_result)) {
            $error_code_masters[$error_code_master_row->ErrorCode] = $error_code_master_row->ErrorContent;
        }


        echo '<div class="" style="padding-top:20px;">';
        if (isset($_GET['from_date']) and isset($_GET['to_date'])) { //date selected

            echo '<strong>Time Period From:</strong> ' . $_GET['from_date'] . ' To ' . $_GET['to_date'];

            echo '<br>';
        }
        if (isset($_GET['select_id_no'])) { //date selected

            echo '<strong>Serial No.:</strong> ' . $_GET['select_id_no'];

            echo '<br>';
        }

        if (count($engine_types) > 0) { //date selected

            echo '<strong>Engine types:</strong> ';
            echo implode(', ', $engine_types);
            echo '<br>';
        }
        if (count($lines) > 0) { //date selected

            echo '<strong>Line:</strong> ';
            echo implode(', ', $lines);
            echo '<br>';
        }
        if (count($groups) > 0) { //date selected

            echo '<strong>Group:</strong> ';
            echo implode(', ', $groups);
            echo '<br>';
        }
        if (count($casting_machine_numbers) > 0) { //date selected

            echo '<strong>Casting Machine No.:</strong> ';
            echo implode(', ', $casting_machine_numbers);
            echo '<br>';
        }
        echo '</div>';
        echo pagination2($total_entries, $items, $page, 'results.php?' . str_replace('page=' . $page, '', http_build_query($_GET)) . '&page=');
        echo '<div class="pull-right" style="padding-top:20px;">';
        echo '<a class="btn btn-primary" href="results-export.php?' . http_build_query($_GET) . '">Export Data</a>';
        echo '</div>';
        echo '</div>';


        echo '<div class="row">';
    ?>

        <table class="table table-bordered table-striped  row-border order-column" id="data_table" style="width:100%">
            <thead>
                <?php
                echo "<tr>";
                $ignored_columns = array('id', 'Serial No.1', 'Serial No.2', 'Serial No.3', 'Serial No.4', 'Serial No.5', 'Serial No.6', 'Serial No.7', 'Serial No.8');
                if (isset($data[0]) && $data[0]) {
                    foreach ($data[0] as $key => $column) {
                        if (!in_array($key, $ignored_columns) && $key != "date_time") {
                            if ($key == 'serial' or $key == 'Date/time' or $key == 'Product model' or $key == 'Product type' or $key == 'Inspection result 1') {
                                echo '<th   style="background-color:white;">';
                                echo (str_replace("_", " ", $key));
                                echo "</th>";
                            }

                            if ($key != 'serial' and $key != 'Date/time' and $key != 'Product model' and $key != 'Product type' and $key != 'Inspection result 1') {
                                echo '<th>' . (str_replace("_", " ", $key)) . '</th>';
                            }
                        }
                    }
                }

                echo "</tr>";
                ?>
            </thead>
            <tbody>
                <?php


                if (count($data) > 0) {

                    $start = ($page - 1) * $items;
                    $limit = $start + $items;
                    $count = 0;

                    foreach ($data as $row) {
                        if ($count >= $start && $count < $limit) {
                            echo "<tr>";
                            foreach ($row as $index => $column) {
                                if (!in_array($index, $ignored_columns) && $index != "date_time") {

                                    if ($index == "Inspection result 1") {
                                        if ((int)$column == 1)
                                            $column = "<td style='background-color: green; color: white;'>OK</td>";
                                        else if((int)$column == 2)
                                            $column = "<td style='background-color: red; color: white;'>NG</td>";
                                        else
                                            $column = "<td style='background-color: orange; color: white;'>NO RESULT</td>";
                                        echo $column;
                                    } else if ($index != "id" && $index != "process_id") {
                                        echo '<td >';
                                        if ($index == "shot_date") {
                                            $column = convert_date_string($column);
                                        } elseif ($index == "inprocess_judgment") {
                                            if ($column == '1') {
                                                $column = 'OK';
                                            }
                                        }
                                        if ($index == 'Product model') {
                                            //echo $index
                                            $column = substr($row->serial, 1, 3);
                                        }

                                        if ($index == 'Product type') {
                                            //echo $index
                                            $column = substr($row->serial, 0, 1);
                                        }
                                        if ($index == 'Manufacturing plant') {
                                            //echo $index
                                            $column = 'U';
                                        }

                                        if ($index == 'Manufacturing line') {
                                            //echo $index
                                            $column = substr($row->serial, 5, 1);
                                        }

                                        if ($index == 'Casting shift') {
                                            //echo $index
                                            $column = substr($row->serial, -3, 1);
                                        }
                                        if ($index == 'Machine No.') {
                                            //echo $index
                                            $column = 'EDM-010' . substr($row->serial, 6, 1);
                                        }

                                        if ($index == 'Worker' and isset($workers[$column])) {
                                            //echo $index
                                            $column = $workers[$column];
                                        }

                                        if ($index == 'Work defect content' and isset($work_defect_contents[$column])) {
                                            //echo $index
                                            $column = $work_defect_contents[$column];
                                        }
                                        if ($index == 'Error content 1' and isset($error_code_masters[$column])) {
                                            //echo $index
                                            $column = $error_code_masters[$column];
                                        }

                                        if ($index == "Date/time") {
                                            $column = "<span style='display: none'>" . $column . "</span>" . date('d-m-Y H:i:s', strtotime($column));
                                        }

                                        echo $column;

                                        echo "</td>";
                                    }
                                }
                            }
                            echo "</tr>";
                        }


                        $count++;
                    }
                }

                ?>
            </tbody>
        </table>
    <?php
    }

    pclose(popen("start /B " . "php collect_ng.php", "r"));

    ?>
</div>
<div class="row">
    <p style="font-size:17px;padding-top:5px;">
        Showing <?php echo (($page - 1) * $items + 1); ?> to <?php echo (($page) * $items); ?>
        of <?php echo $total_entries; ?> entries
    </p>
</div>