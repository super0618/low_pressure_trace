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

$graph_zone = 1;

if (isset($_POST['graph_zone'])) {
    $graph_zone = $_POST['graph_zone'];
}

$line = get_line($graph_zone);

$page_index = "live";

if ($graph_date == $live_date && $shift == $live_shift) {
    $page_index = "live";
} else {
    $page_index = "history";
}

$graph_data = get_graph_data($graph_date, $shift, $line->line);


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
    <link href="css/tableexport.css" rel="stylesheet" type="text/css">
    <link href="css/select2.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet"/>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js')}}"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js')}}"></script>
    <![endif]-->
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/moment.min.js"></script>
    <script src="js/select2.min.js"></script>

    <style>
        .back-div {
            background-color: #d6d6d8; height: 240px; width: 100%;text-align: center; padding: 10px;
        }

        .title {
            background-color: #fff;
            color: #000;
            border: 1px solid #000;
            height: 30px;
            text-align: center;
            padding: 2px;
            font-weight: bold;
            font-size: 20px;
        }

        .machine-name1 {
            background-color: #6cbd47;
            color: #000;
            border: 1px solid #000;
            height: 120px;
            text-align: center;
            font-weight: bold;
        }

        .casting {
            font-size: 46px;
            padding: 25px;
        }

        .inspection {
            font-size: 24px;
            padding: 30px;
        }

        .machine-name2 {
            background-color: #ed2025;
            color: #000;
            border: 1px solid #000;
            height: 120px;
            text-align: center;
            font-weight: bold;
        }

        .ip-address{
            background-color: #000;
            color: #dee01a;
            border: 1px solid #000;
            height: 38px;
            text-align: center;
            padding: 5px;
            font-weight: bold;
            font-size: 20px;
        }

        .link-data {
            cursor: pointer;
        }
    </style>
</head>

<body onload="startTime()">
<?php require_once("header.php"); ?>
<div class="container">
    <div class="row">
        <div class="col-md-12" style="padding: 10px;">
            <label class="pull-right" style=" margin-left: 10px;">BAD CONDITION</label>
            <div style="width: 30px; height: 20px; background-color: red; margin-left: 30px;" class="pull-right"></div>
            <label class="pull-right" style=" margin-left: 10px;">GOOD CONDITION</label>
            <div style="width: 30px; height: 20px; background-color: green; margin-left: 10px;" class="pull-right"></div>
        </div>

        <div class="col-md-12">
            <div class="col-md-offset-4 col-md-4" style="background-color: #d6d6d8; padding: 15px;">
                <div class="col-md-6 link-data" style="padding: 10px 20px;">
                    <div class="title">CASTING</div>
                    <?php
                    if(isset($graph_data['casting_h1_1']) && $graph_data['casting_h1_1'] == 1)
                        echo '<div class="machine-name1 casting">H1</div>';
                    else
                        echo '<div class="machine-name2 casting">H1</div>';
                    ?>
                    <div class="ip-address">192.168.1.10</div>
                </div>
                <div class="col-md-6 link-data" style="padding: 10px 20px;">
                    <div class="title">CASTING</div>
                    <?php
                    if(isset($graph_data['casting_h2_1']) && $graph_data['casting_h2_1'] == 1)
                        echo '<div class="machine-name1 casting">H2</div>';
                    else
                        echo '<div class="machine-name2 casting">H2</div>';
                    ?>

                    <div class="ip-address">192.168.1.10</div>
                </div>
                <div class="col-md-6 link-data" style="padding: 10px 20px;">
                    <div class="title">CASTING</div>
                    <?php
                    if(isset($graph_data['casting_h1_2']) && $graph_data['casting_h1_2'] == 1)
                        echo '<div class="machine-name1 casting">H1</div>';
                    else
                        echo '<div class="machine-name2 casting">H1</div>';
                    ?>

                    <div class="ip-address">192.168.1.10</div>
                </div>
                <div class="col-md-6 link-data" style="padding: 10px 20px;">
                    <div class="title">CASTING</div>
                    <?php
                    if(isset($graph_data['casting_h2_2']) && $graph_data['casting_h2_2'] == 1)
                        echo '<div class="machine-name1 casting">H2</div>';
                    else
                        echo '<div class="machine-name2 casting">H2</div>';
                    ?>

                    <div class="ip-address">192.168.1.10</div>
                </div>

            </div>
        </div>
        <div class="col-md-12" style="margin-top: 20px;">
            <div class="col-md-6" style="padding: 20px;">
                <div class="back-div">
                    <h3>CASTING INSPECTION</h3>
                    <div class="col-md-6 link-data" style="padding: 10px 30px;">
                        <?php
                        if(isset($graph_data['inspection_h1']) && $graph_data['inspection_h1'] == 1)
                            echo '<div class="machine-name1 inspection">INSPECTION H1</div>';
                        else
                            echo '<div class="machine-name2 inspection">INSPECTION H1</div>';
                        ?>
                        <div class="ip-address">192.168.1.10</div>
                    </div>
                    <div class="col-md-6 link-data" style="padding: 10px 30px;">
                        <?php
                        if(isset($graph_data['inspection_h2']) && $graph_data['inspection_h2'] == 1)
                            echo '<div class="machine-name1 inspection">INSPECTION H2</div>';
                        else
                            echo '<div class="machine-name2 inspection">INSPECTION H2</div>';
                        ?>

                        <div class="ip-address">192.168.1.10</div>
                    </div>
                </div>

            </div>
            <div class="col-md-3" style="padding: 20px;">
                <div class="back-div">
                    <h3>DEBURRING INSPECTION</h3>
                    <div class="col-md-12 link-data" style="padding: 10px 30px;">
                        <?php
                        if(isset($graph_data['inspection']) && $graph_data['inspection'] == 1)
                            echo '<div class="machine-name1 inspection">INSPECTION</div>';
                        else
                            echo '<div class="machine-name2 inspection">INSPECTION</div>';
                        ?>

                        <div class="ip-address">192.168.1.10</div>
                    </div>
                </div>

            </div>
            <div class="col-md-3" style="padding: 20px;">
                <div class="back-div">
                    <h3>HEAT TREATMENT</h3>
                    <div class="col-md-12 link-data" style="padding: 10px 30px;">
                        <?php
                        if(isset($graph_data['heat_treatment']) && $graph_data['heat_treatment'] == 1)
                            echo '<div class="machine-name1 inspection">HEAT TREATMENT</div>';
                        else
                            echo '<div class="machine-name2 inspection">HEAT TREATMENT</div>';
                        ?>

                        <div class="ip-address">192.168.1.10</div>
                    </div>
                </div>

            </div>
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
<script src="js/tableexport.min.js"></script>

<script>
    $(document).ready(function () {

        $(".link-data").on('click', function () {
            location.href = "view_data.php";
        });

    });
</script>
</html>