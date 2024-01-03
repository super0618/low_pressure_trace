<?php
require_once("./config/config.php");
require_once("functions.php");
$page_name = "View Data";

//GET Last Date
$casting_machine_query = "SELECT * FROM {$tblcastingmaster}";
$casting_machine_result = $db->query($casting_machine_query);
while ($casting_machine_row = mysqli_fetch_object($casting_machine_result)) {
    $casting_machine_numbers[] = $casting_machine_row->CastingCode;
}
$casting_machine_numbers = array_unique($casting_machine_numbers);
$last_date =  date('Y-m-d');

foreach ($casting_machine_numbers as $casting_machine_number) {
    $tblLPQuality_[$casting_machine_number] = $tblLPQuality . '_' . $casting_machine_number;
    $query = "SELECT `Date/time` as date_time  FROM {$tblLPQuality_[$casting_machine_number]} ORDER BY `Date/time` DESC limit 1";
    $result = $db->query($query);
    $row = mysqli_fetch_object($result);
    if($row) {
        $tmp_date = $row->date_time;
        if(!isset($last_date) || $last_date < $tmp_date)
            $last_date = date('Y-m-d', strtotime($tmp_date));
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
            <div class="row">
                <div class="row filter">
                    <div class="col-md-12">

                    </div>
                    <form id="filter_form" class="form-inline">
                        <div class="col-md-4 ">
                            <div class="form-group col-md-12" style="margin-top: 10px;">
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

                            <div class="form-group col-md-12" style="margin-top: 10px;">
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


                            <div class="form-group col-md-12" style="margin-top: 10px;">
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

                            <div class="form-group col-md-12" style="margin-top: 10px;">
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


                            <div class="form-group col-md-12" style="margin-top: 10px;">
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
                            <form class="form-inline">
                                <div class="form-group col-md-12">
                                    <div class="col-md-4 text-right">
                                        <label>Time Period From:</label>
                                    </div>
                                    <div class="col-md-8 text-right">
                                        <input type="text" id="from_date" name="from_date" class="form-control datepicker"
                                               style="width: 100%;" value="<?php echo date('d-m-Y', strtotime($last_date)); ?>">
                                    </div>
                                </div>
                                <div class="form-group col-md-12" style="margin-top: 10px;">
                                    <div class="col-md-4 text-right">
                                        <label>To:</label>
                                    </div>
                                    <div class="col-md-8 text-right">
                                        <input type="text" id="to_date" name="to_date" class="form-control datepicker"
                                               style="width: 100%;" value="<?php echo date('d-m-Y', strtotime($last_date)); ?>">
                                        <input type="hidden" id="last_date" value="<?php echo date('d-m-Y', strtotime($last_date)); ?>">
                                    </div>
                                </div>
                                <div class="form-group col-md-12" style="margin-top: 10px;">
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


                                <div class="form-group col-md-12" style="margin-top: 10px;">
                                    <div class="col-md-4 text-right">
                                        <label>Serial No.:</label>
                                    </div>
                                    <div class="col-md-8 text-right">

                                        <input type="text" name="select_id_no" style="width: 100%;" id="select_id_no" value="">
                                    </div>
                                </div>

                                <div class="form-group col-md-12" style="margin-top: 10px;">
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
                            </form>

                        </div>


                        <div class="col-md-4 ">

                            <div class="col-md-12">
                                <label class="col-md-12"><input type="radio" name="order" id="order_default" checked>&nbsp;&nbsp;Default</label>
                                <label class="col-md-12"><input type="radio" name="order"
                                                                id="order_asc">&nbsp;&nbsp;Ascending</label>
                                <label class="col-md-12"><input type="radio" name="order"
                                                                id="order_desc">&nbsp;&nbsp;Descending</label>
                            </div>
                            <div class="col-md-12">
                                &nbsp;&nbsp;
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-primary pull-left btn-xlarge" style="margin-left: 20px;" id="retrieval">
                                    Search
                                </button>
                                <button class="btn btn-primary pull-left btn-xlarge" style="margin-left: 20px;" id="reset">Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row" id="filter_result" style="margin-top: 20px;"></div>
            </div>
        </div>
    </main>
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
</style>
</body>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-datepicker.min.js"></script>
<script src="js/custom.js"></script>

<script src="js/FileSaver.min.js"></script>
<script src="js/Blob.min.js"></script>
<!-- <script src="js/xls.core.min.js"></script> -->


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

        $(".chosen-select").chosen({
            "placeholder_text_multiple": "Please Select"
        });

        get_last_data();

        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
        });


        $("#retrieval").on('click', function () {
            $("#overlay").fadeIn(300);
            var form = $("#filter_form").serialize();
            form += '&order_default' + '=' + $("#order_default").prop("checked");
            form += '&order_asc' + '=' + $("#order_asc").prop("checked");
            form += '&order_desc' + '=' + $("#order_desc").prop("checked");
            console.log(form);
            $.ajax({
                url: "results.php",
                method: "get",
                data: form,
                dataType: "HTML"
            }).done(function (html) {
                $("#filter_result").html(html);
                console.log(html);
                setTimeout(function () {
                    $("#overlay").fadeOut(300);
                }, 500);
                var props = {
                    scrollY: "700px",
                    scrollX: true,
                    scrollCollapse: true,
                    paging: false,
                    searching: false,
                    bInfo: false,
                    fixedColumns: {
                        leftColumns: 5
                    }
                };
                if($("#order_default").prop("checked")) props['order'] = [[1, 'desc']];
                var table = $('#data_table').DataTable(props);

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
                leftColumns: 5
            },
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel'
            ]
        });
    });

    function get_last_data() {
        $("#overlay").fadeIn(300);
        var form = $("#filter_form").serialize();
        form += '&order_default' + '=' + $("#order_default").prop("checked");
        form += '&order_asc' + '=' + $("#order_asc").prop("checked");
        form += '&order_desc' + '=' + $("#order_desc").prop("checked");
        $.ajax({
            url: "results.php",
            method: "get",
            data: form,
            dataType: "HTML"
        }).done(function (html) {
            $("#filter_result").html(html);
            setTimeout(function () {
                $("#overlay").fadeOut(300);
            }, 500);
            var props = {
                scrollY: "700px",
                scrollX: true,
                scrollCollapse: true,
                paging: false,
                searching: false,
                bInfo: false,
                fixedColumns: {
                    leftColumns: 5
                }
            };
            if($("#order_default").prop("checked")) props['order'] = [[1, 'desc']];
            var table = $('#data_table').DataTable(props);
        });


        $(document).on('click', '.go-page', function () {
            console.log('test');
            var page = $(this).data('page');
            $("#page").val(page);
            $("#retrieval").click();
        });
    }


</script>
<script src="assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="assets/js/custom.js"></script>
</html>