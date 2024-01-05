<?php
$page_name = 'Admin: Error Code Master';
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'Add') {
        $query = "INSERT INTO {$tblerrormaster} (`MachineType`,  `ErrorCode`, `ErrorContent`) VALUES ('" . $_POST['MachineType'] . "','" . $_POST['ErrorCode'] . "', '" . $_POST['ErrorContent'] . "');";

        $result = $db->query($query);
    } elseif ($_POST['action'] == 'Delete') {
        $query = "DELETE FROM {$tblerrormaster} WHERE `MachineType` = '" . $_POST['MachineType'] . "' AND `ErrorCode` = '" . $_POST['ErrorCode'] . "';";

        $result = $db->query($query);
    } elseif ($_POST['action'] == 'Update') {
        $query = "UPDATE {$tblerrormaster} SET  `ErrorContent` = '" . $_POST['ErrorContent'] . "'  WHERE `MachineType` = '" . $_POST['MachineType'] . "' AND `ErrorCode` = '" . $_POST['ErrorCode'] . "';";

        $result = $db->query($query);
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

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="css/font-awesome.css">
    <link rel="stylesheet" href="assets/css/jquery.mCustomScrollbar.min.css"/>
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="assets/css/custom-themes.css">

    <style>
        /*Modal */
        body {
            color: #566787;
            background: #f5f5f5;
            font-family: 'Varela Round', sans-serif;
            font-size: 13px;
        }

        .table-wrapper {
            background: #fff;
            padding: 20px 25px;
            margin: 30px 0;
            border-radius: 3px;
            box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        }

        .table-title {
            padding-bottom: 15px;
            background: #435d7d;
            color: #fff;
            padding: 16px 30px;
            margin: -20px -25px 10px;
            border-radius: 3px 3px 0 0;
        }

        .table-title h2 {
            margin: 5px 0 0;
            font-size: 24px;
        }

        .table-title .btn-group {
            float: right;
        }

        .table-title .btn {
            color: #fff;
            float: right;
            font-size: 13px;
            border: none;
            min-width: 50px;
            border-radius: 2px;
            border: none;
            outline: none !important;
            margin-left: 10px;
        }

        .table-title .btn i {
            float: left;
            font-size: 21px;
            margin-right: 5px;
        }

        .table-title .btn span {
            float: left;
            margin-top: 2px;
        }

        table.table tr th, table.table tr td {
            border-color: #e9e9e9;
            padding: 12px 15px;
            vertical-align: middle;
        }

        table.table tr th:first-child {
            width: 60px;
        }

        table.table tr th:last-child {
            width: 100px;
        }

        table.table-striped tbody tr:nth-of-type(odd) {
            background-color: #fcfcfc;
        }

        table.table-striped.table-hover tbody tr:hover {
            background: #f5f5f5;
        }

        table.table th i {
            font-size: 13px;
            margin: 0 5px;
            cursor: pointer;
        }

        table.table td:last-child i {
            opacity: 0.9;
            font-size: 22px;
            margin: 0 5px;
        }

        table.table td a {
            font-weight: bold;
            color: #566787;
            display: inline-block;
            text-decoration: none;
            outline: none !important;
        }

        table.table td a:hover {
            color: #2196F3;
        }

        table.table td a.edit {
            color: #FFC107;
        }

        table.table td a.delete {
            color: #F44336;
        }

        table.table td i {
            font-size: 19px;
        }

        table.table .avatar {
            border-radius: 50%;
            vertical-align: middle;
            margin-right: 10px;
        }

        .pagination {
            float: right;
            margin: 0 0 5px;
        }

        .pagination li a {
            border: none;
            font-size: 13px;
            min-width: 30px;
            min-height: 30px;
            color: #999;
            margin: 0 2px;
            line-height: 30px;
            border-radius: 2px !important;
            text-align: center;
            padding: 0 6px;
        }

        .pagination li a:hover {
            color: #666;
        }

        .pagination li.active a, .pagination li.active a.page-link {
            background: #03A9F4;
        }

        .pagination li.active a:hover {
            background: #0397d6;
        }

        .pagination li.disabled i {
            color: #ccc;
        }

        .pagination li i {
            font-size: 16px;
            padding-top: 6px
        }

        .hint-text {
            float: left;
            margin-top: 10px;
            font-size: 13px;
        }

        /* Custom checkbox */
        .custom-checkbox {
            position: relative;
        }

        .custom-checkbox input[type="checkbox"] {
            opacity: 0;
            position: absolute;
            margin: 5px 0 0 3px;
            z-index: 9;
        }

        .custom-checkbox label:before {
            width: 18px;
            height: 18px;
        }

        .custom-checkbox label:before {
            content: '';
            margin-right: 10px;
            display: inline-block;
            vertical-align: text-top;
            background: white;
            border: 1px solid #bbb;
            border-radius: 2px;
            box-sizing: border-box;
            z-index: 2;
        }

        .custom-checkbox input[type="checkbox"]:checked + label:after {
            content: '';
            position: absolute;
            left: 6px;
            top: 3px;
            width: 6px;
            height: 11px;
            border: solid #000;
            border-width: 0 3px 3px 0;
            transform: inherit;
            z-index: 3;
            transform: rotateZ(45deg);
        }

        .custom-checkbox input[type="checkbox"]:checked + label:before {
            border-color: #03A9F4;
            background: #03A9F4;
        }

        .custom-checkbox input[type="checkbox"]:checked + label:after {
            border-color: #fff;
        }

        .custom-checkbox input[type="checkbox"]:disabled + label:before {
            color: #b8b8b8;
            cursor: auto;
            box-shadow: none;
            background: #ddd;
        }

        /* Modal styles */
        .modal .modal-dialog {
            max-width: 400px;
        }

        .modal .modal-header, .modal .modal-body, .modal .modal-footer {
            padding: 20px 30px;
        }

        .modal .modal-content {
            border-radius: 3px;
        }

        .modal .modal-footer {
            background: #ecf0f1;
            border-radius: 0 0 3px 3px;
        }

        .modal .modal-title {
            display: inline-block;
        }

        .modal .form-control {
            border-radius: 2px;
            box-shadow: none;
            border-color: #dddddd;
        }

        .modal textarea.form-control {
            resize: vertical;
        }

        .modal .btn {
            border-radius: 2px;
            min-width: 100px;
        }

        .modal form label {
            font-weight: normal;
        }

        /* /modal */
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
                <div class="table-wrapper">
                    <div class="table-title">
                        <div class="row">
                            <div class="col-sm-6">
                                <h2><b>Error Code Master</b></h2>
                            </div>
                            <div class="col-sm-6">
                                <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i>
                                    <span>Add New Error Code Master</span></a>

                            </div>
                        </div>
                    </div>
                    <table class="table table-striped table-hover" style="width:100%;">
                        <thead>
                        <tr>

                            <th>Equipment Code</th>
                            <th>Error Code</th>
                            <th>Error Content</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        $query = "SELECT   *  FROM {$tblerrormaster} ";
                        $result = $db->query($query);
                        $data = array();
                        while ($row = mysqli_fetch_array($result)) {
                            array_push($data, $row);
                        }
                        // print_r($data);


                        foreach ($data as $item) {
                            echo '<tr>';
                            echo '<td>' . $item['MachineType'] . '</td>';
                            echo '<td>' . $item['ErrorCode'] . '</td>';
                            echo '<td style="text-lign:left;">' . $item['ErrorContent'] . '</td>';
                            echo '<td>
                            <a href="#editEmployeeModal" class="edit editModal" data-toggle="modal"  data-machinetype="' . $item['MachineType'] . '" data-errorcode="' . $item['ErrorCode'] . '" data-errorcontent="' . $item['ErrorContent'] . '" ><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
                            <a href="#deleteEmployeeModal" class="delete deleteModal" data-toggle="modal" data-machinetype="' . $item['MachineType'] . '" data-errorcode="' . $item['ErrorCode'] . '" ><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
                        </td>';
                            echo '</tr>';
                        }
                        ?>

                        </tbody>
                    </table>
                    <div class="clearfix">
                        <div class="hint-text">Showing <b><?php echo count($data); ?></b> out of <b><?php echo count($data); ?></b>
                            entries
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Add Modal HTML -->
<div id="addEmployeeModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="master.php?do=error-master" method="post">
                <div class="modal-header">
                    <h4 class="modal-title">Add Error Code Master</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Equipment Type</label>
                        <input type="text" name="MachineType" class="form-control" value="" required>
                    </div>
                    <div class="form-group">
                        <label>Error Code</label>
                        <input type="text" name="ErrorCode" class="form-control" value="" required>
                    </div>
                    <div class="form-group">
                        <label>Error Content</label>
                        <input type="text" name="ErrorContent" class="form-control" value="" required>
                    </div>


                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                    <input type="submit" class="btn btn-success" name="action" value="Add">
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Modal HTML -->
<div id="editEmployeeModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="master.php?do=error-master" method="post">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Error Code Master</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Equipment Type</label>
                        <input type="text" name="MachineType" class="form-control" id="MachineType" value="" disabled>
                        <input type="hidden" name="MachineType" class="form-control" id="MachineType2" value="">

                    </div>
                    <div class="form-group">
                        <label>Error Code</label>
                        <input type="text" name="ErrorCode" class="form-control" id="ErrorCode" value="" disabled>
                        <input type="hidden" name="ErrorCode" class="form-control" id="ErrorCode2" value="">

                    </div>
                    <div class="form-group">
                        <label>Error Content</label>
                        <input type="text" name="ErrorContent" class="form-control" id="ErrorContent" value="" required>
                    </div>


                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                    <input type="submit" class="btn btn-success" name="action" value="Update">
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Delete Modal HTML -->
<div id="deleteEmployeeModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="master.php?do=error-master" method="post">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Error Code Master</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this Error Code Master?</p>
                    <p class="text-warning">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" class="btn btn-danger" id="DeleteMachineType" name="MachineType" value="">
                    <input type="hidden" class="btn btn-danger" id="DeleteErrorCode" name="ErrorCode" value="">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                    <input type="submit" class="btn btn-danger" name="action" value="Delete">
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(".deleteModal").on('click', function () {
        var MachineType = $(this).data('machinetype');

        $('#DeleteMachineType').val(MachineType);
        var ErrorCode = $(this).data('errorcode');
        $('#DeleteErrorCode').val(ErrorCode);

    });
    $(".editModal").on('click', function () {
        var MachineType = $(this).data('machinetype');
        $('#MachineType').val(MachineType);
        $('#MachineType2').val(MachineType);

        var ErrorCode = $(this).data('errorcode');
        $('#ErrorCode').val(ErrorCode);
        $('#ErrorCode2').val(ErrorCode);

        var ErrorContent = $(this).data('errorcontent');
        $('#ErrorContent').val(ErrorContent);


    });
</script>
<script src="js/custom.js"></script>
<script src="assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="assets/js/custom.js"></script>
</body>
</html>