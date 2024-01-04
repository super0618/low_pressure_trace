<?php
require_once("./config/config.php");
require_once("./functions.php");
$page_name = 'Results';
$action = $_GET['action'];
if($action == "" || $action == NULL){
    echo "Action died";
    exit;
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $page_name; ?></title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-datepicker3.min.css" rel="stylesheet">
    
    <link href="css/select2.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet"/>
    <link href="css/jquery.dataTables.min.css" rel="stylesheet"/>
    <link href="css/buttons.dataTables.min.css" rel="stylesheet"/>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js')}}"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js')}}"></script>
    <![endif]-->
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/moment.min.js"></script>
    <script src="js/select2.min.js"></script>
    
    

<link href="css/chosen.css" rel="stylesheet"/>
<script src="js/chosen.jquery.js"></script>
    <style>
        h5{
            color: #0e83cd;
            font-size: 16px;
        }

        .filter {
           margin-top:20px;
           padding-top:15px;
            border: 1px solid #e0e0e0; padding: 10px 10px 20px 10px;
        }
    .filter label {
           margin-top:10px;
        }
    th, td { white-space: nowrap;  }
    .even { background-color:white;  }
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
    .btn-xlarge {
    padding: 30px 28px;
    font-size: 22px;
    line-height: normal;
    -webkit-border-radius: 8px;
       -moz-border-radius: 8px;
            border-radius: 8px;
    }
    ul.pagination{
    
	margin:0px;
	padding:20px 0 0 0;
	/*height:100%;*/
	overflow:hidden;
	font:12px 'Tahoma';
	list-style-type:none;	
}

ul.pagination li.details{
    padding:7px 10px 7px 10px;
    font-size:14px;
}

ul.pagination li.dot{padding: 3px 0;}

ul.pagination li{
	float:left;
	margin:0px;
	padding:0px;
	margin-left:5px;
}

ul.pagination li:first-child{
	margin-left:0px;
}

ul.pagination li a{
	color:black;
	display:block;
	text-decoration:none;
	padding:7px 10px 7px 10px;
}
ul.pagination li a.current{
	background-color:#ddd;
}
ul.pagination li a img{
	border:none;
}
	
</style>
</head>

<body onload="startTime()">
    <div class="container">
        <div class="row">
            
<?php


