<?php
require_once("./config/config.php");
require_once("functions.php");

$page_name = "Cast Trace";

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
    
   // $re = import_db($graph_date, $default_process, $shift);
  //  echo $re;
}


?>
<!DOCTYPE html>
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


    <style>
        h5{
            color: #0e83cd;
            font-size: 16px;
        }

        .filter {
            min-height: 390px;
            border: 1px solid #e0e0e0; padding: 10px 10px 20px 10px;
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
</style>
</head>

<body onload="startTime()">
<?php require_once("header.php"); ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h4>Filter</h4>
        </div>
        <form id="filter_form" class="form-inline">
            <div class="col-md-3 filter">
                <h5 style="font-weight: bold;">Mode Selection</h5>
                <div class="col-md-12">
                    <label class="col-md-6"><input type="radio" name="mode_selection_1" checked value="data_display" id="data_display">&nbsp;&nbsp;Data display</label>
                    <label class="col-md-6"><input type="radio" name="mode_selection_1" value="street_display" id="street_display">&nbsp;&nbsp;Street display</label>
                </div>
                <div class="col-md-12">
                    <label class="col-md-6"><input type="radio" name="mode_selection_2" checked value="process_unit" id="process_unit">&nbsp;&nbsp;Process unit</label>
                    <label class="col-md-6"><input type="radio" name="mode_selection_2" value="unification" id="unification">&nbsp;&nbsp;Unification</label>
                </div>
                <div class="col-md-12" style="height: 20px;"></div>
                <h5 style="font-weight: bold;">Process name</h5>
                <div class="col-md-12">
                    <select class="form-control" id="process_name" name="process_name">
                        <option value="0" selected disabled>[SELECT PROCESS]</option>
                        <?php
                        $query = "SELECT * FROM {$tblCastProcesses}";
                        $result = $db->query($query);
                        while($row=mysqli_fetch_object($result)){
                            if($default_process == $row->id)
                                echo '<option value="'.$row->id.'" selected>'.$row->process_name.'</option>';
                            else
                                echo '<option value="'.$row->id.'">'.$row->process_name.'</option>';

                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-md-4 filter">
                <form class="form-inline">
                    <h5 style="font-weight: bold;">Period or IDNo</h5>
                    <div class="form-group col-md-12">
                        <label><input type="radio" name="period_no" checked id="period_no_radio">&nbsp;&nbsp;period</label>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="text" id="from_date" name="from_date" class="form-control datepicker" value="<?php echo date("Y-m-d", strtotime($graph_date)); ?>">
                        <input type="text" id="to_date" name="to_date" class="form-control datepicker" value="<?php echo date("Y-m-d", strtotime($graph_date)); ?>">
                    </div>
                    <div class="form-group col-md-12" style="margin-top: 10px;">
                        <label><input type="checkbox" name="shift_number" id="shift_number_check">&nbsp;&nbsp;Shift</label>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <select class="form-control" disabled id="shift_no" name="shift_no">
                            <?php
                            $shift_no = str_replace("shift", "",  $shift);
                            if($shift_no == 1) {
                                echo '<option value="1" selected>1</option>';
                                echo '<option value="2">2</option>';
                                echo '<option value="3">3</option>';
                            } elseif($shift_no == 2) {
                                echo '<option value="1">1</option>';
                                echo '<option value="2" selected>2</option>';
                                echo '<option value="3">3</option>';
                            }elseif($shift_no == 3) {
                                echo '<option value="1">1</option>';
                                echo '<option value="2" >2</option>';
                                echo '<option value="3" selected>3</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-12" style="margin-top: 10px;">
                        <label class="col-md-6"><input type="radio" name="period_mode" value="id_no">&nbsp;&nbsp;Casting day(IDNo)</label>
                        <label class="col-md-6"><input type="radio" name="period_mode" checked value="measurement">&nbsp;&nbsp;Casting day(Measurement date)</label>
                        <label class="col-md-6"><input type="radio" name="period_mode" value="shipment">&nbsp;&nbsp;Shipment day</label>
                        <label class="col-md-6"><input type="radio" name="period_mode" value="self_process">&nbsp;&nbsp;Measurement date(Self-process)</label>
                    </div>

                    <div class="form-group col-md-12" style="margin-top: 10px;">
                        <label><input type="radio" name="period_no" id="period_no_select">&nbsp;&nbsp;ID No</label>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        
                        <input type="text" name="select_id_no" style="width: 200px;" id="select_id_no" value="">
                    </div>

                    <div class="form-group col-md-12" style="margin-top: 10px;">
                        <label>The back and forth </label>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <select class="form-control" style="width: 132px;" id="back_forth" disabled name="back_forth">
                            <option>0</option>
                            <option>10</option>
                            <option>50</option>
                            <option>100</option>
                        </select>&nbsp; <span style="font-weight: bold">Shot</span>
                    </div>

                    <div class="col-md-12" style="height: 20px;"></div>
                    <h5 style="font-weight: bold;">Order of display</h5>

                    <div class="form-group col-md-12" style="margin-top: 10px;">
                        <label class="col-md-6"><input type="radio" name="order" id="order_asc" checked>&nbsp;&nbsp;Positive the order</label>
                        <label class="col-md-6"><input type="radio" name="order" id="order_desc">&nbsp;&nbsp;Reverse order</label>
                    </div>
                </form>

            </div>


            <div class="col-md-5 filter">
                <h5 style="font-weight: bold;">Additional Condition</h5>
                <div class="col-md-6">
                    <label><input type="checkbox" id="condition_none" name="condition_none">&nbsp;&nbsp;Condition none</label>
                    <table class="table table-bordered" id="process_lines_table">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Process Name</th>
                            <th>Line Name</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $query = "SELECT * FROM {$tblLines} WHERE process_id = {$default_process}";
                        $result = $db->query($query);
                        while($row = mysqli_fetch_object($result)) {
                            echo '<tr>';
                            echo '<td><input type="checkbox" checked class="lines" name="line'.$row->id.'" id="line'.$row->id.'" value="1"></td>';
                            echo '<td>'.$default_process_name.'</td>';
                            echo '<td>'.$row->line.'</td>';
                        }
                        ?>

                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <form class="form-inline">
                        <div class="form-group col-md-12">
                            <label class="col-md-6"><input class="other_condition" type="checkbox" id="product_type_check" name="product_type_check">&nbsp; Product Type</label>
                            &nbsp;&nbsp;&nbsp;
                            <select class="form-control col-md-6" style="width: 120px" id="product_type" name="product_type" disabled>
                                <option></option>
                                <option></option>
                                <option></option>
                            </select>
                        </div>
                        <div class="form-group col-md-12" style="margin-top: 10px;">
                            <label class="col-md-6"><input class="other_condition" type="checkbox" id="unique_type_check" name="unique_type_check">&nbsp; Unique Type</label>
                            &nbsp;&nbsp;&nbsp;
                            <select class="form-control col-md-6" style="width: 120px" name="unique_type" id="unique_type" disabled>
                                <option></option>
                                <option></option>
                                <option></option>
                            </select>
                        </div>
                        <div class="form-group col-md-12" style="margin-top: 10px;">
                            <label class="col-md-6"><input class="other_condition" type="checkbox" id="general_purpose_check" name="general_purpose_check">&nbsp; General Purpose Type</label>
                            &nbsp;&nbsp;&nbsp;
                            <select class="form-control col-md-6" style="width: 120px" id="general_purpose" name="general_purpose" disabled>
                                <option></option>
                                <option></option>
                                <option></option>
                            </select>
                        </div>
                        <div class="form-group col-md-12" style="margin-top: 10px;">
                            <label class="col-md-6"><input class="other_condition" type="checkbox"  id="machine_type_check" name="machine_type_check">&nbsp; Machine Type</label>
                            &nbsp;&nbsp;&nbsp;
                            <select class="form-control col-md-6" style="width: 120px" id="machine_type" name="machine_type" disabled>
                                <option></option>
                                <option></option>
                                <option></option>
                            </select>
                        </div>
                        <div class="form-group col-md-12" style="margin-top: 10px;">
                            <label class="col-md-6"><input class="other_condition" type="checkbox" id="condition_coming_check" name="condition_coming_check">&nbsp; Condition coming off</label>
                            &nbsp;&nbsp;&nbsp;
                            <select class="form-control col-md-6" style="width: 120px" id="condition_coming" name="condition_coming" disabled>
                                <option></option>
                                <option></option>
                                <option></option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-12" style="padding: 20px;">
                <button class="btn btn-primary pull-right btn-xlarge" style="margin-left: 20px;" id="retrieval">Retrieval</button>
                <a href="settings.php" class="btn btn-primary pull-right" style="cursor: pointer;">Display Item setting</a>
            </div>

            <input type="hidden" id="graph_date" name="graph_date" value="<?php echo $graph_date; ?>">


        </form>
    </div>

    <div class="row">
        <div class="col-md-12" id="data_table_container">
            <?php
            $time_set = get_start_end_time($graph_date, $shift);
            $start_time = $time_set['start'];
            $end_time = $time_set['end'];
			

            //$query = "SELECT * FROM {$tblCastData} WHERE process_id = {$default_process} AND created_at >= '{$start_time}' AND created_at <= '{$end_time}' ORDER BY created_at ASC ";
            $query = "SELECT * FROM {$tblCastData} WHERE process_id = {$default_process}  AND created_at <= '{$end_time}' ORDER BY created_at DESC LIMIT 0,50";
            
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
                        if($key != "id" && $key != "process_id") {
                            echo '<th '.($key=='id_no'?'style="background-color:white;"':'').'>';
                            echo strtoupper(str_replace("_", " ", $key));
                            echo "</th>";
                        }
                    }
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
            
        </div>
    </div>
</div>

<div class="my-alert alert alert-success" id="success-alert" style="display: none">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <strong id="alert_title">Success! </strong>
    <span id="alert_message">Saved successfully.</span>
</div>

<div class="my-alert alert alert-danger" id="fault-alert" style="display: none">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <strong id="fault_title">Fail! </strong>
    <span id="fault_message">Saved failed.</span>
</div>

</body>
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

       

        read_id_no();

        $("#street_display").on('click', function () {
            $("#process_unit").attr('disabled', true);
            $("#process_unit").attr('checked', false);
            $("#unification").attr('checked', true);
            $.ajax({
                url: "actions.php",
                method: "post",
                data: {action:"unification_process_lines_table"},
                dataType: "HTML"
            }).done(function (html) {
                $("#process_lines_table").find('tbody').html(html);
            });
        });

        $("#process_unit").on('click', function () {
            var process_id = $("#process_name").val();
            $.ajax({
                url: "actions.php",
                method: "post",
                data: {action:"process_unit_process_lines_table", process_id:process_id},
                dataType: "HTML"
            }).done(function (html) {
                $("#process_lines_table").find('tbody').html(html);
            });
        });
        $("#retrieval").on('click', function () {
            
            var period_no_radio = $("#period_no_radio").is(':checked');
            var period_no_select = $("#period_no_select").is(':checked');
            var shift_number_check =  $("#shift_number_check").is(':checked');
            var shift_no = $("#shift_no").val();
            var order_asc = $("#order_asc").is(':checked');
            var order_desc = $("#order_desc").is(':checked');
            var process_id = $("#process_name").val();
            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
            var select_id_no = $("#select_id_no").val();
            var back_forth = $('#back_forth').val();
            $.ajax({
                url: "actions.php",
                method: "post",
                data: {
                        action:"retrieval", 
                        process_id: process_id,
                        period_no_radio: period_no_radio,
                        shift_number_check: shift_number_check,
                        shift_no: shift_no,
                        from_date:from_date, 
                        to_date:to_date,
                        period_no_select:period_no_select, 
                        select_id_no:select_id_no,
                        back_forth: back_forth,
                        order_asc: order_asc,
                        order_desc: order_desc
                    
                },
                dataType: "HTML"
            }).done(function (html) {
                $("#data_table_container").html(html);
                
                
                var table = $('#data_table').DataTable( {
        scrollY:        "500px",
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        fixedColumns:   {
            leftColumns: 1
        }
    } );
                
                
            });
            
        });

        $("#unification").on('click', function () {
            $.ajax({
                url: "actions.php",
                method: "post",
                data: {action:"unification_process_lines_table"},
                dataType: "HTML"
            }).done(function (html) {
                $("#process_lines_table").find('tbody').html(html);
            });
        });

        $("#data_display").on('click', function () {
            $("#process_unit").attr('disabled', false);
        });

        $("#period_no_select").on('click', function () {
            $("#from_date").attr('disabled', true);
            $("#to_date").attr('disabled', true);

            $("#select_id_no").attr('disabled', false);
            $("#back_forth").attr('disabled', false);

        });

        $("#period_no_radio").on('click', function () {
            $("#from_date").attr('disabled', false);
            $("#to_date").attr('disabled', false);

            $("#select_id_no").attr('disabled', true);
            $("#back_forth").attr('disabled', true);
        });

        $('#shift_number_check').change(function() {
            if($(this).is(":checked")) {
               $("#shift_no").attr('disabled', false);
            } else{
                $("#shift_no").attr('disabled', true);
            }
        });

        $('#condition_none').change(function() {
            if($(this).is(":checked")) {
                $("#process_lines_table").find('input').attr('disabled', true);
            } else{
                $("#process_lines_table").find('input').attr('disabled', false);
            }
        });

        $('.other_condition').change(function() {
            var div = $(this).closest('div');
            if($(this).is(":checked")) {
                div.find('select').attr('disabled', false);
            } else{
                div.find('select').attr('disabled', true);
            }
        });

    });

    function read_id_no() {
        var process_id = $("#process_name").val();
        $.ajax({
            url: "actions.php",
            method: "post",
            data: {action:"read_id_no_options", process_id:process_id},
            dataType: "HTML"
        }).done(function (html) {
            //$("#select_id_no").html(html);
        });
    }
    
    $(document).ready(function() {
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
} );
</script>

</html>