
$(document).ready(function(){

    startTime();

    $(document).on('click', "#opr_save", function () {
        var set_zone = $("#set_zone").val();
        var opr = $("#opr_setting").val();
        $.ajax({
            url: "actions.php",
            method: "post",
            data: {opr:opr, set_zone:set_zone, action:"opr_setting"}
        }).done(function (res) {
            if(res=="ok"){
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

    $(document).on('click', ".fault-amount", function () {
        var fault_type = $(this).data('kind');
        var set_zone = $("#set_zone").val();
        var amount = 10;

        if(fault_type == "own_fault_amount") {
            amount = $("#own_fault_amount").val();
        }

        if(fault_type == "fw_fault_amount") {
            amount = $("#fw_fault_amount").val();
        }

        if(fault_type == "nw_fault_amount") {
            amount = $("#nw_fault_amount").val();
        }

        if(fault_type == "machine_fault_amount") {
            amount = $("#machine_fault_amount").val();
        }

        $.ajax({
            url: "actions.php",
            method: "post",
            data: {set_zone:set_zone, kind:fault_type, amount:amount, action:"fault_amount_setting"}
        }).done(function (res) {
            if(res=="ok"){
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


    $(document).on('click', "#time_trend_save", function () {
        var value = $("#time_trend").val();
        var set_zone = $("#set_zone").val();
        $.ajax({
            url: "actions.php",
            method: "post",
            data: {time_trend:value, kind:"andon", action:"time_trend_setting", set_zone:set_zone}
        }).done(function (res) {
            if(res=="ok"){
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


    $(document).on('click', "#count_trend_save", function () {
        var value = $("#count_trend").val();
        var set_zone = $("#set_zone").val();
        $.ajax({
            url: "actions.php",
            method: "post",
            data: {count_trend:value, kind:"andon", action:"count_trend_setting", set_zone:set_zone}
        }).done(function (res) {
            if(res=="ok"){
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


    $(document).on('click', ".save-reason", function () {
        var id = $(this).attr('id').replace('reason_save', '');
        var tr = $(this).closest('tr');
        var reason = $("#reason"+id).val();
        var description = tr.find("input[name*='description']").val();
        var axis = tr.find("input[name*='axis']").val();
        var engine_number = tr.find("input[name*='engine_number']").val();
        var other = tr.find("input[name*='other']").val();
        var tr = $(this).closest('tr');
        $.ajax({
            url: "actions.php",
            method: "post",
            data: {
                id:id,
                reason:reason,
                description:description,
                axis: axis,
                engine_number: engine_number,
                other:other,
                action:"save_reason"}
        }).done(function (res) {

            if(res=="ok"){
                tr.css('background-color', '#fff');
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


    $(document).on('click', "#az_time_trend_save", function () {
        var value = $("#az_time_trend").val();
        var set_zone = $("#set_zone").val();
        $.ajax({
            url: "actions.php",
            method: "post",
            data: {time_trend:value, kind:'az', action:"time_trend_setting", set_zone:set_zone}
        }).done(function (res) {
            if(res=="ok"){
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


    $(document).on('click', "#az_count_trend_save", function () {
        var value = $("#az_count_trend").val();
        var set_zone = $("#set_zone").val();
        $.ajax({
            url: "actions.php",
            method: "post",
            data: {count_trend:value, kind:'az', action:"count_trend_setting", set_zone:set_zone}
        }).done(function (res) {
            if(res=="ok"){
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



    $("#reason_save").on('click', function () {
        var id = $("#fault_id").val();
        var description = $("#f_description").val();
        var reason = $("#reason").val();
        if(!reason) {
            $("#reason").focus();
            return false;
        }
        $.ajax({
            url: "actions.php",
            method: "post",
            data: {id:id, reason:reason, description:description, action:"save_reason"}
        }).done(function (res) {
            location.reload();
        });
    });

});


function startTime() {

    var today = new Date();

    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();

    m = checkTime(m);
    s = checkTime(s);

    var am_pm = today.getHours() >= 12 ? "PM" : "AM";

    $(document).find('#current_time').text(h + ":" + m + ":" + s + ' ' + am_pm);

    var t = setTimeout(startTime, 500);
}
function checkTime(i) {
    if (i < 10) {
        i = "0" + i
    }
    ;  // add zero in front of numbers < 10
    return i;
}
