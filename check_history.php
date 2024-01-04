<?php
require_once("./config/config.php");
require_once("functions.php");

$page_name = "Check History";

$pageSize = 50;
$pageNo = $_GET['pageNo'] ?? 0;


//$result = $db->query("SELECT * FROM t_ng_check_history LIMIT " . $pageSize * $pageNo . ", " . $pageSize);
$result = $db->query("SELECT * FROM t_ng_check_history");
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
</head>
<body onload="startTime()">

<div class="page-wrapper chiller-theme">
    <?php
    include('menu.php');
    ?>
    <main class="page-content">
        <div class="container-fluid">
            <div class="row">
                <?php
                include('header.php');
                ?>
            </div>

            <div class="row" style="padding-top: 30px;">
                <table style="display: none">
                    <thead>
                    <tr>
                        <th>Serial</th>
                        <th>Machine No</th>
                        <th>Check time</th>
                        <th>Inspection result 1</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = mysqli_fetch_object($result)) { ?>
                        <tr>
                            <td><?php echo $row->serial ?></td>
                            <td><?php echo $row->machine ?></td>
                            <td><?php echo $row->check_time ?></td>
                            <?php
                            $column = $row->inspection_result_1;
                            if ($column == "0")
                                $column = "<td style='background-color: green; color: white;'>OK</td>";
                            else
                                $column = "<td style='background-color: red; color: white;'>NG</td>";
                            echo $column;
                            ?>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
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

    td {
        text-align: center;
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
        $('table').DataTable({
            // scrollY: "500px",
            // scrollX: true,
            scrollCollapse: true,
            paging: false,
            fixedColumns: {
                leftColumns: 4
            },
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel'
            ]
        });

        $('table').show();
    });
</script>

</html>
