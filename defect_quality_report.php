<?php
require_once("./config/config.php");
require_once("functions.php");
$page_name = "Defects Quality Report";
$data_type = "head";
$int_ext = "internal";
$targetValue = 1;

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
    <script src="js/jspdf.min.js"></script>
    <script src="js/html2canvas.min.js"></script>

    <link href="css/chosen.css" rel="stylesheet"/>
    <script src="js/chosen.jquery.js"></script>

    <link rel="stylesheet" href="css/font-awesome.css">
    <link rel="stylesheet" href="assets/css/jquery.mCustomScrollbar.min.css" />
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="assets/css/custom-themes.css">

    <style>
        .data-type-filter{
            margin-right: 16px;
        }
        .int-ext-filter {
            margin-right: 16px;
        }
        .date-filter.second {
            margin-top: 8px;
        }
        .hide {
            display: none;
        }
        .chart{
            margin-top: 40px;
        }
        .trend-line {
            margin-right: 16px;
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
            <form id="filter_form" class="form-inline">
                <div class="row" style="margin-top: 20px; margin-bottom: 20px;">
                    <div class="row" style="margin-left: 0; margin-right: 0; display: flex; align-items: center;">
                        <div class="data-type-filter">
                            <label>DATA TYPE:</label>
                            <select class="form-control" id="data_type">
                                <option value="" selected></option>
                                <option value="block">Block</option>
                                <option value="head">Head</option>
                                <option value="block_head" >Block & Head</option>
                            </select>
                        </div>
                        <div class="int-ext-filter">
                            <label>INT/EXT</label>
                            <select class="form-control" id="int_ext">
                                <option value="" selected></option>
                                <option value="internal">Internal</option>
                                <option value="external" >External</option>
                                <option value="internal_external" >Internal & External</option>
                            </select>
                        </div>
                        <div class="month-filter">
                            <a class="btn btn-primary" style="margin-right: 16px" id="current_month">CURRENT MONTH</a>
                            <a class="btn btn-primary" style="margin-right: 16px" id="past_month">PAST MONTH</a>
                        </div>
                        <div class="trend-line">
                            <label style="margin-right: 4px">TREND LINE</label>
                            <input type="number" id="trend-line" value="1" style="width: 50px" />
                        </div>
                        <div>
                            <div class="date-filter first" style="display: flex; align-items: center">
                                <label style="margin-right: 16px;">DATE SELECT</label>
                                <label style="margin-right: 16px;">TO</label>
                                <input type="text" id="to_date" name="to_date" class="form-control datepicker" style="margin-right: 16px;" value="">
                                <label style="margin-right: 16px;">FROM</label>
                                <input type="text" id="from_date" name="from_date" class="form-control datepicker" style="margin-right: 16px;" value="">
                            </div>
                            <div class="date-filter second hide" style="display: flex; align-items: center; justify-content: right">
                                <label style="margin-right: 16px;">TO</label>
                                <input type="text" id="to_date2" name="to_date2" class="form-control datepicker" style="margin-right: 16px;" value="">
                                <label style="margin-right: 16px;">FROM</label>
                                <input type="text" id="from_date2" name="from_date2" class="form-control datepicker" style="margin-right: 16px;" value="">
                            </div>
                        </div>
                        <a class="btn btn-primary" id="trend-button" style="margin-right: 16px">TREND</a>
                        <div class="download">
                            <label style="margin-right: 16px;">DOWNLOAD: </label>
                            <a class="btn btn-primary" id="pdf-download" style="margin-right: 16px; background-color: pink; color: black;">PDF</a>
                            <a class="btn btn-primary" id="csv-download" style="margin-right: 16px; background-color: lightgreen; color: black">CSV</a>
                        </div>
                    </div>
                </div>
            </form>
            <div id="lp-chart-head-internal" class="chart">
                <div class="row text-center">
                    <strong style="text-transform: uppercase" class="chart-title">LP HEAD INTERNAL QUALITY</strong>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-4" style="padding: 0px">
                            <canvas id="lp-head-internal-quality-chart-month" height="296"></canvas>
                        </div>
                        <div class="col-md-8" style="padding: 0px; margin-left: -20px">
                            <canvas id="lp-head-internal-quality-chart-date" width="100%" height="296"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div id="lp-chart-head-external" class=" chart">
                <div class="row text-center">
                    <strong style="text-transform: uppercase" class="chart-title">LP HEAD EXTERNAL QUALITY</strong>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-4" style="padding: 0px">
                            <canvas id="lp-head-external-quality-chart-month" height="296"></canvas>
                        </div>
                        <div class="col-md-8" style="padding: 0px; margin-left: -20px">
                            <canvas id="lp-head-external-quality-chart-date" width="100%" height="296"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div id="lp-chart-block-internal" class=" chart">
                <div class="row text-center">
                    <strong style="text-transform: uppercase" class="chart-title">LP BLOCK INTERNAL QUALITY</strong>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-4" style="padding: 0px">
                            <canvas id="lp-block-internal-quality-chart-month" height="296"></canvas>
                        </div>
                        <div class="col-md-8" style="padding: 0px; margin-left: -20px">
                            <canvas id="lp-block-internal-quality-chart-date" width="100%" height="296"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div id="lp-chart-block-external" class=" chart">
                <div class="row text-center">
                    <strong style="text-transform: uppercase" class="chart-title">LP BLOCK EXTERNAL QUALITY</strong>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-4" style="padding: 0px">
                            <canvas id="lp-block-external-quality-chart-month" height="296"></canvas>
                        </div>
                        <div class="col-md-8" style="padding: 0px; margin-left: -20px">
                            <canvas id="lp-block-external-quality-chart-date" width="100%" height="296"></canvas>
                        </div>
                    </div>
                </div>
            </div>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
<script>
    let targetValue = "<?php echo $targetValue; ?>";
    let month = new Date().getMonth()+1;
    let prevLabelDates = [];
    const label_months = ["", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", ""];
    let from_date, to_date;
    let from_date2, to_date2;
    let from_date_str = "", to_date_str = "";
    let from_date_str2 = "", to_date_str2 = "";
    let int_ext = "";
    let data_type = "";
    let isVisibleSecond = false;

    let month_data_external = [];
    let month_data_internal = [];
    let date_data_internal = [];
    let date_data_external = [];

    let date_data_internal2 = [];
    let date_data_external2 = [];

    var head_internal_month_chart, head_internal_date_chart;
    var head_external_month_chart, head_external_date_chart;
    var block_internal_month_chart, block_internal_date_chart;
    var block_external_month_chart, block_external_date_chart;

    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
    });

    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
    });

    $("#trend-button").on('click', () => {
        isVisibleSecond = !isVisibleSecond;
        $(".date-filter.second").toggleClass("hide");
    });

    $("#data_type").on('change', (e) => {
        data_type = e.target.value;
        $(".chart").addClass("hide");
        if(data_type === "block_head"){
            if(int_ext === "internal_external"){
                $(".chart").removeClass("hide");
            } else {
                $(`#lp-chart-block-${int_ext}`).removeClass("hide");
                $(`#lp-chart-head-${int_ext}`).removeClass("hide");
            }
        } else {
            if(int_ext === "internal_external"){
                $(`#lp-chart-${data_type}-internal`).removeClass("hide");
                $(`#lp-chart-${data_type}-external`).removeClass("hide");
            } else {
                $(`#lp-chart-${data_type}-${int_ext}`).removeClass("hide");
            }
        }
    });

    $("#int_ext").on('change', (e) => {
        int_ext = e.target.value;
        $(".chart").addClass("hide");
        if(int_ext === "internal_external"){
            if(data_type === "block_head"){
                $(".chart").removeClass("hide");
            } else {
                $(`#lp-chart-${data_type}-internal`).removeClass("hide");
                $(`#lp-chart-${data_type}-external`).removeClass("hide");

            }
        } else {
            if(data_type === "block_head"){
                $(`#lp-chart-block-${int_ext}`).removeClass("hide");
                $(`#lp-chart-head-${int_ext}`).removeClass("hide");
            } else {
                $(`#lp-chart-${data_type}-${int_ext}`).removeClass("hide");
            }
        }
    });

    $("#current_month").on('click', () => {
        month = new Date().getMonth() + 1;
        const currentDate = new Date();
        const lastDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
        to_date_str = convertDateToString(lastDayOfMonth);
        const firstDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
        from_date_str = convertDateToString(firstDayOfMonth);
        let label_dates = Array.from({length: getEndDateEachMonth(month)}, (_, i) => i + 1);
        getData(label_dates);
    });

    $("#to_date").on('change', (e) => {
        if(e.target.value) {
            to_date = new Date(e.target.value.split("-").reverse().join("-"));
            to_date_str = e.target.value;
        }
        else {
            to_date = undefined;
            to_date_str = "";
        }

        let label_dates = [];
        if(from_date) {
            for (let i = from_date.getMonth() + 1; i < to_date.getMonth(); i++)
                label_dates.unshift(...Array.from({length: getEndDateEachMonth(i + 1)}, (_, j) => j + 1));
            if(from_date.getMonth() < to_date.getMonth()) {
                label_dates = Array.from({length: to_date.getDate()}, (_, i) => i + 1);
                label_dates.unshift(...Array.from({length: (getEndDateEachMonth(from_date.getMonth() + 1) - from_date.getDate() + 1)}, (_, j) => j + from_date.getDate()));
            }
            else if(from_date.getMonth() === to_date.getMonth())
                label_dates.unshift(...Array.from({length: (to_date.getDate() - from_date.getDate() + 1)}, (_, j) => j + from_date.getDate()));
        } else {
            label_dates = Array.from({length: to_date.getDate() }, (_, i) => i + 1);
        }

        getData(label_dates);
    });

    $("#from_date").on('change', (e) => {
        if(e.target.value) {
            from_date = new Date(e.target.value.split("-").reverse().join("-"));
            from_date_str = e.target.value;
        }
        else {
            from_date = undefined;
            from_date_str = "";
        }

        let label_dates = [];
        if(to_date) {
            for (let i = from_date.getMonth() + 1; i < to_date.getMonth(); i++)
                label_dates.unshift(...Array.from({length: getEndDateEachMonth(i + 1)}, (_, j) => j + 1));
            if(from_date.getMonth() < to_date.getMonth()) {
                label_dates = Array.from({length: to_date.getDate()}, (_, i) => i + 1);
                label_dates.unshift(...Array.from({length: (getEndDateEachMonth(from_date.getMonth() + 1) - from_date.getDate() + 1)}, (_, j) => j + from_date.getDate()));
            }
            else if(from_date.getMonth() === to_date.getMonth())
                label_dates.unshift(...Array.from({length: (to_date.getDate() - from_date.getDate() + 1)}, (_, j) => j + from_date.getDate()));
        } else {
            label_dates = Array.from({length: (getEndDateEachMonth(from_date.getMonth() + 1) - from_date.getDate() + 1) }, (_, i) => i + from_date.getDate());
        }

        getData(label_dates);
    });

    $("#trend-line").on('change', (e) => {
        targetValue = parseInt(e.target.value);
        getData(prevLabelDates);
    });

    $("#to_date2").on('change', (e) => {
        if(e.target.value) {
            to_date2 = new Date(e.target.value.split("-").reverse().join("-"));
            to_date_str2 = e.target.value;
        }
        else {
            to_date2 = undefined;
            to_date_str2 = "";
        }

        getData(prevLabelDates);
    });

    $("#from_date2").on('change', (e) => {
        if(e.target.value) {
            from_date2 = new Date(e.target.value.split("-").reverse().join("-"));
            from_date_str2 = e.target.value;
        }
        else {
            from_date2 = undefined;
            from_date_str2 = "";
        }

        getData(prevLabelDates);
    });

    $("#past_month").on('click', () => {
        month -= 1;
        if(month === 0)
            month = 12;

        const currentDate = new Date();
        const lastDayOfMonth = new Date(currentDate.getFullYear(), month, 0);
        to_date_str = convertDateToString(lastDayOfMonth);
        const firstDayOfMonth = new Date(currentDate.getFullYear(), month - 1, 1);
        from_date_str = convertDateToString(firstDayOfMonth);

        let label_dates = Array.from({length: getEndDateEachMonth(month)}, (_, i) => i + 1);
        getData(label_dates);
    });

    // function takeScreenshot() {
    //     return new Promise(resolve => {
    //         const canvas = document.createElement('canvas');
    //         const context = canvas.getContext('2d');
    //         const width = window.innerWidth;
    //         const height = window.innerHeight;
    //         canvas.width = width;
    //         canvas.height = height;
    //         const image = new Image();
    //         image.onload = () => {
    //             context.drawImage(image, 0, 0, width, height);
    //             resolve(canvas.toDataURL('image/jpeg'));
    //         };
    //         image.src = 'data:image/svg+xml,' + encodeURIComponent('<svg xmlns="http://www.w3.org/2000/svg" width="' + width + '" height="' + height + '"><foreignObject width="100%" height="100%"><div xmlns="http://www.w3.org/1999/xhtml">' + document.documentElement.innerHTML + '</div></foreignObject></svg>');
    //     });
    // }

    function takeScreenshot() {
        return html2canvas(document.body, {
            width: 1920,
            height: 1080
        })
    }

    takeScreenshot().then(result => console.log(result));

    $("#pdf-download").on('click', async () => {

        const canvas = await takeScreenshot();
        const imageData = canvas.toDataURL('image/jpeg');

        const img = new Image();
        img.width = 1920;
        img.height = 1080;
        img.src = imageData;

        const doc = new jsPDF({
            unit: 'mm',
            format: [500, 500]
        });

        doc.addImage(img, 'JPEG', 0, 0, doc.internal.pageSize.getWidth(), doc.internal.pageSize.getHeight());
        doc.save('screenshot.pdf');
    });

    $("#csv-download").on('click', () => {
        // Convert the canvas to a data URL
        const dataUrl = `defects-quality-export.php?page=defect-quality-report&action=retrieve-quality-results&from_date=${from_date_str || convertDateToString(new Date(new Date().getFullYear(), new Date().getMonth(), 1))}&to_date=${to_date_str || convertDateToString(new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0))}`;

        // Create a link element
        const link = document.createElement('a');
        link.setAttribute('href', dataUrl);

        // Click the link to download the file
        link.click();

        link.remove();
    })

    const convertDateToString = (date) => {
        const day = date.getDate().toString().padStart(2, '0'); // convert day to string and pad with leading zero if necessary
        const month = (date.getMonth() + 1).toString().padStart(2, '0'); // add 1 to month because it's zero-indexed, then convert to string and pad with leading zero if necessary
        const year = date.getFullYear().toString(); // convert year to string
        const dateString = `${day}-${month}-${year}`;
        return dateString;
    }

    const getEndDateEachMonth = (month) => {
        let date;
        switch(month) {
            case 1: case 3: case 5: case 7: case 8: case 10: case 12:
                date = 31;
                break;
            case 4: case 6: case 9: case 11:
                date = 30;
                break;
            case 2:
                date = 28;
                break;
        }
        return date;
    }

    const generateDiagram = (label_dates) => {
        try{
            head_internal_month_chart.destroy();
            head_internal_date_chart.destroy();

            head_external_month_chart.destroy();
            head_external_date_chart.destroy();

            block_internal_month_chart.destroy();
            block_internal_date_chart.destroy();

            block_external_month_chart.destroy();
            block_external_date_chart.destroy();
        } catch (e) {

        }
        const targetArrayMonth = Array.from({length: label_months.length}, () => targetValue);

        label_dates.push("");
        label_dates.unshift("");

        const targetArrayDate = Array.from({length: label_dates.length}, () => targetValue);

        let maximumYAxisTickInternal = 7 > targetValue ? 7 : targetValue;
        let maximumYAxisTickExternal = 7 > targetValue ? 7 : targetValue;

        month_data_internal.forEach((data) => {
            if(data>maximumYAxisTickInternal)
                maximumYAxisTickInternal = data;
        })

        month_data_external.forEach((data) => {
            if(data>maximumYAxisTickExternal)
                maximumYAxisTickExternal = data;
        })

        const data_internal_month = {
            labels: label_months,
            datasets: [{
                label: '',
                data: month_data_internal.map((data) => {if(data === -1) return null; return data;}),
                fill: false,
                borderColor: 'black',
                pointBackgroundColor: 'black',
                showLine: false
            },{
                label: '',
                data: targetArrayMonth,
                fill: false,
                borderColor: 'red',
                pointRadius: 0
            }
            ]
        };

        const data_external_month = {
            labels: label_months,
            datasets: [{
                label: '',
                data: month_data_external.map((data) => {if(data === -1) return null; return data;}),
                fill: false,
                borderColor: 'black',
                pointBackgroundColor: 'black',
                showLine: false
            },{
                label: '',
                data: targetArrayMonth,
                fill: false,
                borderColor: 'red',
                pointRadius: 0
            }
            ]
        };

        const config_internal_month = {
            type: 'line',
            data: data_internal_month,
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        min: 0,
                        max: maximumYAxisTickInternal + 3,
                        ticks: {
                            stepSize: 1
                        },
                        grid: {
                            display: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            padding: 10
                        }
                    }
                }
            }
        };

        const config_external_month = {
            type: 'line',
            data: data_external_month,
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        min: 0,
                        max: maximumYAxisTickExternal + 3,
                        ticks: {
                            stepSize: 1
                        },
                        grid: {
                            display: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            padding: 10
                        }
                    }
                }
            }
        };

        const data_internal_date = {
            labels: label_dates,
            datasets: [{
                label: '',
                data: date_data_internal.map((data) => {if(data === -1) return null; return data;}),
                fill: false,
                // borderColor: 'black',
                pointBackgroundColor: 'black',
                // showLine: false
            },{
                label: '',
                data: targetArrayDate,
                fill: false,
                borderColor: 'red',
                pointRadius: 0
            }
            ]
        };

        if(isVisibleSecond) {
            data_internal_date.datasets.push({
                label: 'daterange2',
                data: date_data_internal2.map((data) => {if(data === -1) return null; return data;}),
                fill: false,
                // borderColor: 'black',
                pointBackgroundColor: 'black',
                // showLine: false
            } );
        }

        const data_external_date = {
            labels: label_dates,
            datasets: [{
                label: '',
                data: date_data_external.map((data) => {if(data === -1) return null; return data;}),
                fill: false,
                // borderColor: 'black',
                pointBackgroundColor: 'black',
                // showLine: false
            },{
                label: '',
                data: targetArrayDate,
                fill: false,
                borderColor: 'red',
                pointRadius: 0
            }
            ]
        };

        if(isVisibleSecond) {
            data_external_date.datasets.push({
                label: 'daterange2',
                data: date_data_external2.map((data) => {if(data === -1) return null; return data;}),
                fill: false,
                // borderColor: 'black',
                pointBackgroundColor: 'black',
                // showLine: false
            } );
        }

        const config_internal_date = {
            type: 'line',
            data: data_internal_date,
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        min: 0,
                        max: maximumYAxisTickInternal+3,
                        ticks: {
                            stepSize: 1
                        },
                        grid: {
                            display: false
                        },
                        ticks: {
                            display: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            padding: 10
                        }
                    }
                }
            }
        };

        const config_external_date = {
            type: 'line',
            data: data_external_date,
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        min: 0,
                        max: maximumYAxisTickExternal + 3,
                        ticks: {
                            stepSize: 1
                        },
                        grid: {
                            display: false
                        },
                        ticks: {
                            display: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            padding: 10
                        }
                    }
                }
            }
        };

        const config_internal_block_month = {
            type: 'line',
            data: [],
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        min: 0,
                        max: 5,
                        ticks: {
                            stepSize: 1
                        },
                        grid: {
                            display: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            padding: 10
                        }
                    }
                }
            }
        };

        const config_internal_block_date = {
            type: 'line',
            data: [],
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        min: 0,
                        max: 5,
                        ticks: {
                            stepSize: 1
                        },
                        grid: {
                            display: false
                        },
                        ticks: {
                            display: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            padding: 10
                        }
                    }
                }
            }
        };

        $(".chart").removeClass("hide");

        head_internal_month_chart = new Chart(document.getElementById('lp-head-internal-quality-chart-month'), config_internal_month);
        head_internal_date_chart = new Chart(document.getElementById('lp-head-internal-quality-chart-date'), config_internal_date);

        head_external_month_chart = new Chart(document.getElementById('lp-head-external-quality-chart-month'), config_external_month);
        head_external_date_chart = new Chart(document.getElementById('lp-head-external-quality-chart-date'), config_external_date);

        block_internal_month_chart = new Chart(document.getElementById('lp-block-internal-quality-chart-month'), config_internal_block_month);
        block_internal_date_chart = new Chart(document.getElementById('lp-block-internal-quality-chart-date'), config_internal_block_date);

        block_external_month_chart = new Chart(document.getElementById('lp-block-external-quality-chart-month'), config_internal_block_month);
        block_external_date_chart = new Chart(document.getElementById('lp-block-external-quality-chart-date'), config_internal_block_date);

        $(".chart").addClass("hide");

        if(data_type === "block_head") {
            if(int_ext === "internal_external") {
                $(".chart").removeClass("hide");
            }
            else {
                $(`#lp-chart-block-${int_ext}`).removeClass("hide");
                $(`#lp-chart-head-${int_ext}`).removeClass("hide");
            }
        } else {
            if(int_ext === "internal_external") {
                $(`#lp-chart-${data_type}-internal`).removeClass("hide");
                $(`#lp-chart-${data_type}-external`).removeClass("hide");
            }
            else {
                $(`#lp-chart-${data_type}-${int_ext}`).removeClass("hide");
            }
        }

    }

    const getData = (label_dates, callback = () => {}) => {
        prevLabelDates = [...label_dates];

        $.ajax({
            url: "defects_action.php",
            method: "POST",
            dataType: "json",
            data: {
                "action": "getDefectsReport",
                "data_type": data_type,
                "int_ext": int_ext,
                "from_date": from_date_str || "",
                "to_date": to_date_str || "",
                "from_date2": from_date_str2 || "",
                "to_date2": to_date_str2 || ""
            },
        }).done(res => {
            month_data_external = res['month_data_external'];
            month_data_external.unshift(null);
            // month_data_external.push(null);

            month_data_internal = res['month_data_internal'];
            month_data_internal.unshift(null);
            // month_data_internal.push(null);

            date_data_external = res['date_data_external'];
            // date_data_external.unshift(null);
            // date_data_external.push(null);

            date_data_internal = res['date_data_internal'];
            // date_data_internal.unshift(null);
            // date_data_internal.push(null);

            date_data_external2 = res['date_data_external'];
            date_data_external2.unshift(null);
            date_data_external2.push(null);

            date_data_internal2 = res['date_data_internal'];
            date_data_internal2.unshift(null);
            date_data_internal2.push(null);

            generateDiagram(label_dates);

            callback();
        });
    }
    let label_dates = Array.from({length: getEndDateEachMonth(month)}, (_, i) => i + 1);
    getData(label_dates);

</script>
<script src="assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="assets/js/custom.js"></script>
</html>