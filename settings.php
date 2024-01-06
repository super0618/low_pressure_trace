<?php
require_once("./config/config.php");
require_once("functions.php");
$page_name = "Setting";

$query = "SELECT * FROM {$tblCastDisplay}";
$result = $db->query($query);
$display_setting = array();
while($row = mysqli_fetch_object($result)){
    $display_setting[$row->name] = $row->tag_address;
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cast Trace Settings</title>
    <!-- Fonts -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="css/select2.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet"/>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js')}}"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js')}}"></script>
    <![endif]-->
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/moment.min.js"></script>
    <script src="js/select2.min.js"></script>
</head>
<style>
    h4{
        font-weight: bold;
    }
</style>
<body>
<?php require_once ("header.php"); ?>
<div class="container">
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-12">
            <h4 style="background-color: #0c99d6; color: #fff; padding: 10px;">Shift Details</h4>
            <form method="post" id="shift_setting_form">
            <?php
            $query = "SELECT timeset FROM {$tblShiftDetail}";
            $result = $db->query($query);
            $timeSets = mysqli_fetch_all($result, MYSQLI_BOTH);

            echo '<table class="table">
                    <thead>
                    <tr>
                        <th style="width: 90px;border-top: 0px;"></th>';

            for($i=0; $i<count($week); $i++ ) {
                echo '<th colspan="2" style="border-top: 0px;">'.$week[$i].'</th>';
            }

            echo '</tr>
                  </thead>
                  <tbody>
                  <tr>
                  <td></td>';
            for($i=0; $i<count($week); $i++ ) {
                echo '<td>Start</td>';
                echo '<td>End</td>';
            }

            echo '</tr>';

            for($k=1; $k<4; $k++){
                echo "<tr>";
                echo "<td>Shift ".$k."</td>";
                for($i=0; $i<7; $i++ ) {
                    $times = array();
                    if(isset($timeSets[$i])) {
                        $timeSet = $timeSets[$i]['timeset'];
                        $times = json_decode($timeSet, true);
                    }

                    if(count($times) > 0 && $times[$k]['start']) {
                        $start = $times[$k]['start'];
                    } else {
                        $start = "00:00";
                    }

                    if(count($times) > 0 && $times[$k]['start']) {
                        $end = $times[$k]['end'];
                    } else {
                        $end = "00:00";
                    }

                    echo '<td>';
                    echo '<div class="input-group time-picker">';
                    echo '<input name="start['.$k.'][]" type="text" class="time-picker form-control input-small" value="'.$start.'" style="min-width:70px;">';
                    echo '</div>';
                    echo '</td>';

                    echo '<td>';
                    echo '<div class="input-group time-picker">';
                    echo '<input type="text" class="time-picker form-control input-small" name="end['.$k.'][]" value="'.$end.'" style="min-width:70px;">';
                    echo '</div>';
                    echo '</td>';
                }
                echo "</tr>";
            }
            echo '</tbody></table>';
            ?>
                <input type="hidden" name="action" id="action" value="shift_time_setting">
            </form>
        </div>
        <div style="padding: 20px; text-align: right; border-top: 1px solid #dadada">
            <button type="button" class="btn btn-primary" id="save_shift_time">Save changes</button>
        </div>

        <div class="col-md-12" style="height: 20px; border-bottom: 1px solid #ececec"></div>

        <div class="col-md-12">
            <h4 style="background-color: #0c99d6; color: #fff; padding: 10px;">Display Setting</h4>
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-responsive">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Tag</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Casting H1/1</td>
                            <td>
                                <select class="form-control tags" id="casting_h1_1" name="casting_h1_1">
                                    <option value="0" selected disabled>[Select Tag Address]</option>
                                    <?php
                                    foreach ($tag_addresses as $tag_address){
                                        if(isset($display_setting['casting_h1_1']) && $display_setting['casting_h1_1'] == $tag_address)
                                            echo "<option value='".$tag_address."' selected>".$tag_address."</option>";
                                        else
                                            echo "<option value='".$tag_address."'>".$tag_address."</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <a class="display_save" id="casting_h1_1_save" style="cursor:pointer;">Save</a>&nbsp;&nbsp;
                                <a class="display_delete" id="casting_h1_1_del" style="cursor:pointer;">Delete</a>
                            </td>
                        </tr>
                        <tr>
                            <td>Casting H1/2</td>
                            <td>
                                <select class="form-control tags" id="casting_h1_2" name="casting_h1_2">
                                    <option value="0" selected disabled>[Select Tag Address]</option>
                                    <?php
                                    foreach ($tag_addresses as $tag_address){
                                        if(isset($display_setting['casting_h1_2']) && $display_setting['casting_h1_2'] == $tag_address)
                                            echo "<option value='".$tag_address."' selected>".$tag_address."</option>";
                                        else
                                            echo "<option value='".$tag_address."'>".$tag_address."</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <a class="display_save" id="casting_h1_2_save" style="cursor:pointer;">Save</a>&nbsp;&nbsp;
                                <a class="display_delete" id="casting_h1_2_del" style="cursor:pointer;">Delete</a>
                            </td>
                        </tr>
                        <tr>
                            <td>Casting H2/1</td>
                            <td>
                                <select class="form-control tags" id="casting_h2_1" name="casting_h2_1">
                                    <option value="0" selected disabled>[Select Tag Address]</option>
                                    <?php
                                    foreach ($tag_addresses as $tag_address){
                                        if(isset($display_setting['casting_h2_1']) && $display_setting['casting_h2_1'] == $tag_address)
                                            echo "<option value='".$tag_address."' selected>".$tag_address."</option>";
                                        else
                                            echo "<option value='".$tag_address."'>".$tag_address."</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <a class="display_save" id="casting_h2_1_save" style="cursor:pointer;">Save</a>&nbsp;&nbsp;
                                <a class="display_delete" id="casting_h2_1_del" style="cursor:pointer;">Delete</a>
                            </td>
                        </tr>
                        <tr>
                            <td>Casting H2/2</td>
                            <td>
                                <select class="form-control tags" id="casting_h2_2" name="casting_h2_2">
                                    <option value="0" selected disabled>[Select Tag Address]</option>
                                    <?php
                                    foreach ($tag_addresses as $tag_address){
                                        if(isset($display_setting['casting_h2_2']) && $display_setting['casting_h2_2'] == $tag_address)
                                            echo "<option value='".$tag_address."' selected>".$tag_address."</option>";
                                        else
                                            echo "<option value='".$tag_address."'>".$tag_address."</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <a class="display_save" id="casting_h2_2_save" style="cursor:pointer;">Save</a>&nbsp;&nbsp;
                                <a class="display_delete" id="casting_h2_2_del" style="cursor:pointer;">Delete</a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-responsive">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Tag</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Inspection H1</td>
                            <td>
                                <select class="form-control tags" id="inspection_h1" name="inspection_h1">
                                    <option value="0" selected disabled>[Select Tag Address]</option>
                                    <?php
                                    foreach ($tag_addresses as $tag_address){
                                        if(isset($display_setting['inspection_h1']) && $display_setting['inspection_h1'] == $tag_address)
                                            echo "<option value='".$tag_address."' selected>".$tag_address."</option>";
                                        else
                                            echo "<option value='".$tag_address."'>".$tag_address."</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <a class="display_save" id="inspection_h1_save" style="cursor:pointer;">Save</a>&nbsp;&nbsp;
                                <a class="display_delete" id="inspection_h1_del" style="cursor:pointer;">Delete</a>
                            </td>
                        </tr>
                        <tr>
                            <td>Inspection H2</td>
                            <td>
                                <select class="form-control tags" id="inspection_h2" name="inspection_h2">
                                    <option value="0" selected disabled>[Select Tag Address]</option>
                                    <?php
                                    foreach ($tag_addresses as $tag_address){
                                        if(isset($display_setting['inspection_h2']) && $display_setting['inspection_h2'] == $tag_address)
                                            echo "<option value='".$tag_address."' selected>".$tag_address."</option>";
                                        else
                                            echo "<option value='".$tag_address."'>".$tag_address."</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <a class="display_save" id="inspection_h2_save" style="cursor:pointer;">Save</a>&nbsp;&nbsp;
                                <a class="display_delete" id="inspection_h2_del" style="cursor:pointer;">Delete</a>
                            </td>
                        </tr>
                        <tr>
                            <td>Inspection</td>
                            <td>
                                <select class="form-control tags" id="inspection" name="inspection">
                                    <option value="0" selected disabled>[Select Tag Address]</option>
                                    <?php
                                    foreach ($tag_addresses as $tag_address){
                                        if(isset($display_setting['inspection']) && $display_setting['inspection'] == $tag_address)
                                            echo "<option value='".$tag_address."' selected>".$tag_address."</option>";
                                        else
                                            echo "<option value='".$tag_address."'>".$tag_address."</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <a class="display_save" id="inspection_save" style="cursor:pointer;">Save</a>&nbsp;&nbsp;
                                <a class="display_delete" id="inspection_del" style="cursor:pointer;">Delete</a>
                            </td>
                        </tr>
                        <tr>
                            <td>Heat Treatment</td>
                            <td>
                                <select class="form-control tags" name="heat_treatment" id="heat_treatment">
                                    <option value="0" selected disabled>[Select Tag Address]</option>
                                    <?php
                                    foreach ($tag_addresses as $tag_address){
                                        if(isset($display_setting['heat_treatment']) && $display_setting['heat_treatment'] == $tag_address)
                                            echo "<option value='".$tag_address."' selected>".$tag_address."</option>";
                                        else
                                            echo "<option value='".$tag_address."'>".$tag_address."</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <a class="display_save" id="heat_treatment_save" style="cursor:pointer;">Save</a>&nbsp;&nbsp;
                                <a class="display_delete" id="heat_treatment_del" style="cursor:pointer;">Delete</a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <h4 style="background-color: #0c99d6; color: #fff; padding: 10px;">Main Settings</h4>

            <div class="row">
                <div class="col-md-5">
                    <div class="col-md-12">
                        <h4 style="display: inline">Processes</h4>&nbsp;&nbsp;&nbsp;
                        <input type="text" id="process_name" name="process_name" class="form-control" style="display: inline; width: 300px;">
                        <input type="hidden" id="pre_process_id" name="pre_process_id" value="0">
                        <button class="btn btn-success" id="save_process">Save</button>
                    </div>
                    <div class="col-md-12" style="padding-top: 20px;" id="processes_table"></div>

                </div>
                <div class="col-md-7">
                    <div class="col-md-12">
                        <h4 style="display: inline">Lines</h4>&nbsp;&nbsp;&nbsp;
                        <select id="select_process" name="select_process" class="form-control" style="display: inline; width: 300px;">
                        </select>
                        <input type="text" id="line_name" name="line_name" class="form-control" style="display: inline; width: 300px;">
                        <input type="hidden" id="pre_line_id" name="pre_line_id" value="0">
                        <button class="btn btn-success" id="save_line">Save</button>
                    </div>
                    <div class="col-md-12" style="padding-top: 20px;" id="lines_table"></div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12" style="padding-top: 50px;">
                    <h4>Tags</h4>
                    <form id="tag_form">
                        <div class="form-group col-md-3">
                            <label for="selected_line">Line Name:</label>
                            <select id="selected_line" name="selected_line" class="form-control">
                                <option value="0" selected disabled>[Select Line]</option>
                                <?php
                                $query = "SELECT * FROM {$tblLines}";
                                $result = $db->query($query);
                                while($row=mysqli_fetch_object($result)) {
                                    echo '<option value="'.$row->id.'">'.$row->line.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="tag_name">Tag Name:</label>
                            <input type="text" id="tag_name" name="tag_name" class="form-control">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="tag_address">Tag Address</label>
                            <select id="tag_address" name="tag_address" class="form-control">
                                <option value="0" selected disabled>[Select Tag Address]</option>
                                <?php
                                foreach ($tag_addresses as $tag_address){
                                    echo "<option value='".$tag_address."'>".$tag_address."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3" style="padding-top: 25px;">
                            <button type="button" class="btn btn-success line_tag_save" id="tag_name_save">Save</button>
                        </div>
                        <input type="hidden" id="old_tag_id" name="old_tag_id" value="0">
                        <input type="hidden" id="action" name="action" value="save_tag_name">
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-12">
                        <h4 style="display: inline">Trend Line Setting</h4>&nbsp;&nbsp;&nbsp;
                        <input type="text" id="trend_line" name="trend_line" class="form-control" style="display: inline; width: 300px;">
                        <button class="btn btn-success" id="save_trend_line">Save</button>
                    </div>
                    <div class="col-md-12" style="padding-top: 20px;" id="processes_table"></div>
                </div>
            </div>
        </div>
        <div class="col-md-12" id="line_tags_table">
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
<script src="js/bootstrap-timepicker.min.js"></script>
<script src="js/custom.js"></script>
<script>
    $(document).ready(function(){

        $('.time-picker').timepicker({
            minuteStep: 1,
            template: 'modal',
            appendWidgetTo: 'body',
            showSeconds: false,
            showMeridian: false,
            defaultTime: false
        });

        read_tag_tables();

        read_processes_table();
        read_process_options();

        read_lines_table();
        read_line_options();

        //Shift Time setting
        $(document).on('click', '#save_shift_time', function () {
            var form = $("#shift_setting_form");

            $.ajax({
                url: "actions.php",
                method: "post",
                data: form.serialize()
            }).done(function (res) {
                //console.log(res);
                if(res =="ok") {
                    $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                        $("#success-alert").slideUp(500);
                    });
                } else {
                    $("#fault-alert").fadeTo(2000, 500).slideUp(500, function(){
                        $("#fault-alert").slideUp(500);
                    });
                }
            });
        });

        $(".display_save").on('click', function () {
            var id = $(this).attr('id');
            var cast = id.replace("_save","");
            var tag = $("#"+cast).val();

            if(tag == null || tag=="") {
                $("#"+cast).focus();
                return false;
            }

            $.ajax({
                url: "actions.php",
                method: "post",
                data: {action:"display_setting", tag:tag, cast:cast},
            }).done(function (res) {
                if(res =="ok") {
                    $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                        $("#success-alert").slideUp(500);
                    });
                } else {
                    $("#fault-alert").fadeTo(2000, 500).slideUp(500, function(){
                        $("#fault-alert").slideUp(500);
                    });
                }
            });
        });

        $(".display_delete").on('click', function () {
            var id = $(this).attr('id');
            var cast = id.replace("_del","");

            $.ajax({
                url: "actions.php",
                method: "post",
                data: {action:"display_setting_delete", cast:cast},
            }).done(function (res) {
                if(res =="ok") {
                    $("#"+cast).val(0);
                    $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                        $("#success-alert").slideUp(500);
                    });
                } else {
                    $("#fault-alert").fadeTo(2000, 500).slideUp(500, function(){
                        $("#fault-alert").slideUp(500);
                    });
                }
            });

        });

        $("#save_process").on('click', function () {
            var process = $("#process_name").val();
            var old_id = $("#pre_process_id").val();

            if(process == "") {
                $("#process_name").focus();
                return false;
            }

            $.ajax({
                url: "actions.php",
                method: "post",
                data: {action:"save_process_name", old_id:old_id, process:process},
            }).done(function (res) {
                if(res =="ok") {
                    $("#process_name").val('');
                    $("#pre_process_id").val(0);

                    read_processes_table();
                    read_process_options();

                    $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                        $("#success-alert").slideUp(500);
                    });
                } else {
                    $("#fault-alert").fadeTo(2000, 500).slideUp(500, function(){
                        $("#fault-alert").slideUp(500);
                    });
                }
            });

        });

        $("#save_trend_line").on('click', function () {
            var process = $("#process_name").val();
            var old_id = $("#pre_process_id").val();

            if(process == "") {
                $("#process_name").focus();
                return false;
            }

            $.ajax({
                url: "actions.php",
                method: "post",
                data: {action:"save_process_name", old_id:old_id, process:process},
            }).done(function (res) {
                if(res =="ok") {
                    $("#process_name").val('');
                    $("#pre_process_id").val(0);

                    read_processes_table();
                    read_process_options();

                    $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                        $("#success-alert").slideUp(500);
                    });
                } else {
                    $("#fault-alert").fadeTo(2000, 500).slideUp(500, function(){
                        $("#fault-alert").slideUp(500);
                    });
                }
            });

        });

        $(document).on('click', '.edit-process', function () {
            var process = $(this).data('process');
            $("#process_name").val(process);
            var old_id = $(this).data('old');
            $("#pre_process_id").val(old_id);
        });

        $(document).on('click', '.delete-process', function () {
            var old_id = $(this).data('old');
            if(confirm("Are you sure?")){
                $.ajax({
                    url: "actions.php",
                    method: "post",
                    data: {action:"delete_process_name", old_id:old_id},
                }).done(function (res) {
                    if(res =="ok") {
                        read_processes_table();
                        read_process_options();
                        read_lines_table();
                    }
                });
            }

        });

        $("#save_line").on('click', function () {
            var line = $("#line_name").val();
            var old_id = $("#pre_line_id").val();
            var process = $("#select_process").val();

            if(line == "") {
                $("#line_name").focus();
                return false;
            }

            if(process == null || process == 0 || process == ""){
                $("#select_process").focus();
                return false;
            }

            $.ajax({
                url: "actions.php",
                method: "post",
                data: {action:"save_line_name", line:line, old_id:old_id, process:process},
            }).done(function (res) {
                if(res =="ok") {
                    $("#line_name").val('');
                    $("#select_process").val(0);
                    $("#pre_line_id").val(0);

                    read_lines_table();
                    read_line_options();

                    $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                        $("#success-alert").slideUp(500);
                    });
                } else {
                    $("#fault-alert").fadeTo(2000, 500).slideUp(500, function(){
                        $("#fault-alert").slideUp(500);
                    });
                }
            });

        });

        $(document).on('click', '.edit-line', function () {
            var line = $(this).data('line');
            $("#line_name").val(line);
            var process = $(this).data('process');
            $("#select_process").val(process);
            var old_id = $(this).data('old');
            $("#pre_line_id").val(old_id);
        });

        $(document).on('click', '.delete-line', function () {
            var old_id = $(this).data('old');
            if(confirm("Are you sure?")){
                $.ajax({
                    url: "actions.php",
                    method: "post",
                    data: {action:"delete_line_name", old_id:old_id},
                }).done(function (res) {
                    if(res =="ok") {
                        read_lines_table();
                        read_line_options();
                    }
                });
            }

        });

        $("#tag_name_save").on('click', function () {
            var form  = $("#tag_form");
            var line_id = $("#selected_line").val();

            if(line_id == 0 || line_id == null) {
                $("#selected_line").focus();
                return false;
            }

            var tag_name = $("#tag_name").val();

            if(tag_name == "" || tag_name == null) {
                $("#tag_name").focus();
                return false;
            }

            var tag_address = $("#tag_address").val();

            if(tag_address == 0 || tag_address == null) {
                $("#tag_address").focus();
                return false;
            }

            $.ajax({
                url: "actions.php",
                method: "post",
                data: form.serialize()
            }).done(function (res) {
                //console.log(res);
                if(res == "ok") {
                    $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                        $("#success-alert").slideUp(500);
                    });
                    $("#selected_line").val(0);
                    $("#tag_name").val('');
                    $("#tag_address").val(0);
                    read_tag_tables();
                } else {
                    $("#fault_message").text(res);
                    $("#fault-alert").fadeTo(2000, 500).slideUp(500, function(){
                        $("#fault-alert").slideUp(500);
                    });
                }
            });
        });

        $(document).on('click', '.tag-delete', function () {
            var tag_id = $(this).attr('id').replace('tag_del', '');
            if(confirm("Are you sure?")) {
                $.ajax({
                    url: "actions.php",
                    method: "post",
                    data: {tag_id:tag_id, action : "delete_line_tags"}
                }).done(function (res) {
                    console.log(res);
                    if(res == "ok") {
                        read_tag_tables();
                    } else {
                        $("#fault_message").text('Delete failed');
                        $("#fault-alert").fadeTo(2000, 500).slideUp(500, function(){
                            $("#fault-alert").slideUp(500);
                        });
                    }
                });
            }
        });

        $(document).on('click', '.tag-edit', function () {
            var line_tag_id = $(this).attr('id').replace('tag', '');
            $("#old_tag_id").val(line_tag_id);

            var line_id = $(this).data('line');
            $("#selected_line").val(line_id);

            var tag_id = $(this).data('tag');
            $("#tag_name").val(tag_id);

            var tag_address = $(this).data('tagaddress');
            $("#tag_address").val(tag_address);
        });

        /*$("#selected_line").on('change', function () {
         read_tag_tables();
         });*/
    });


    function read_tag_tables()
    {
        var line_id = $("#selected_line").val();
        $.ajax({
            url: "actions.php",
            method: "post",
            data: {action:"read_line_tags_table", line_id:line_id},
            dataType: "HTML"
        }).done(function (html) {
            $("#line_tags_table").html(html);
        });

    }

    function read_processes_table()
    {
        $.ajax({
            url: "actions.php",
            method: "post",
            data: {action:"read_processes_table"},
            dataType: "HTML"
        }).done(function (html) {
            $("#processes_table").html(html);
        });

    }

    function read_process_options()
    {
        $.ajax({
            url: "actions.php",
            method: "post",
            data: {action:"read_process_options"},
            dataType: "HTML"
        }).done(function (html) {
            $("#select_process").html(html);
        });

    }


    function read_lines_table()
    {
        $.ajax({
            url: "actions.php",
            method: "post",
            data: {action:"read_lines_table"},
            dataType: "HTML"
        }).done(function (html) {
            $("#lines_table").html(html);
        });

    }

    function read_line_options()
    {
        $.ajax({
            url: "actions.php",
            method: "post",
            data: {action:"read_line_options"},
            dataType: "HTML"
        }).done(function (html) {
            $("#selected_line").html(html);
        });

    }
</script>
</html>

