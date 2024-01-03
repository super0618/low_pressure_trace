<?php
require_once("./config/config.php");
require_once("functions.php");
$page_name = "Defect Overview";

?>

<?php
$query = $db->query("SELECT * FROM {$tblDefectsSetting}");

$defects = array();

$default_start_time = date('Y-m-d 00:00:00');
$default_end_time = date('Y-m-d 23:59:59');
$total_internal = 0;
$total_external = 0;
$total_count = 0;
while ($setting = mysqli_fetch_assoc($query)) {
    $defect_count = $db->query("SELECT * FROM t_defects WHERE (defect = '".$setting['name']."' Or defect = '".$setting['value']."') And `timestamp` >= '".$default_start_time."' And `timestamp`<= '".$default_end_time."';")->num_rows;
    $setting['count'] = $defect_count;
    if($setting['in_ex'] == 'internal')
        $total_internal+=$defect_count;
    else if($setting['in_ex'] == 'external')
        $total_external+=$defect_count;
    array_push($defects, $setting);
}

foreach([1,2,3,4,5,6] as $index) {
    $total_count += ($db->query("SELECT * FROM t_lp_quality_" . $index . " WHERE `Date/time` >= '" . $default_start_time . "' And `Date/time` <= '" . $default_end_time . "';")->num_rows);
}

$total_defects = $total_internal + $total_external;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $page_name ?></title>
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
<!--    <script src="js/chart.js"></script>-->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link href="css/chosen.css" rel="stylesheet"/>
    <script src="js/chosen.jquery.js"></script>

    <link rel="stylesheet" href="css/font-awesome.css">
    <link rel="stylesheet" href="assets/css/jquery.mCustomScrollbar.min.css" />
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="assets/css/custom-themes.css">
</head>
<style>
    .text-bold {
        font-weight: bold;
    }
    .pressure-container{
        border: 1px solid black;
        padding: 0;
        position: relative;
        z-index: -1;
    }
    .left-container {
        border-right: 1px solid black;
        width: 50%;
        height: 100%;
        float: left;
    }
    .right-container {
        width: 50%;
        height: 100%;
        float: right;
    }
    .widget-container {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }
    .bg-gray{
        background-color: gray;
    }
    .text-white {
        color: white;
    }
    .pressure-title {
        text-align: center;
        z-index: 10;
        position: relative;
    }
    .title-text {
        padding: 8px 16px;
        position: relative;
        top: 10px;
        font-size: 26px;
    }
    .internal-text {
        padding: 16px 8px;
        writing-mode: vertical-lr;
        transform: rotate(180deg);
        font-size: 26px;
    }
    .internal-container {
        padding: 16px;
    }
    .internal-total {
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -webkit-flex-direction: column;
        -ms-flex-direction: column;
        flex-direction: column;
        border: 1px solid #000;
        height: auto;
        margin-top: 4px;
        width: auto;
    }
    .div-block-8 {
        text-align: center;
    }
    .text-block {
        position: static;
        right: auto;
        bottom: auto;
        display: inline-block;
        width: 100%;
        padding-right: 4px;
        padding-left: 4px;
        background-color: grey;
        color: #fff;
        font-size: 18px;
    }
    .div-block-9 {
        -webkit-box-flex: 0;
        -webkit-flex: 0 auto;
        -ms-flex: 0 auto;
        flex: 0 auto;
    }
    .text-gray {
        color: gray;
    }
    .text-red {
        color: #e74026;
    }
    .total-text {
        font-size: 96px;
        line-height: 62px;
        text-align: center;
        padding: 24px 24px;
    }
    .defect-card {
        border: 1px solid #000;
        margin: 4px;
        height: 80px;
        /*max-width: 25%;*/
    }

    .defect-card-title {
        padding: 4px 8px;
        background-color: grey;
        color: #fff;
        font-size: 16px;
        white-space: nowrap;
        text-transform: uppercase;
    }
    .internal-chart{
        /*height: 250px;*/
        margin-top: 16px;
        max-height: 180px;
    }
    .text-block-8 {
        font-size: 36px;
        text-align: center;
    }
    .total-defects-container{
        border: 1px solid black;
        z-index: 20;
        background: white;
        flex-basis: ;
    }
