<?php
require_once("./config/config.php");
require_once("./functions.php");

$action = $_POST['action'];
if ($action == "" || $action == NULL) {
    echo "Action died";
    exit;
}


if ($action == "retrieval") {


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
    /*if($period_no_select=='true'){ //date selected
       if($back_forth =='0'){
        $query_conditions[] = "id_no = '{$select_id_no}'";
       }else{
           $select_id_no_array = array();
           $shots =  (int) $back_forth;
           preg_match_all('!\d+!', $select_id_no, $matches);
           $select_id_no_part_2 = (int) end($matches['0']);
           $select_id_no_part_1 = rtrim($select_id_no, end($matches['0']));

           for($i= - $shots; $i<=$shots;$i++){
              $select_id_no_item = $select_id_no_part_1.($select_id_no_part_2 + $i);
               $select_id_no_array[] = "'{$select_id_no_item}'";
           }

           $query_conditions[] = " id_no IN (".implode(',', $select_id_no_array).') ';



       }
    }*/
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
                if ($key == 'Serialnumber' OR $key == 'ENGINE_TYPE' OR $key == 'ClassName' OR $key == 'inprocess_judgment') {
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
                if ($key != 'Serialnumber' AND $key != 'ENGINE_TYPE' AND $key != 'ClassName' AND $key != 'inprocess_judgment') {
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

} elseif ($action == "retrieve-quality-results") {


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
    /*if($period_no_select=='true'){ //date selected
       if($back_forth =='0'){
        $query_conditions[] = "id_no = '{$select_id_no}'";
       }else{
           $select_id_no_array = array();
           $shots =  (int) $back_forth;
           preg_match_all('!\d+!', $select_id_no, $matches);
           $select_id_no_part_2 = (int) end($matches['0']);
           $select_id_no_part_1 = rtrim($select_id_no, end($matches['0']));

           for($i= - $shots; $i<=$shots;$i++){
              $select_id_no_item = $select_id_no_part_1.($select_id_no_part_2 + $i);
               $select_id_no_array[] = "'{$select_id_no_item}'";
           }

           $query_conditions[] = " id_no IN (".implode(',', $select_id_no_array).') ';



       }
    }*/
    if (count($query_conditions) > 0) {
        $query_conditions_sql = ' WHERE ' . implode(' AND ', $query_conditions);
    }
    // $query = "SELECT * FROM {$tblCastData} WHERE process_id = {$process_id}  ".$query_conditions_sql." ORDER BY created_at ".$order_by." ";

    $query = "SELECT    *  FROM {$tblLPQuality}
                                                            " . $query_conditions_sql . "
                                                            
                                                             " . $order_by . " ";

    //echo $query;

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
                if ($key == 'Serialnumber' OR $key == 'ENGINE_TYPE' OR $key == 'ClassName' OR $key == 'inprocess_judgment') {
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
                if ($key != 'Serialnumber' AND $key != 'ENGINE_TYPE' AND $key != 'ClassName' AND $key != 'inprocess_judgment') {
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

}