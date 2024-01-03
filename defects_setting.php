<?php
require_once("./config/config.php");
require_once("functions.php");
$page_name = "Setting";

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

    <link href="css/chosen.css" rel="stylesheet"/>
    <script src="js/chosen.jquery.js"></script>

    <link rel="stylesheet" href="css/font-awesome.css">
    <link rel="stylesheet" href="assets/css/jquery.mCustomScrollbar.min.css" />
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="assets/css/custom-themes.css">
</head>
<style>
    h4{
        font-weight: bold;
    }

    .hide {
        display: none;
    }

    .show {
        display: block;
    }
</style>
<body onload="startTime()">
    <div class="page-wrapper chiller-theme">
        <?php include('menu.php'); ?>
        <main class="page-content">
            <div class="container-fluid">
                <div class="row"><?php include("header.php"); ?></div>
                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-md-12">
                        <h4 style="background-color: #0c99d6; color: #fff; padding: 10px;">Defect Setting</h4>
                        <table class="table table-responsive table-striped">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Value</th>
                                <th>IN/EX</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $query = $db->query("SELECT * FROM {$tblDefectsSetting}");
                                ?>

                                <?php while ($setting = mysqli_fetch_assoc($query)) { ?>
                                <tr>
                                    <form onsubmit="event.preventDefault();" id="form-edit-<?= $setting['id']; ?>" data-id="<?= $setting['id']; ?>" class="form-edit">
                                        <td>
                                            <input type="hidden" name="action" value="edit">
                                            <input type="hidden" name="id" value="<?= $setting['id']; ?>">
                                            <input type="text" name="name" id="name" class="form-control" value="<?= $setting['name']; ?>">
                                        </td>
                                        <td>
                                            <input type="text" name="value" id="value" class="form-control" value="<?= $setting['value']; ?>">
                                        </td>
                                        <td>
                                            <?php
                                                echo '<select class="form-control" id="in_ex"'.$setting['id'].' name="in_ex" style="width: 100%;">';
                                                if($setting['in_ex'] == '')
                                                    echo '<option value="" selected ></option><option value="internal">INTERNAL</option><option value="external">EXTERNAL</option>';
                                                else if($setting['in_ex'] == 'internal')
                                                    echo '<option value=""></option><option value="internal" selected>INTERNAL</option><option value="external">EXTERNAL</option>';
                                                else if($setting['in_ex'] == 'external')
                                                    echo '<option value=""></option><option value="internal">INTERNAL</option><option value="external" selected>EXTERNAL</option>';
                                            ?>
                                            </select>
                                        </td>
                                        <td>
                                            <button type="submit" class="btn btn-sm btn-primary" id="casting_h1_1_save">Save</button>&nbsp;&nbsp;
                                            <button type="button" class="btn btn-sm btn-danger delete-button" data-id="<?= $setting['id']; ?>">Delete</button>
                                        </td>
                                    </form>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <form onsubmit="event.preventDefault();" id="form-add" class="form-add">
                                        <td>
                                            <input type="hidden" name="action" value="add">
                                            <input type="text" name="name" id="name" class="form-control">
                                        </td>
                                        <td>
                                            <input type="text" name="value" id="value" class="form-control">
                                        </td>
                                        <td>
                                            <select class="form-control" id="in_ex" name="in_ex" style="width: 100%;">
                                            <option value=""></option>
                                            <option value="internal">INTERNAL</option>
                                            <option value="external">EXTERNAL</option>
                                            </select>
                                        </td>
                                        <td>
                                            <button type="submit" class="btn btn-sm btn-primary" id="casting_h1_1_save">Save</button>
                                        </td>
                                    </form>
                                </tr>
                            </tbody>
                        </table>
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
<script>
    const deleteItem = (e, id) => {
        const button = $(e.target);

        button.attr('disabled', true);

        $.ajax({
            url: "defects_action.php",
            method: "POST",
            dataType: "json",
            data: {
                "id": id,
                "action": "delete",
            },
        }).done(response => {
            if (response.status === 'success') {
                $('#success-alert').toggleClass('hide');
                $('#success-message').html(response.message);

                setTimeout(() => {
                    $('#success-alert').toggleClass('hide');
                    document.location.reload();
                    button.attr('disabled', false);
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
    }

    $(document).ready(() => {
        $('.delete-button').on('click', e => {
            deleteItem(e, $(e.target).data('id'));
        });

        $('.form-edit').on('submit', e => {
            const button = $(e.originalEvent.submitter);

            button.attr('disabled', true);

            $.ajax({
                url: "defects_action.php",
                method: "POST",
                dataType: "json",
                data: $(e.target).serialize(),
            }).done(response => {
                if (response.status === 'success') {
                    $('#success-alert').toggleClass('hide');
                    $('#success-message').html(response.message);

                    setTimeout(() => {
                        $('#success-alert').toggleClass('hide');
                        button.attr('disabled', false);
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
        });

        $('.form-add').on('submit', e => {
            const button = $(e.originalEvent.submitter);

            button.attr('disabled', true);

            $.ajax({
                url: "defects_action.php",
                method: "POST",
                dataType: "json",
                data: $(e.target).serialize(),
            }).done(response => {
                if (response.status === 'success') {
                    $('#success-alert').toggleClass('hide');
                    $('#success-message').html(response.message);

                    setTimeout(() => {
                        $('#success-alert').toggleClass('hide');
                        document.location.reload();
                        button.attr('disabled', false);
                    }, 1000);
                } else {
                    $('#fault-alert').toggleClass('hide');
                    $('#fault-message').html(response.message);

                    setTimeout(() => {
                        $('#fault-alert').toggleClass('hide');
                        button.attr('disabled', false);
                    }, 2000);
                }
            });
        });
    });
</script>
<script src="assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="assets/js/custom.js"></script>
</html>