</style>
<body onload="startTime()">
    <div class="page-wrapper chiller-theme">
        <?php include('menu.php'); ?>
        <main class="page-content">
            <div class="container-fluid">
                <div class="row"><?php include("header.php"); ?></div>
                <div class="row" style="margin-top: 20px;">
                    <div class="pressure-title"><span class="bg-gray text-white title-text"">LOW PRESSURE</span></div>
                    <div class="pressure-container">
                        <div style="position: absolute; left: 0; top: 0; width: 100%; height: 100%">
                            <div class="left-container"></div>
                            <div class="right-container"></div>
                        </div>
                        <div class="widget-container">
                            <div style="position: absolute">
                                <span class="bg-gray text-white internal-text">INTERNAL</span>
                            </div>
                            <div class="container">
                                <div class="row" style="padding: 16px; display: flex; justify-content: center; align-items: center">
                                    <div class="col-md-5">
                                        <div class="internal-container">
                                            <div class="internal-defect">
                                                <div class="row" style="display: flex">
                                                    <div class="col-md-4">
                                                        <div class="internal-total">
                                                            <div class="text-center">
                                                                <strong class="text-block">TOTAL INTERNAL</strong>
                                                            </div>
                                                            <div class="div-block-9">
                                                                <?= '<div class="total-text text-bold '.($total_internal != 0 ? 'text-red' : 'text-gray').'" id="total-internal-text">'.$total_internal.'</div>' ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8" id="defect_container_lp_internal" style="display: flex; flex-wrap: wrap">
                                                        <?php
                                                            foreach ($defects as $defect) {
                                                                if($defect['in_ex'] == 'internal')
                                                                    echo '
                                                                        <div class="defect-card">
                                                                            <div class="text-center">
                                                                                <div class="defect-card-title">'.$defect['name'].'</div>
                                                                            </div>
                                                                            <div>
                                                                                <div class="text-block-8 text-bold '.($defect['count'] == 0 ? 'text-gray' : 'text-red').'" id="text-defect-'.$defect['id'].'">'.$defect['count'].'</div>
                                                                            </div>
                                                                        </div>
                                                                    ';
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="internal-chart">
                                                <canvas id="internal-chart-lp" width="100%" style="width: 100%"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="total-defects-container">
                                            <div class="text-center">
                                                <div><strong class="text-block" style="font-size: 26px">TOTAL DEFECTS</strong></div>
                                            </div>
                                            <div class="div-block-9">
                                                <?= '<div class="total-text text-bold '.($total_defects == 0 ? 'text-gray' : 'text-red').'" id="total-defect-text" style="font-size: 108px">'.$total_count.'</div>' ?>
                                            </div>
                                            <div class="div-block-9" style="margin: 8px; border-top: 1px solid black">
                                                <div class="total-text text-bold text-gray" id="total-percent" style="font-size: 84px;"><?= $total_count == 0 ? '0%' : (''.intval($total_defects/$total_count*100).'%') ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="internal-container">
                                            <div class="internal-defect">
                                                <div class="row" style="display: flex">
                                                    <div class="col-md-4">
                                                        <div class="internal-total">
                                                            <div class="text-center">
                                                                <strong class="text-block">TOTAL EXTERNAL</strong>
                                                            </div>
                                                            <div class="div-block-9">
                                                                <?= '<div class="total-text text-bold '.($total_external != 0 ? 'text-red' : 'text-gray').'" id="total-external">'.$total_external.'</div>' ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8" id="defect_container_lp_external" style="display: flex; flex-wrap: wrap">
                                                        <?php
                                                        foreach ($defects as $defect) {
                                                            if($defect['in_ex'] == 'external')
                                                                echo '
                                                                        <div class="defect-card">
                                                                            <div class="text-center">
                                                                                <div class="defect-card-title">'.$defect['name'].'</div>
                                                                            </div>
                                                                            <div>
                                                                                <div class="text-block-8 text-bold '.($defect['count'] == 0 ? 'text-gray' : 'text-red').'" id="text-defect-'.$defect['id'].'">'.$defect['count'].'</div>
                                                                            </div>
                                                                        </div>
                                                                    ';
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="internal-chart">
                                                <canvas id="external-chart-lp" width="100%" style="width: 100%"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div style="position: absolute; right: 0">
                                <span class="bg-gray text-white internal-text">EXTERNAL</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 20px;">
                    <div class="pressure-title"><span class="bg-gray text-white title-text"">HIGH PRESSURE</span></div>
                    <div class="pressure-container">
                        <div style="position: absolute; left: 0; top: 0; width: 100%; height: 100%">
                            <div class="left-container"></div>
                            <div class="right-container"></div>
                        </div>
                        <div class="widget-container">
                            <div style="position: absolute">
                                <span class="bg-gray text-white internal-text">INTERNAL</span>
                            </div>
                            <div class="container">
                                <div class="row" style="padding: 16px; display: flex; justify-content: center; align-items: center">
                                    <div class="col-md-5">
                                        <div class="colinternal-container">
                                            <div class="internal-defect">
                                                <div class="row" style="display: flex">
                                                    <div class="col-md-4">
                                                        <div class="internal-total">
                                                            <div class="text-center">
                                                                <strong class="text-block">DEFECT</strong>
                                                            </div>
                                                            <div class="div-block-9">
                                                                <div class="total-text text-gray"><strong>0</strong></div>
                                                            </div>
                                                        </div>
                                                        <div class="internal-total">
                                                            <div class="text-center">
                                                                <strong class="text-block">HUS</strong>
                                                            </div>
                                                            <div class="div-block-9">
                                                                <div class="total-text text-red" style="font-size: 72px"><strong>15</strong></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8" style="display: flex; flex-wrap: wrap">
                                                        <table style="width: 100%;">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="width: 40px;">
                                                                        <div class="text-center">
                                                                            <div class="defect-card-title" style="height: 110px; display: flex; justify-content: center; align-items: center">H1</div>
                                                                        </div>
                                                                    </td>
                                                                    <td style="width: 150px;">
                                                                        <div class="defect-card" style="height: 110px; ">
                                                                            <div class="text-center">
                                                                                <div class="defect-card-title">DEFECT</div>
                                                                            </div>
                                                                            <div>
                                                                                <div class="text-block-8 text-gray" style="font-size:60px;"><strong>0</strong></div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td style="width: 150px;">
                                                                        <div class="defect-card" style="height: 110px; ">
                                                                            <div class="text-center">
                                                                                <div class="defect-card-title">HUS</div>
                                                                            </div>
                                                                            <div>
                                                                                <div class="text-block-8 text-red" style="font-size:60px;"><strong>6</strong></div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="width: 40px;">
                                                                        <div class="text-center">
                                                                            <div class="defect-card-title" style="height: 110px; display: flex; justify-content: center; align-items: center">H2</div>
                                                                        </div>
                                                                    </td>
                                                                    <td style="width: 150px;">
                                                                        <div class="defect-card" style="height: 110px; ">
                                                                            <div class="text-center">
                                                                                <div class="defect-card-title">DEFECT</div>
                                                                            </div>
                                                                            <div>
                                                                                <div class="text-block-8 text-gray" style="font-size:60px;"><strong>0</strong></div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td style="width: 150px;">
                                                                        <div class="defect-card" style="height: 110px; ">
                                                                            <div class="text-center">
                                                                                <div class="defect-card-title">HUS</div>
                                                                            </div>
                                                                            <div>
                                                                                <div class="text-block-8 text-red" style="font-size:60px;"><strong>9</strong></div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
<!--                                                        <div class="defect-card">-->
<!--                                                            <div class="text-center">-->
<!--                                                                <div class="defect-card-title">DEFECT</div>-->
<!--                                                            </div>-->
<!--                                                            <div>-->
<!--                                                                <div class="text-block-8 text-gray"><strong>0</strong></div>-->
<!--                                                            </div>-->
<!--                                                        </div>-->
<!--                                                        <div class="defect-card">-->
<!--                                                            <div class="div-block-8 defect-card-title-container">-->
<!--                                                                <div class="defect-card-title">HUS</div>-->
<!--                                                            </div>-->
<!--                                                            <div>-->
<!--                                                                <div class="text-block-8 text-red"><strong>6</strong></div>-->
<!--                                                            </div>-->
<!--                                                        </div>-->
<!--                                                        <div class="defect-card">-->
<!--                                                            <div class="div-block-8 defect-card-title-container">-->
<!--                                                                <div class="defect-card-title">DEFECT</div>-->
<!--                                                            </div>-->
<!--                                                            <div>-->
<!--                                                                <div class="text-block-8 text-gray"><strong>0</strong></div>-->
<!--                                                            </div>-->
<!--                                                        </div>-->
<!--                                                        <div class="defect-card">-->
<!--                                                            <div class="div-block-8 defect-card-title-container">-->
<!--                                                                <div class="defect-card-title">HUS</div>-->
<!--                                                            </div>-->
<!--                                                            <div>-->
<!--                                                                <div class="text-block-8 text-red"><strong>9</strong></div>-->
<!--                                                            </div>-->
<!--                                                        </div>-->
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="internal-chart">
                                                <canvas id="internal-chart-hp" width="100%" style="width: 100%"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="total-defects-container" style="position: relative; top: -50px;">
                                            <div class="text-center" >
                                                <div><strong class="text-block" style="font-size: 26px">TOTAL DEFECTS</strong></div>
                                            </div>
                                            <div class="div-block-9">
                                                <div class="total-text text-red" style="font-size: 108px"><strong>20</strong></div>
                                            </div>
                                            <div class="div-block-9" style="margin: 8px; border-top: 1px solid black">
                                                <div class="total-text text-gray" style="font-size: 84px;"><strong>1%</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="colinternal-container">
                                            <div class="internal-defect">
                                                <div class="row" style="display: flex">
                                                    <div class="col-md-8" style="display: flex; flex-wrap: wrap; flex-direction: row-reverse">
                                                        <table style="width: 100%">
                                                            <tbody>
                                                            <tr>
                                                                <td style="width: 40px;">
                                                                    <div class="text-center">
                                                                        <div class="defect-card-title" style="height: 110px; display: flex; justify-content: center; align-items: center">H1</div>
                                                                    </div>
                                                                </td>
                                                                <td style="width: 150px;">
                                                                    <div class="defect-card" style="height: 110px; ">
                                                                        <div class="text-center">
                                                                            <div class="defect-card-title">VISUAL</div>
                                                                        </div>
                                                                        <div>
                                                                            <div class="text-block-8 text-red" style="font-size:60px;"><strong>4</strong></div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td style="width: 150px;">
                                                                    <div class="defect-card" style="height: 110px; ">
                                                                        <div class="text-center">
                                                                            <div class="defect-card-title">LTF</div>
                                                                        </div>
                                                                        <div>
                                                                            <div class="text-block-8 text-gray" style="font-size:60px;"><strong>0</strong></div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 40px;">
                                                                    <div class="text-center">
                                                                        <div class="defect-card-title" style="height: 110px; display: flex; justify-content: center; align-items: center">H2</div>
                                                                    </div>
                                                                </td>
                                                                <td style="width: 150px;">
                                                                    <div class="defect-card" style="height: 110px; ">
                                                                        <div class="text-center">
                                                                            <div class="defect-card-title">VISUAL</div>
                                                                        </div>
                                                                        <div>
                                                                            <div class="text-block-8 text-red" style="font-size:60px;"><strong>1</strong></div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td style="width: 150px;">
                                                                    <div class="defect-card" style="height: 110px; ">
                                                                        <div class="text-center">
                                                                            <div class="defect-card-title">LTF</div>
                                                                        </div>
                                                                        <div>
                                                                            <div class="text-block-8 text-gray" style="font-size:60px;"><strong>0</strong></div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="internal-total">
                                                            <div class="text-center">
                                                                <strong class="text-block">VISUAL</strong>
                                                            </div>
                                                            <div class="div-block-9">
                                                                <div class="total-text text-red"><strong>5</strong></div>
                                                            </div>
                                                        </div>
                                                        <div class="internal-total">
                                                            <div class="text-center">
                                                                <strong class="text-block">LTF</strong>
                                                            </div>
                                                            <div class="div-block-9">
                                                                <div class="total-text text-gray" style="font-size: 72px"><strong>0</strong></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="internal-chart">
                                                <canvas id="external-chart-hp" width="100%" style="width: 100%"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div style="position: absolute; right: 0">
                                <span class="bg-gray text-white internal-text">EXTERNAL</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div class="my-alert alert alert-success hide" id="success-alert">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong id="alert_title">Success! </strong>
        <span id="success-message">Saved successfully.</span>
    </div>

    <div class="my-alert alert alert-danger hide" id="fault-alert">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong id="fault_title">Fail! </strong>
        <span id="fault-message">Saved failed.</span>
    </div>

</body>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-timepicker.min.js"></script>
<script src="js/custom.js"></script>
<script type='text/javascript'>
    <?php
        $js_array = json_encode($defects);
        echo "var defect_lp = ". $js_array . ";\n";
    ?>
</script>
<script>
    $(document).ready(() => {
        var myExternalChart, myInternalChart;
        var refreshPage = () => {
            $.ajax({
                url: "defects_action.php",
                method: "POST",
                dataType: "json",
                data: {
                    "action": "getDefectsToday"
                },
            }).done(response => {
                var total_lp = response.total_lp;
                var defect_lp = response.defect_lp;
                var total_defects = 0;
                var total_internal = 0;
                var total_external = 0;
                for(var i = 0; i < defect_lp.length; i++) {
                    var text_defect_element = $(`#text-defect-${defect_lp[i].id}`);
                    if(text_defect_element.length === 0){
                        if(defect_lp[i].in_ex === 'internal'){
                            $("#defect_container_lp_internal").append('' +
                                '<div class="defect-card">'+
                                    '<div class="text-center">'+
                                        `<div class="defect-card-title">${defect_lp[i]['name']}</div>`+
                                    '</div>'+
                                    '<div>'+
                                    '<div class="text-block-8 text-bold ' + (defect_lp[i]['count'] == 0 ? 'text-gray' : 'text-red')+'" id="text-defect-' + defect_lp[i]['id'] + '">' + defect_lp[i]['count'] + '</div>'+
                                '</div>'+
                            '</div>');
                        }
                        else if(defect_lp[i].in_ex === 'external')
                        {
                            $("#defect_container_lp_external").append('' +
                                '<div class="defect-card">'+
                                '<div class="text-center">'+
                                `<div class="defect-card-title">${defect_lp[i]['name']}</div>`+
                                '</div>'+
                                '<div>'+
                                '<div class="text-block-8 text-bold ' + (defect_lp[i]['count'] == 0 ? 'text-gray' : 'text-red')+'" id="text-defect-' + defect_lp[i]['id'] + '">' + defect_lp[i]['count'] + '</div>'+
                                '</div>'+
                                '</div>');
                        }
                    } else {
                        text_defect_element.text(defect_lp[i].count)
                        if(defect_lp[i].count === 0) {
                            text_defect_element.removeClass("text-red");
                            text_defect_element.addClass("text-gray");
                        } else {
                            text_defect_element.removeClass("text-gray");
                            text_defect_element.addClass("text-red");
                        }
                    }
                    if(defect_lp[i].in_ex === 'internal')
                        total_internal += defect_lp[i].count;
                    else if(defect_lp[i].in_ex === 'external')
                        total_external += defect_lp[i].count;
                }
                total_defects = total_internal + total_external;
                $("#total-defect-text").text(total_defects);
                if(total_defects === 0) {
                    $("#total-defect-text").removeClass('text-red');
                    $("#total-defect-text").addClass('text-gray');
                } else {
                    $("#total-defect-text").removeClass('text-gray');
                    $("#total-defect-text").addClass('text-red');
                }

                $("#total-internal-text").text(total_internal);
                if(total_internal === 0) {
                    $("#total-internal-text").removeClass('text-red');
                    $("#total-internal-text").addClass('text-gray');
                } else {
                    $("#total-internal-text").removeClass('text-gray');
                    $("#total-internal-text").addClass('text-red');
                }

                $("#total-external-text").text(total_external);
                if(total_external === 0) {
                    $("#total-external-text").removeClass('text-red');
                    $("#total-external-text").addClass('text-gray');
                } else {
                    $("#total-external-text").removeClass('text-gray');
                    $("#total-external-text").addClass('text-red');
                }

                if(total_lp === 0) {
                    $("#total-percent").text("0%");
                } else {
                    $("#total-percent").text(parseInt(total_defects/total_lp*100) + "%");
                }

                var labels = defect_lp.filter((defect) => defect.in_ex === "internal").map(defect => defect.name);
                var datas = defect_lp.filter((defect) => defect.in_ex === "internal").map(defect => defect.count);
                datas.push(1);
                var data = {
                    labels: labels,
                    datasets: [{
                        backgroundColor: '#e74026',
                        borderColor: '#e74026',
                        data: datas,
                    }]
                };

                var config = {
                    type: 'bar',
                    data: data,
                    options: {
                        maintainAspectRatio: false,
                        scales:{
                            y: {
                                ticks: {
                                    stepSize: 1,
                                    fontSize: 18
                                }
                            },
                            x: {
                                ticks: {
                                    fontSize: 18
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                };

                myInternalChart.destroy();
                myInternalChart = new Chart(
                    document.getElementById('internal-chart-lp'),
                    config
                );

                labels = defect_lp.filter((defect) => defect.in_ex === "external").map(defect => defect.name);
                datas = defect_lp.filter((defect) => defect.in_ex === "external").map(defect => defect.count);
                data = {
                    labels: labels,
                    datasets: [{
                        backgroundColor: '#e74026',
                        borderColor: '#e74026',
                        data: datas,
                    }]
                };

                config = {
                    type: 'bar',
                    data: data,
                    options: {
                        maintainAspectRatio: false,
                        scales:{
                            y: {
                                ticks: {
                                    stepSize: 1,
                                    fontSize: 18
                                }
                            },
                            x: {
                                ticks: {
                                    fontSize: 18
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                };

                myExternalChart.destroy();
                myExternalChart = new Chart(
                    document.getElementById('external-chart-lp'),
                    config
                );
            });
            setTimeout(refreshPage, 10000);
        }

        var labels = defect_lp.filter((defect) => defect.in_ex === "internal").map(defect => defect.name);
        var datas = defect_lp.filter((defect) => defect.in_ex === "internal").map(defect => defect.count);
        var data = {
            labels: labels,
            datasets: [{
                backgroundColor: '#e74026',
                borderColor: '#e74026',
                data: datas,
            }]
        };

        var config = {
            type: 'bar',
            data: data,
            options: {
                maintainAspectRatio: false,
                scales:{
                    y: {
                        ticks: {
                            stepSize: 1,
                            fontSize: 18
                        }
                    },
                    x: {
                        ticks: {
                            fontSize: 18
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        };

        myInternalChart = new Chart(
            document.getElementById('internal-chart-lp'),
            config
        );

        labels = defect_lp.filter((defect) => defect.in_ex === "external").map(defect => defect.name);
        datas = defect_lp.filter((defect) => defect.in_ex === "external").map(defect => defect.count)
        data = {
            labels: labels,
            datasets: [{
                backgroundColor: '#e74026',
                borderColor: '#e74026',
                data: datas,
            }]
        };

        config = {
            type: 'bar',
            data: data,
            options: {
                maintainAspectRatio: false,
                scales:{
                    y: {
                        ticks: {
                            stepSize: 1,
                            fontSize: 18
                        }
                    },
                    x: {
                        ticks: {
                            fontSize: 18
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        };

        myExternalChart = new Chart(
            document.getElementById('external-chart-lp'),
            config
        );

        refreshPage();
    });
</script>
<script src="assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="assets/js/custom.js"></script>
</html>

