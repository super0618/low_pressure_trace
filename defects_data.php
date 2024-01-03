<?php
require_once("./config/config.php");
require_once("functions.php");
$page_name = "Defects Data";

//GET Last Date
$casting_machine_query = "SELECT * FROM {$tblcastingmaster}";
$casting_machine_result = $db->query($casting_machine_query);
while ($casting_machine_row = mysqli_fetch_object($casting_machine_result)) {
    $casting_machine_numbers[] = $casting_machine_row->CastingCode;
}
$casting_machine_numbers = array_unique($casting_machine_numbers);

$last_date = date('Y-m-d');
foreach ($casting_machine_numbers as $casting_machine_number) {
    $tblLPQuality_[$casting_machine_number] = $tblLPQuality . '_' . $casting_machine_number;
    $query = "SELECT `Date/time` as date_time  FROM {$tblLPQuality_[$casting_machine_number]} ORDER BY `Date/time` DESC limit 1";
    $result = $db->query($query);
    $row = mysqli_fetch_object($result);
    if($row) {
        $tmp_date = $row->date_time;
        if(!isset($last_date) || $last_date < $tmp_date) {
            $last_date = date('Y-m-d', strtotime($tmp_date));
        }
    } else {
        $last_date = date('Y-m-d');
    }
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

    <link href="css/chosen.css" rel="stylesheet"/>
    <script src="js/chosen.jquery.js"></script>

    <link rel="stylesheet" href="css/font-awesome.css">
    <link rel="stylesheet" href="assets/css/jquery.mCustomScrollbar.min.css" />
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="assets/css/custom-themes.css">

    <style>
        h5 {
            color: #0e83cd;
            font-size: 16px;
        }

        .filter {
            margin-top: 20px;
            padding-top: 15px;
            border: 1px solid #e0e0e0;
            padding: 10px 10px 20px 10px;
        }

        .filter label {
            margin-top: 10px;
        }

        th, td {
            white-space: nowrap;
        }

        .even {
            background-color: white;
        }

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

        .col-centered{
            float: none;
            margin: 0 auto;
        }

        .circle {
            width: 100px;
            line-height: 100px;
            border-radius: 50%;
            text-align: center;
            font-size: 32px;
            color: #fff;
            border: 2px solid #666;
        }

        .show {
            display: block;
            transition:all 1s;
        }

        .hide {
            display: none;
            transition:all 1s;
        }

        .defect-card {
            border: 1px solid #000;
            margin: 4px;
            height: 80px;
            max-width: 180px;
        }

        .defect-card-title {
            padding: 0 8px;
            background-color: grey;
            color: #fff;
            font-size: 16px;
            white-space: nowrap;
            text-transform: uppercase;
        }

        .text-block-8 {
            font-size: 36px;
            text-align: center;
        }

        .text-bold {
            font-weight: bold;
        }

        .text-gray {
            color: gray;
        }
        .text-red {
            color: #e74026;
        }
    </style>
</head>
<body onload="startTime()">
<div class="page-wrapper chiller-theme">
    <?php
    include('menu.php');
    ?>
    <!-- sidebar-wrapper  -->
    <main class="page-content">
        <div class="container-fluid">
            <div class="row">
                <?php
                include('header.php');
                ?>
            </div>
            <div class="row filter" style="margin-top: 20px; margin-bottom: 20px;">
                <div class="row" style="margin-left: 0; margin-right: 0;">
                    <form id="filter_form" class="form-inline">
                        <div class="col-md-4 ">
                            <div class="form-group col-md-12 show defect-inputs" style="margin-top: 10px;">
                                <div class="col-md-4 text-right">
                                    <label>Defect Input:</label>
                                </div>
                                <div class="col-md-8 text-right">
                                    <input type="text" name="defect" id="defect" style="width: 100%;" class="form-control" value="">
                                </div>
                            </div>
                            <div class="form-group col-md-12 hide defect-inputs" style="margin-top: 10px;">
                                <div class="col-md-4 text-right">
                                    <label>Defect Code:</label>
                                </div>
                                <div class="col-md-8 text-right">
                                    <input type="text" name="defect_value" id="defect_value" style="width: 100%;" class="form-control" value="">
                                </div>
                            </div>
                            <div class="form-group col-md-12 advanced-search" style="margin-top: 10px;">
                                <div class="col-md-4 text-right">
                                    <label>Engine Types:</label>
                                </div>
                                <div class="col-md-8 text-right">
                                    <select class="form-control" style="width: 100%;" id="engine_type" name="engine_type">
                                        <option value="" SELECTED>(Unspecified)</option>
                                        <?php
                                        $query = "SELECT * FROM {$tblcarmaster}";
                                        $result = $db->query($query);
                                        while ($row = mysqli_fetch_object($result)) {
                                            echo '<option value="' . $row->CarCode . '">' . $row->CarName . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-md-12 advanced-search" style="margin-top: 10px;">
                                <div class="col-md-4 text-right">
                                    <label>Line:</label>
                                </div>
                                <div class="col-md-8 text-right">
                                    <select class="form-control" style="width: 100%;" id="line" name="line">
                                        <option value="" SELECTED>(Unspecified)</option>
                                        <?php
                                        $query = "SELECT * FROM {$tbllinemaster}";
                                        $result = $db->query($query);
                                        while ($row = mysqli_fetch_object($result)) {
                                            echo '<option value="' . $row->LineCode . '">' . $row->LineName . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group col-md-12 advanced-search" style="margin-top: 10px;">
                                <div class="col-md-4 text-right">
                                    <label>Group:</label>
                                </div>
                                <div class="col-md-8 text-right">
                                    <select class="form-control" style="width: 100%;" id="group" name="group">
                                        <option value="" SELECTED>(Unspecified)</option>
                                        <?php
                                        $query = "SELECT * FROM {$tblclassmaster}";
                                        $result = $db->query($query);
                                        while ($row = mysqli_fetch_object($result)) {
                                            echo '<option value="' . $row->ClassCode . '">' . $row->ClassName . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-md-12 advanced-search" style="margin-top: 10px;">
                                <div class="col-md-4 text-right">
                                    <label>Casting Machine No.:</label>
                                </div>
                                <div class="col-md-8 text-right">
                                    <select class="form-control" style="width: 100%;" id="casting_machine_number"
                                            name="casting_machine_number">
                                        <option value="" SELECTED>(Unspecified)</option>
                                        <option value="1">EDM-101</option>
                                        <option value="2">EDM-102</option>
                                        <option value="3">EDM-103</option>
                                        <option value="4">EDM-104</option>
                                        <option value="5">EDM-105</option>
                                        <option value="6">EDM-106</option>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group col-md-12 advanced-search" style="margin-top: 10px;">
                                <div class="col-md-4 text-right">
                                    <label>Number of Items:</label>
                                </div>
                                <div class="col-md-8 text-right">
                                    <select class="form-control" style="width: 100%;" id="item" name="item">
                                        <option value="10">10</option>
                                        <option value="50" selected>50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 ">
                            <div class="form-group col-md-12 advanced-search" style="margin-top: 10px;">
                                <div class="col-md-4 text-right">
                                    <label>Defect Filter:</label>
                                </div>
                                <div class="col-md-8 text-right">
                                    <select class="form-control" id="defect_filter" name="defect_filter" style="width: 100%;">
                                        <option value="" SELECTED>(Unspecified)</option>
                                        <?php
                                        $query = "SELECT * FROM {$tblDefectsSetting}";
                                        $result = $db->query($query);
                                        while ($row = mysqli_fetch_object($result)) {
                                            echo "<option value=\"{$row->name}\">{$row->value}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-12 advanced-search" style="margin-top: 10px;">
                                <div class="col-md-4 text-right">
                                    <label>Time Period From:</label>
                                </div>
                                <div class="col-md-8 text-right">
                                    <input type="text" id="from_date" name="from_date" class="form-control datepicker"
                                            style="width: 100%;" value="<?php echo date('d-m-Y'); ?>">
                                </div>
                            </div>
                            <div class="form-group col-md-12 advanced-search" style="margin-top: 10px;">
                                <div class="col-md-4 text-right">
                                    <label>To:</label>
                                </div>
                                <div class="col-md-8 text-right">
                                    <input type="text" id="to_date" name="to_date" class="form-control datepicker"
                                            style="width: 100%;" value="<?php echo date('d-m-Y'); ?>">
                                    <input type="hidden" id="last_date" value="<?php echo date('d-m-Y', strtotime($last_date)); ?>">
                                </div>
                            </div>
                            <div class="form-group col-md-12 advanced-search" style="margin-top: 10px; width: 100%;">
                                <div class="col-md-4 text-right">
                                    <label>Shift:</label>
                                </div>
                                <div class="col-md-8 text-left">
                                    <select class="chosen-select  form-control" id="shift_no" name="shift_no[]" style="width: 100%;" multiple>
                                        <?php
                                        $query = "SELECT * FROM {$tbldutymaster}";
                                        $result = $db->query($query);
                                        while ($row = mysqli_fetch_object($result)) {
                                            echo '<option value="' . $row->DutyCode . '">' . $row->DutyName . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group col-md-12 advanced-search" style="margin-top: 10px;">
                                <div class="col-md-4 text-right">
                                    <label>Serial No.:</label>
                                </div>
                                <div class="col-md-8 text-right">

                                    <input type="text" name="select_id_no" style="width: 100%;" id="select_id_no" value="" class="form-control">
                                </div>
                            </div>

                            <div class="form-group col-md-12 advanced-search" style="margin-top: 10px;">
                                <div class="col-md-4 text-right">

                                </div>
                                <div class="col-md-8 text-left">
                                    <label><input type="checkbox" id="ng_judgements_only" name="ng_judgements_only">&nbsp;&nbsp;Display
                                        NG judgments Only</label>
                                </div>
                            </div>
                            <input type="hidden" name="page" id="page" value="1">
                            <input type="hidden" name="action" value="retrieve-quality-results">
                            <input type="hidden" name="result_target" value="traceability">

                        </div>
                        <div class="col-md-4 ">
                            <div class="col-md-12" style="margin-top: 10px;">
                                <button type="button" class="btn btn-secondary btn-md" id="advanced-search">Advanced Search</button>
                            </div>
                            <div class="col-md-12 advanced-search">
                                <label class="col-md-12"><input type="radio" name="order" id="order_default" checked>&nbsp;&nbsp;Default</label>
                                <label class="col-md-12"><input type="radio" name="order"
                                                                id="order_asc">&nbsp;&nbsp;Ascending</label>
                                <label class="col-md-12"><input type="radio" name="order"
                                                                id="order_desc">&nbsp;&nbsp;Descending</label>
                            </div>
                            <div class="col-md-12">
                                &nbsp;&nbsp;
                            </div>
                            <div class="col-md-12 advanced-search">
                                <button type="button" class="btn btn-primary pull-left btn-xlarge" id="retrieval">
                                    Search
                                </button>
                                <button type="button" class="btn btn-primary pull-left btn-xlarge" style="margin-left: 20px;" id="reset">Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9" style="display: flex">
                    <?php
                        $query = $db->query("SELECT * FROM {$tblDefectsSetting}");
                    ?>
                    <?php 
                    $defects = array();
                    $default_start_time = date('Y-m-d 00:00:00');
                    $default_end_time = date('Y-m-d 23:59:59');

                    while ($setting = mysqli_fetch_assoc($query)) {
                            $defect_count = $db->query("SELECT Count(*) FROM t_defects WHERE (defect = '" . $setting['name'] . "' Or defect = '" . $setting['value'] . "') and `timestamp` >= '" . $default_start_time . "' and `timestamp` <= '" . $default_end_time . "'");
                            $setting['count'] = mysqli_fetch_array($defect_count)[0];
                            array_push($defects, $setting);
                        }
                    ?>
                    <?php foreach ($defects as $defect) {
                        echo '<div class="defect-card defect-item" defect_name="'.str_replace(" ","_",$defect['name']).'">
                            <div class="text-center">
                                <div class="defect-card-title">' . $defect['name'] . '</div>
                            </div>
                            <div>
                                <div class="text-block-8 text-bold defect-text ' . ($defect['count'] == 0 ? 'text-gray' : 'text-red') . '" id="defect-circle-' . $defect['id'] . '">' . $defect['count'] . '</div>
                            </div>
                        </div>';
                        }
                    ?>
                </div>
                <div class="col-md-3">
                    <div class="row col-centered">
                        <div class="text-center col-md-6">
                            <?php
                                $outstanding = $db->query("SELECT * FROM t_defects WHERE (scrap = 0 OR scrap IS NULL) and `timestamp` >= '".$default_start_time."' and `timestamp` <= '".$default_end_time."'")->num_rows;
                            ?>
                            <?php
                                echo '<div class="defect-card">
                                    <div class="text-center">
                                        <div class="defect-card-title">Outstanding</div>
                                    </div>
                                    <div>
                                        <div class="text-block-8 text-bold defect-outstanding ' . ($outstanding == 0 ? 'text-gray' : 'text-red') . '" id="outstanding-circle">' . $outstanding . '</div>
                                    </div>
                                </div>';
                            ?>
                        </div>
                        <div class="text-center col-md-6">
                            <?php
                                $completed = $db->query("SELECT * FROM t_defects WHERE scrap = 1 and `timestamp` >= '".$default_start_time."' and `timestamp` <= '".$default_end_time."'")->num_rows;
                            ?>
                            <?php
                            echo '<div class="defect-card">
                                    <div class="text-center">
                                        <div class="defect-card-title">Completed</div>
                                    </div>
                                    <div>
                                        <div class="text-block-8 text-bold defect-completed ' . ($completed == 0 ? 'text-gray' : 'text-red') . '" id="completed-circle">' . $completed . '</div>
                                    </div>
                                </div>';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="filter_result" style="margin-top: 20px; margin-left: 0; margin-right: 0;"></div>
        </div>
    </main>
</div>

<div class="my-alert alert alert-success hide" id="success-alert">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <span id="success-message"></span>
</div>

<div class="my-alert alert alert-danger hide" id="fault-alert">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <strong id="fault_title">Fail! </strong>
    <span id="fault-message">Saved failed.</span>
</div>

<div id="overlay">
    <div class="cv-spinner">
        <span class="spinner"></span>
    </div>
</div>
<style>

    #overlay {
        position: fixed;
        top: 0;
        z-index: 100;
        width: 100%;
        height: 100%;
        display: none;
        background: rgba(0, 0, 0, 0.6);
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

    .is-hide {
        display: none;
    }

    .go-page{
        cursor: pointer;
    }

    body::-webkit-scrollbar {
        width: 5px;
    }

    body::-webkit-scrollbar-track {
        background: transparent;
    }

    body::-webkit-scrollbar-thumb {
        background-color: rgba(155, 155, 155, 0.5);
        border-radius: 10px;
        border: transparent;
    }
</style>
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

<script type='text/javascript'>
    <?php
        $js_array = json_encode($defects);
        echo "var availableNumbers = ". $js_array . ";\n";
    ?>
</script>
<script>
    // const availableNumbers = [1, 2, 3, 4, 5, 6];
    const defectsCount = number => {
        var form = $("#filter_form");
        $.ajax({
            url: "defects_action.php",
            method: "POST",
            dataType: "json",
            data: {
                "action": "get-machines",
                "number": number['id'],
                "formData": form.serialize()
            },
        }).done(response => {
            if(response[`defect_${number['id']}`]!=0) {
                $(`#defect-circle-${number['id']}`).addClass("text-red");
                $(`#defect-circle-${number['id']}`).removeClass("text-gray");
            } else {
                $(`#defect-circle-${number['id']}`).addClass("text-gray");
                $(`#defect-circle-${number['id']}`).removeClass("text-red");
            }

            $(`#defect-circle-${number['id']}`).text(response[`defect_${number['id']}`]);
        });
    }

    $(document).ready(function () {
        $('.advanced-search').hide();

        $('#advanced-search').on('click', () => {
            $('.advanced-search').toggle();

            $(".chosen-select").chosen({
                "placeholder_text_multiple": "Please Select"
            });
        });

        availableNumbers.forEach(number => defectsCount(number));

        $('#defect').on('keyup', e => {
            var keyCode = e.code || e.key;

            if (keyCode == 'Enter') {
                $('.defect-inputs').toggleClass('show hide');
                $('#defect_value').focus();
            }
        });

        $('#defect_value').on('keyup', e => {
            var keyCode = e.code || e.key;

            if (keyCode == 'Enter') {
                if (!$('#defect').val() || !$('#defect_value').val()) {
                    $('#fault-alert').toggleClass('hide');
                    $('#fault-message').html('Defect and defect code is required');

                    setTimeout(() => {
                        $('#fault-alert').toggleClass('hide');
                        $(e.target).attr('disabled', false);
                    }, 2000);

                    return;
                }
                $.ajax({
                    url: "defects_action.php",
                    method: "POST",
                    dataType: "json",
                    data: {
                        "defect": $('#defect').val(),
                        "defect_value": $('#defect_value').val(),
                        "action": "add-to-defect",
                    },
                }).done(response => {
                    if (response.status === 'success') {

                        $('#success-alert').toggleClass('hide');
                        $('#success-message').html(`Serial ${response.defect} has defect ${response.defect_value} added`);

                        setTimeout(() => {
                            availableNumbers.forEach(number => defectsCount(number));

                            $('#success-alert').toggleClass('hide');
                            // $('#add-to-defect').attr('disabled', false);

                            $("#overlay").fadeIn(300);
                            var form = $("#filter_form");
                            $.ajax({
                                url: "defects_result.php",
                                method: "get",
                                data: form.serialize(),
                                dataType: "HTML"
                            }).done(function (html) {
                                $("#filter_result").html(html);
                                setTimeout(function () {
                                    $("#overlay").fadeOut(300);
                                }, 500);

                                var table = $('#data_table').DataTable({
                                    scrollY: "700px",
                                    scrollX: true,
                                    scrollCollapse: true,
                                    paging: false,
                                    searching: false,
                                    bInfo: false,
                                    fixedColumns: {
                                        leftColumns: 2
                                    }
                                });
                            });

                            $.ajax({
                                url: "defects_action.php",
                                method: "POST",
                                dataType: "json",
                                data: {
                                    "action": "get-total",
                                    "formData": form.serialize()
                                },
                            }).done(res => {
                                const [outstanding, completed] = res;
                                if(outstanding != 0) {
                                    $('#outstanding-circle').addClass('text-red');
                                    $('#outstanding-circle').removeClass('text-gray');
                                } else {
                                    $('#outstanding-circle').removeClass('text-red');
                                    $('#outstanding-circle').addClass('text-gray');
                                }

                                if(completed != 0) {
                                    $('#completed-circle').addClass('text-red');
                                    $('#completed-circle').removeClass('text-gray');
                                } else {
                                    $('#completed-circle').removeClass('text-red');
                                    $('#completed-circle').addClass('text-gray');
                                }

                                $('#outstanding-circle').html(outstanding);
                                $('#completed-circle').html(completed);
                            });
                        }, 2000);
                    } else {
                        $('#fault-alert').toggleClass('hide');
                        $('#fault-message').html(response.message);

                        setTimeout(() => {
                            $('#fault-alert').toggleClass('hide');
                            button.attr('disabled', false);
                        }, 2000);
                    }
                });
                $('.defect-inputs').toggleClass('show hide');
                $('#defect, #defect_value').val("");

            }
        });

        get_last_data();

        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
        });

        $("#retrieval").on('click', function () {
            $("#overlay").fadeIn(300);
            var form = $("#filter_form");
            $.ajax({
                url: "defects_result.php",
                method: "get",
                data: form.serialize(),
                dataType: "HTML"
            }).done(function (html) {
                $("#filter_result").html(html);
                setTimeout(function () {
                    $("#overlay").fadeOut(300);
                }, 500);

                var a = $(".defect-item");

                for(let i = 0; i<a.length; i++){
                    let defect_name = $(a[i]).attr("defect_name");
                    let count = parseInt(Math.ceil($("." + defect_name).length));
                    $(a[i]).find(".defect-text").text(count);
                    if(count === 0) {
                        $(a[i]).find(".defect-text").removeClass("text-red");
                        $(a[i]).find(".defect-text").addClass("text-gray");
                    } else {
                        $(a[i]).find(".defect-text").addClass("text-red");
                        $(a[i]).find(".defect-text").removeClass("text-gray");
                    }
                }

                a = $(".defect-outstanding");

                var b = $("#filter_result").find(".scrap-select");
                var count1 = 0, count2 = 0;
                for(let i = 0; i<b.length; i++) {
                    if(b[i].value == 0)
                        count1++;
                    else
                        count2++;
                }
                a.text(count1);
                if(count1 === 0) {
                    a.removeClass("text-red");
                    a.addClass("text-gray");
                } else {
                    a.removeClass("text-gray");
                    a.addClass("text-red");
                }

                a = $(".defect-completed");
                a.text(count2);
                if(count2 === 0) {
                    a.removeClass("text-red");
                    a.addClass("text-gray");
                } else {
                    a.removeClass("text-gray");
                    a.addClass("text-red");
                }

                var table = $('#data_table').DataTable({
                    scrollY: "700px",
                    scrollX: true,
                    scrollCollapse: true,
                    paging: false,
                    searching: false,
                    bInfo: false,
                    fixedColumns: {
                        leftColumns: 2
                    }
                });
            });

        });

        $("#reset").on('click', function () {
            $("#overlay").fadeIn(300);
            $('#engine_type').prop('selectedIndex', 0);
            $('#line').prop('selectedIndex', 0);
            $('#group').prop('selectedIndex', 0);
            $('#casting_machine_number').prop('selectedIndex', 0);
            $('#item').prop('selectedIndex', 0);
            $('#shift_no').prop('selectedIndex', 0);
            $("#select_id_no").val("");
            $("#defect").val("");
            $("#defect_filter").val("");
            $('#ng_judgements_only').prop('checked', false);

            $('#order_default').prop('checked', true);
            $('#order_desc').prop('checked', false);
            $('#ng_judgementorder_ascs_only').prop('checked', false);

            //date
            var last_date = $("#last_date").val();
            if(last_date == "") {
                var currentDate = new Date();
                var dd = currentDate.getDate();
                var mm = currentDate.getMonth() + 1; //January is 0!
                var yyyy = currentDate.getFullYear();

                if (dd < 10) {
                    dd = '0' + dd
                }

                if (mm < 10) {
                    mm = '0' + mm
                }

                currentDate = dd + '-' + mm + '-' + yyyy;
                $("#from_date").val(currentDate);
                $("#to_date").val(currentDate);
            } else {
                $("#from_date").val(last_date);
                $("#to_date").val(last_date);
            }

            get_last_data();
        });


        var table = $('#data_table').DataTable({
            scrollY: "500px",
            scrollX: true,
            scrollCollapse: true,
            paging: false,
            fixedColumns: {
                leftColumns: 2
            },
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel'
            ]
        });
    });

    function get_last_data() {
        $("#overlay").fadeIn(300);
        var form = $("#filter_form");
        $.ajax({
            url: "defects_result.php",
            method: "get",
            data: form.serialize(),
            dataType: "HTML"
        }).done(function (html) {
            $("#filter_result").html(html);
            setTimeout(function () {
                $("#overlay").fadeOut(300);
            }, 500);
            var table = $('#data_table').DataTable({
                scrollY: "700px",
                scrollX: true,
                scrollCollapse: true,
                paging: false,
                searching: false,
                bInfo: false,
                fixedColumns: {
                    leftColumns: 2
                }
            });
        });

        $(document).on('click', '.go-page', function () {
            console.log('test');
            var page = $(this).data('page');
            $("#page").val(page);
            $("#retrieval").click();
        });

        $(document).on('change', '.scrap-select', e => {
            $.ajax({
                url: "defects_action.php",
                method: "POST",
                dataType: "json",
                data: {
                    "id": $(e.target).data('id'),
                    "value": $(e.target).val(),
                    "action": "update-scrap",
                },
            }).done(response => {
                if (response.status === 'success') {
                    availableNumbers.forEach(number => defectsCount(number));

                    $('#success-alert').toggleClass('hide');
                    $('#success-message').html(response.message);

                    setTimeout(() => $('#success-alert').toggleClass('hide'), 2000);
                } else {
                    $('#fault-alert').toggleClass('hide');
                    $('#fault-message').html(response.message);

                    setTimeout(() => $('#fault-alert').toggleClass('hide'), 2000);
                }

                $.ajax({
                    url: "defects_action.php",
                    method: "POST",
                    dataType: "json",
                    data: {
                        "action": "get-total",
                        "formData": form.serialize()
                    },
                }).done(res => {
                    const [outstanding, completed] = res;

                    $('#outstanding-circle').html(outstanding);
                    $('#completed-circle').html(completed);
                });
            });
        });

        $(document).on('change', '.re-test-select', e => {
            $.ajax({
                url: "defects_action.php",
                method: "POST",
                dataType: "json",
                data: {
                    "id": $(e.target).data('id'),
                    "value": $(e.target).val(),
                    "action": "update-test",
                },
            }).done(response => {
                if (response.status === 'success') {
                    $('#success-alert').toggleClass('hide');
                    $('#success-message').html(response.message);

                    setTimeout(() => $('#success-alert').toggleClass('hide'), 2000);
                } else {
                    $('#fault-alert').toggleClass('hide');
                    $('#fault-message').html(response.message);

                    setTimeout(() => $('#fault-alert').toggleClass('hide'), 2000);
                }

                $.ajax({
                    url: "defects_action.php",
                    method: "POST",
                    dataType: "json",
                    data: {
                        "action": "get-total",
                        "formData": form.serialize()
                    },
                }).done(res => {
                    const [outstanding, completed] = res;

                    $('#outstanding-circle').html(outstanding);
                    $('#completed-circle').html(completed);
                });
            });
        });
    }


</script>
<script src="assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="assets/js/custom.js"></script>
</html>