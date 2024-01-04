<?php
require_once("./config/config.php");
require_once("functions.php");
$page_name = 'Traceability System Master Maintenance Menu';
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

    <link href="css/style.css" rel="stylesheet"/>
    <link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js')}}"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js')}}"></script>
    <![endif]-->
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/moment.min.js"></script>

    <link href="css/chosen.css" rel="stylesheet"/>
    <script src="js/chosen.jquery.js"></script>

    <link rel="stylesheet" href="css/font-awesome.css">
    <link rel="stylesheet" href="assets/css/jquery.mCustomScrollbar.min.css" />
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="assets/css/custom-themes.css">

    <style>
        /* Brand Blocks */
        .brandrow {
            padding: 0;
            margin: 0;
            list-style: none;
            display: flex;
            flex-flow: row wrap;
            justify-content: space-around;
        }

        .brandblock {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 5px;
            width: 260px;
            height: 260px;
            margin-top: 34px;
            font-size: 24px;
            line-height: 30px;
            text-align: center;
        }

        .brandblock a {
            display: flex;
            align-items: center;
            height: 100%;
            width: 100%;
            text-decoration: none;
        }

        .brandblock a:hover {
            opacity: .65;
        }

        .brandblock span {
            text-align: center;
            width: 100%;
        }

        .gold {
            background: #af9775;
        }

        .gold a {
            color: #fff;
        }

        .lightgold {
            background: #C3B197;
        }

        .lightgold a {
            color: #fff;
        }

        .grey {
            background: #e5e5e5;
        }

        .grey a {
            color: #363636;
        }

        .warmgrey {
            background: #E8E7E1;
        }

        .warmgrey a {
            color: #363636;
        }

        @media only screen and (max-width: 767px) {
            .brandblock {
                width: 80%;
                height: 130px;
                margin-top: 15px;
            }
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
                <ul class="brandrow">
                    <li class="brandblock gold"><a href="master.php?do=line-master" target="_blank"><span>Line Master</span></a>
                    </li>
                    <li class="brandblock grey"><a href="master.php?do=group-master" target="_blank"><span>Group Master</span></a>
                    </li>
                    <li class="brandblock warmgrey"><a href="master.php?do=error-master"
                                                       target="_blank"><span>Error Code Master</span></a></li>
                    <li class="brandblock lightgold"><a href="master.php?do=engine-type-master" target="_blank"><span>Engine Type Master</span></a>
                    </li>
                    <li class="brandblock warmgrey"><a href="master.php?do=shift-master"
                                                       target="_blank"><span>Shift Master</span></a></li>
                    <li class="brandblock lightgold"><a href="master.php?do=defect-master"
                                                        target="_blank"><span>Defect Master</span></a></li>
                    <li class="brandblock grey"><a href="master.php?do=equipment-master"
                                                   target="_blank"><span>Equipment Master</span></a></li>
                    <li class="brandblock warmgrey"><a href="master.php?do=worker-master" target="_blank"><span>Worker Master</span></a>
                    </li>
                    <li class="brandblock lightgold"><a href="master.php?do=automatic-creation" target="_blank"><span>Automatic Creation</span></a>
                    </li>
                    <li class="brandblock grey"><a href="master.php?do=scale-master" target="_blank"><span>Scale Master</span></a>
                    </li>
                    <li class="brandblock warmgrey"><a href="master.php?do=die-change-master" target="_blank"><span>Die Change Master</span></a>
                    </li>
                    <li class="brandblock gold"><a href="#"><span>End</span></a></li>
                </ul>
            </div>
        </div>
    </main>
</div>

<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-datepicker.min.js"></script>
<script src="js/custom.js"></script>

<script src="assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="assets/js/custom.js"></script>

</body>
</html>