if($action=="Retrieval") {
    
    
    
    
   $page = $_GET['page'];
   
            $query_conditions= array();
            $query_conditions_sql = '';
            $engine_types = array();
            
            if(isset($_GET['engine_type'])){
            $engine_types = $_GET['engine_type'];
            }
            $lines = array();
            if(isset($_GET['line'])){
            $lines = $_GET['line']; // date selected
            }
            $groups = array();
            if(isset($_GET['group'])){
            $groups = $_GET['group'];
            }
            $casting_machine_numbers = array();
            if(isset($_GET['casting_machine_number'])){
            $casting_machine_numbers = $_GET['casting_machine_number'];
            }
            $items = array();
           if(isset($_GET['item'])){
            $items = $_GET['item'];
           }
           $shift_no = array();
           if(isset($_GET['shift_no'])){
            $shift_no = $_GET['shift_no'];
           }
           $select_id_no = '';
           if(isset($_GET['select_id_no'])){
            $select_id_no = $_GET['select_id_no'];
           }
           $ng_judgements_only = '';
           if(isset($_GET['ng_judgements_only'])){
            $ng_judgements_only = $_GET['ng_judgements_only'];
           }
            if(count($engine_types)>0){ //date selected
               $engine_types_items = array();
               foreach($engine_types as $engine_type){
                   $engine_types_items[] =  "'".$engine_type."'";
               }
                $query_conditions[] = " CastingHist.StyleCode IN ( ".implode(', ', $engine_types_items).") ";
            }
            
            if(count($lines)>0){ //date selected
               $lines_items = array();
               foreach($lines as $line){
                   $lines_items[] =  "'".$line."'";
               }
                $query_conditions[] = " CastingHist.LineNum IN ( ".implode(', ', $lines_items).") ";
            }
            if(count($groups)>0){ //date selected
            $groups_items = array();
               foreach($groups as $group){
                   $groups_items[] =  "'".$group."'";
               }
                $query_conditions[] = " CastingHist.Group IN ( ".implode(', ', $groups_items).") ";
            }
            
            if(strlen($select_id_no)>0 AND $select_id_no!=''){ //date selected
               
                $query_conditions[] = " CastingHist.Serialnumber= '{$select_id_no}' ";
            }
            
            //$start_time = get_start_end_time($_GET['from_date'], $shift)['start'];
            //$end_time = get_start_end_time($_GET['to_date'], $shift)['end'];
            $start_time = '';
            if(isset($_GET['from_date'])){
            $start_time = $_GET['from_date'].' 00:00:00';
            }
            $end_time = '';
            if(isset($_GET['to_date'])){
            $end_time = $_GET['to_date'].' 23:59:59';
            }
            $order_asc = '';
            if(isset($_GET['order_asc'])){
            $order_asc = $_GET['order_asc'];
            }
            $order_default = '';
            if(isset($_GET['order_default'])){
            $order_default = $_GET['order_default'];
            }
            
            
               
                $query_conditions[] = " CastingHist.ProductTime >= '{$start_time}' ";
                $query_conditions[] = " CastingHist.ProductTime <= '{$end_time}' ";
           
            if($shift_no!='unspecified'){ //shift selected
               
              //  $query_conditions[] = "shift = '{$shift_no}'";
            }
            
            $order_by =  " ";
            if($order_default=='true'){ //date selected
               
                $order_by =  " ";
            }elseif($order_asc=='true'){ //date selected
               
                $order_by =  " ORDER BY CastingHist.ProductTime ASC ";
            }else{
                $order_by =  " ORDER BY CastingHist.ProductTime DESC ";
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
            if(count($query_conditions)>0){
                $query_conditions_sql = ' WHERE '.implode(' AND ', $query_conditions);
            }
           // $query = "SELECT * FROM {$tblCastData} WHERE process_id = {$process_id}  ".$query_conditions_sql." ORDER BY created_at ".$order_by." ";
           
           $count_query =  "SELECT    count(*) as count  FROM {$tblCastingHist} AS CastingHist 
                                                            LEFT JOIN {$tblcarmaster} AS CarMaster
                                                            ON CastingHist.StyleCode = CarMaster.CarCode 
                                                            LEFT JOIN {$tblclassmaster} AS ClassMaster 
                                                            ON CastingHist.Group = ClassMaster.ClassCode 
                                                            ".$query_conditions_sql."
                                                            
                                                             ".$order_by." ";
            $result = $db->query($count_query);
            $count_data = array();
            while($row = mysqli_fetch_object($result)) {
                $total_entries = $row->count;
            }
           
            echo pagination($total_entries,50,$page,'results.php?'. str_replace('page='.$page, '', http_build_query($_GET)).'&page=');
            echo '<div class="pull-right" style="padding-top:20px;">';
            echo '<a class="btn btn-primary" href="results-export.php?'.http_build_query($_GET).'">Export Data</a>';
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
                                                            ".$query_conditions_sql."
                                                            
                                                             ".$order_by." LIMIT  ".(($page -  1)*50).", 50";
            
         
         
           
            $result = $db->query($query);
            $data = array();
            while($row = mysqli_fetch_object($result)) {
                array_push($data, $row);
            }
            
            
            ?>

            <table class="table table-bordered table-striped  row-border order-column" id="data_table"   style="width:100%">
                <thead>
                <?php
                echo "<tr>";
                if(isset($data[0]) && count($data[0]) > 0) {
                    foreach ($data[0] as $key => $column) {
                        if($key=='Serialnumber' OR $key=='ENGINE_TYPE' OR $key=='ClassName' OR $key=='inprocess_judgment') {
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
                        if($key !='Serialnumber' AND $key!='ENGINE_TYPE' AND $key!='ClassName' AND $key !='inprocess_judgment') {
                            echo '<th>'.strtoupper(str_replace("_", " ", $key)).'</th>';
                        }
                         
                    }
                    echo "</tr>";
                }

                echo "</tr>";
                ?>
                </thead>
                <tbody>
                <?php
                if(count($data) > 0) {
                    foreach ($data as $row){
                        echo "<tr>";
                        foreach ($row as $index => $column) {
                            if($index != "id" && $index != "process_id") {
                                echo '<td >';
                                if($index == "shot_date") {
                                    $column = convert_date_string($column);
                                }elseif($index == "inprocess_judgment") {
                                    if($column == '1'){
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
    
}elseif($action=="retrieve-quality-results") {
    
    
    
    
   $page = $_GET['page'];
   
            $query_conditions= array();
            $query_conditions_sql = '';
            $engine_types = array();
            
            if(isset($_GET['engine_type'])){
            $engine_types = substr($_GET['engine_type'], 0, 3);
            }
            $lines = array();
            if(isset($_GET['line'])){
            $lines = $_GET['line']; // date selected
            }
            $groups = array();
            if(isset($_GET['group'])){
            $groups = $_GET['group'];
            }
            $casting_machine_numbers = array();
            if(isset($_GET['casting_machine_number'])){
            $casting_machine_numbers = $_GET['casting_machine_number'];
            }
            $items = array();
           if(isset($_GET['item'])){
            $items = $_GET['item'];
           }
           $shift_no = array();
           if(isset($_GET['shift_no'])){
            $shift_no = $_GET['shift_no'];
           }
           $select_id_no = '';
           if(isset($_GET['select_id_no'])){
            $select_id_no = $_GET['select_id_no'];
           }
           $ng_judgements_only = '';
           if(isset($_GET['ng_judgements_only'])){
            $ng_judgements_only = $_GET['ng_judgements_only'];
           }
            if(count($engine_types)>0){ //date selected
               $engine_types_items = array();
               foreach($engine_types as $engine_type){
                   $engine_types_items[] =  "'".$engine_type."'";
               }
               // $query_conditions[] = " CastingHist.StyleCode IN ( ".implode(', ', $engine_types_items).") ";
            }
            
            if(count($lines)>0){ //date selected
               $lines_items = array();
               foreach($lines as $line){
                   $lines_items[] =  "'".$line."'";
               }
                $query_conditions[] = " manufacturing_line IN ( ".implode(', ', $lines_items).") ";
            }
            if(count($groups)>0){ //date selected
            $groups_items = array();
               foreach($groups as $group){
                   $groups_items[] =  "'".$group."'";
               }
                $query_conditions[] = " casting_group IN ( ".implode(', ', $groups_items).") ";
            }
            
            if(strlen($select_id_no)>0 AND $select_id_no!=''){ //date selected
               
               // $query_conditions[] = " CastingHist.Serialnumber= '{$select_id_no}' ";
            }
            
            //$start_time = get_start_end_time($_GET['from_date'], $shift)['start'];
            //$end_time = get_start_end_time($_GET['to_date'], $shift)['end'];
            $start_time = '';
            if(isset($_GET['from_date'])){
            $start_time = $_GET['from_date'].' 00:00:00';
            }
            $end_time = '';
            if(isset($_GET['to_date'])){
            $end_time = $_GET['to_date'].' 23:59:59';
            }
            $order_asc = '';
            if(isset($_GET['order_asc'])){
            $order_asc = $_GET['order_asc'];
            }
            $order_default = '';
            if(isset($_GET['order_default'])){
            $order_default = $_GET['order_default'];
            }
            
            
               
                $query_conditions[] = " date_and_time >= '{$start_time}' ";
                $query_conditions[] = " date_and_time <= '{$end_time}' ";
           
            if($shift_no!='unspecified'){ //shift selected
               
              //  $query_conditions[] = "shift = '{$shift_no}'";
            }
            
            $order_by =  " ";
            if($order_default=='true'){ //date selected
               
                $order_by =  " ";
            }elseif($order_asc=='true'){ //date selected
               
                $order_by =  " ORDER BY date_and_time ASC ";
            }else{
                $order_by =  " ORDER BY date_and_time DESC ";
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
            if(count($query_conditions)>0){
                $query_conditions_sql = ' WHERE '.implode(' AND ', $query_conditions);
            }
           // $query = "SELECT * FROM {$tblCastData} WHERE process_id = {$process_id}  ".$query_conditions_sql." ORDER BY created_at ".$order_by." ";
           
           
           $tblLPQuality =  $tblLPQuality.'_'.$casting_machine_numbers['0'];
           $count_query =  "SELECT    count(*) as count  FROM {$tblLPQuality} 
                                                            ".$query_conditions_sql."
                                                             ";
            $result = $db->query($count_query);
            $count_data = array();
            while($row = mysqli_fetch_object($result)) {
                $total_entries = $row->count;
            }
           
            echo pagination($total_entries,50,$page,'results.php?'. str_replace('page='.$page, '', http_build_query($_GET)).'&page=');
            echo '<div class="pull-right" style="padding-top:20px;">';
            echo '<a class="btn btn-primary" href="results-export.php?'.http_build_query($_GET).'">Export Data</a>';
            echo '</div>';
            echo '</div>';
            echo '<div class="row">';
            
            $query = "SELECT    *  FROM {$tblLPQuality} 
                                                            ".$query_conditions_sql."
                                                            
                                                             ".$order_by." LIMIT  ".(($page -  1)*50).", 50";
            
         
         
           
            $result = $db->query($query);
            $data = array();
            while($row = mysqli_fetch_object($result)) {
                array_push($data, $row);
            }
            
            
            ?>

            <table class="table table-bordered table-striped  row-border order-column" id="data_table"   style="width:100%">
                <thead>
                <?php
                echo "<tr>";
                if(isset($data[0]) && count($data[0]) > 0) {
                    foreach ($data[0] as $key => $column) {
                        if($key=='serial_no' OR $key=='date_and_time' OR $key=='product_model' OR $key=='product_type') {
                            echo '<th   style="background-color:white;">';
                            echo strtoupper(str_replace("_", " ", $key));
                            echo "</th>";
                           
                            
                        }
                         
                    }
                    
                   // echo "<tr>";
                    
                    foreach ($data[0] as $key => $column) {
                        if($key !='serial_no' AND $key!='date_and_time' AND $key!='product_model' AND $key !='product_type') {
                            echo '<th>'.strtoupper(str_replace("_", " ", $key)).'</th>';
                        }
                         
                    }
                    //echo "</tr>";
                }

                echo "</tr>";
                ?>
                </thead>
                <tbody>
                <?php
                if(count($data) > 0) {
                    foreach ($data as $row){
                        echo "<tr>";
                        foreach ($row as $index => $column) {
                            if($index != "id" && $index != "process_id") {
                                echo '<td >';
                                if($index == "shot_date") {
                                    $column = convert_date_string($column);
                                }elseif($index == "inprocess_judgment") {
                                    if($column == '1'){
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

?>
</div>
<div class="row">
    <p style="font-size:17px;padding-top:5px;">
    Showing <?php echo (($page -  1)*50 + 1); ?> to <?php echo (($page)*50); ?> of <?php echo $total_entries; ?> entries 
    </p>
</div>
</div>
<style>

#overlay{	
	position: fixed;
	top: 0;
	z-index: 100;
	width: 100%;
	height:100%;
	display: none;
	background: rgba(0,0,0,0.6);
}
.cv-spinner {
	height: 100%;
	display: flex;
	justify-content: center;
	align-items: center;  
}
.spinner {
	width: 40px;
	height: 40px;
	border: 4px #ddd solid;
	border-top: 4px #2e93e6 solid;
	border-radius: 50%;
	animation: sp-anime 0.8s infinite linear;
}
@keyframes sp-anime {
	0% { 
		transform: rotate(0deg); 
	}
	100% { 
		transform: rotate(359deg); 
	}
}
.is-hide{
	display:none;
}
</style>

<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-datepicker.min.js"></script>
<script src="js/custom.js"></script>

<script src="js/FileSaver.min.js"></script>
<script src="js/Blob.min.js"></script>
<script src="js/xls.core.min.js"></script>





<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.fixedColumns.min.js"></script>

<script src="js/dataTables.buttons.min.js"></script>
<script src="js/buttons.flash.min.js"></script>
<script src="js/jszip.min.js"></script>
<script src="js/pdfmake.min.js"></script>
<script src="js/vfs_fonts.js"></script>
<script src="js/buttons.html5.min.js"></script>
<script src="js/buttons.print.min.js"></script>









<script>
    $(document).ready(function () {

        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
        });

       
        
        $("#retrieval").on('click', function () {
            $("#overlay").fadeIn(300);
            
            var period_no_radio = $("#period_no_radio").is(':checked');
            var period_no_select = $("#period_no_select").is(':checked');
            var shift_number_check =  $("#shift_number_check").is(':checked');
            var shift_no = $("#shift_no").val();
            var order_asc = $("#order_asc").is(':checked');
            var order_desc = $("#order_desc").is(':checked');
            var order_default = $("#order_default").is(':checked');
            var process_id = $("#process_name").val();
            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
            var select_id_no = $("#select_id_no").val();
            var back_forth = $('#back_forth').val();
            
            
            
            var engine_type = $('#engine_type').val();
            var line = $('#line').val();
            var group = $('#group').val();
            var casting_machine_number = $('#casting_machine_number').val();
            var item = $('#item').val();
            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
            var shift_no = $("#shift_no").val();
            var select_id_no = $("#select_id_no").val();
            var ng_judgements_only = $('#ng_judgements_only').is(':checked');
            var order_asc = $("#order_asc").is(':checked');
            var order_desc = $("#order_desc").is(':checked');
            $.ajax({
                url: "actions.php",
                method: "post",
                data: {
                        action:"retrieval", 
                        engine_type: engine_type,
                        period_no_radio: period_no_radio,
                        line: line,
                        group: group,
                        casting_machine_number: casting_machine_number,
                        item: item,
                        order_default: order_default,
                        shift_no: shift_no,
                        from_date:from_date, 
                        to_date:to_date,
                        select_id_no:select_id_no,
                        ng_judgements_only: ng_judgements_only,
                        order_asc: order_asc,
                        order_desc: order_desc
                    
                },
                dataType: "HTML"
            }).done(function (html) {
                $("#data_table_container").html(html);
                
                setTimeout(function(){
				$("#overlay").fadeOut(300);
			},500);   
			
                var table = $('#data_table').DataTable( {
        scrollY:        "500px",
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        fixedColumns:   {
            leftColumns: 1
        },
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel'
        ]
    } );
             
                
            });
            
        });
        
         $("#reset").on('click', function () {
            $("#overlay").fadeIn(300);
            $('#engine_type').prop('selectedIndex',0);
             $('#line').prop('selectedIndex',0);
             $('#group').prop('selectedIndex',0);
             $('#casting_machine_number').prop('selectedIndex',0);
             $('#item').prop('selectedIndex',0);
             $('#shift_no').prop('selectedIndex',0);
             $("#select_id_no").val("");
             $('#ng_judgements_only').prop('checked', false);
             
             $('#order_default').prop('checked', true);
             $('#order_desc').prop('checked', false);
             $('#ng_judgementorder_ascs_only').prop('checked', false);
             
             //date
             var currentDate = new Date();
            var dd = currentDate.getDate();
            var mm = currentDate.getMonth()+1; //January is 0!
            var yyyy = currentDate.getFullYear();

            if(dd<10) {
                dd = '0'+dd
            } 

            if(mm<10) {
                mm = '0'+mm
            } 

            currentDate = yyyy + '-' + mm + '-' + dd;
             $("#from_date").val(currentDate);
             $("#to_date").val(currentDate);
             
             
            var period_no_radio = $("#period_no_radio").is(':checked');
            var period_no_select = $("#period_no_select").is(':checked');
            var shift_number_check =  $("#shift_number_check").is(':checked');
            var shift_no = $("#shift_no").val();
            var order_asc = $("#order_asc").is(':checked');
            var order_desc = $("#order_desc").is(':checked');
            var order_default = $("#order_default").is(':checked');
            var process_id = $("#process_name").val();
            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
            var select_id_no = $("#select_id_no").val();
            var back_forth = $('#back_forth').val();
            
            
            
            var engine_type = $('#engine_type').val();
            var line = $('#line').val();
            var group = $('#group').val();
            var casting_machine_number = $('#casting_machine_number').val();
            var item = $('#item').val();
            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
            var shift_no = $("#shift_no").val();
            var select_id_no = $("#select_id_no").val();
            var ng_judgements_only = $('#ng_judgements_only').is(':checked');
            var order_asc = $("#order_asc").is(':checked');
            var order_desc = $("#order_desc").is(':checked');
            $.ajax({
                url: "actions.php",
                method: "post",
                data: {
                        action:"retrieval", 
                        engine_type: engine_type,
                        period_no_radio: period_no_radio,
                        line: line,
                        group: group,
                        casting_machine_number: casting_machine_number,
                        item: item,
                        order_default: order_default,
                        shift_no: shift_no,
                        from_date:from_date, 
                        to_date:to_date,
                        select_id_no:select_id_no,
                        ng_judgements_only: ng_judgements_only,
                        order_asc: order_asc,
                        order_desc: order_desc
                    
                },
                dataType: "HTML"
            }).done(function (html) {
                $("#data_table_container").html(html);
                
                setTimeout(function(){
				$("#overlay").fadeOut(300);
			},500);   
			
                var table = $('#data_table').DataTable( {
        scrollY:        "500px",
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        fixedColumns:   {
            leftColumns: 1
        },
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel'
        ]
    } );
             
                
            });
            
        });

        
    });

    
    
    $(document).ready(function() {
    var table = $('#data_table').DataTable( {
        scrollY:        "700px",
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        searching: false,
        bInfo : false,
        fixedColumns:   {
            leftColumns: 4
        }
    } );
} );





</script>


</body>
</html>