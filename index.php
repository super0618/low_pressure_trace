<?php
    $page_name = "INDEX";
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>TMUK DEESIDE CASTING LP TRACE MENU</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/buttonstyle.css">

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js')}}"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js')}}"></script>
    <![endif]-->
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/moment.min.js"></script>
    <script src="js/select2.min.js"></script>

    <link rel="stylesheet" href="css/font-awesome.css">
    <link rel="stylesheet" href="assets/css/jquery.mCustomScrollbar.min.css" />
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="assets/css/custom-themes.css">

    <style>
        .buttons {
            max-width: 70%;
            margin-left: auto;
            margin-right: auto;
        }

        .title {
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        #title2 {
            color: #468f12;
        }

        #backbtn {
            float: right;
        }

        #title p {
            text-align: center;
            font-family: "Arial Black", Gadget, sans-serif;
            font-size: 26px;
            color: #858b90;
        }

        body {

            background: rgb(255, 255, 255); /* Old browsers */

            background: -moz-linear-gradient(top, rgba(255, 255, 255, 1) 0%, rgba(246, 246, 246, 1) 47%, rgba(237, 237, 237, 1) 100%); /* FF3.6+ */

            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, rgba(255, 255, 255, 1)), color-stop(47%, rgba(246, 246, 246, 1)), color-stop(100%, rgba(237, 237, 237, 1))); /* Chrome,Safari4+ */

            background: -webkit-linear-gradient(top, rgba(255, 255, 255, 1) 0%, rgba(246, 246, 246, 1) 47%, rgba(237, 237, 237, 1) 100%); /* Chrome10+,Safari5.1+ */

            background: -o-linear-gradient(top, rgba(255, 255, 255, 1) 0%, rgba(246, 246, 246, 1) 47%, rgba(237, 237, 237, 1) 100%); /* Opera 11.10+ */

            background: -ms-linear-gradient(top, rgba(255, 255, 255, 1) 0%, rgba(246, 246, 246, 1) 47%, rgba(237, 237, 237, 1) 100%); /* IE10+ */

            background: linear-gradient(to bottom, rgba(255, 255, 255, 1) 0%, rgba(246, 246, 246, 1) 47%, rgba(237, 237, 237, 1) 100%); /* W3C */

            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#ededed', GradientType=0); /* IE6-9 */

        }

        .logo {
            padding: 7px 0px 20px 9px;
        }

        .spanLogo1 {
            font-family: "Arial Black", Gadget, sans-serif;
            font-size: 30px;
            margin-left: 30px;
            color: #468f12;
            alignment-adjust: central;
        }

        .spanLogo2 {
            font-family: "Arial Black", Gadget, sans-serif;
            font-size: 30px;
            color: #858b90;
        }

        .seletc {
            font-family: "Arial Black", Gadget, sans-serif;
            font-size: 20px;
            color: #858b90;
        }

        #buttons #select {
            color: #858b90;
            font-family: arial;
            line-height: 72px;
        }

        a:link {
            color:;
        }

        a:visited {
            color: #FFF;
        }

        a:hover {
            color: #FFF;
        }

        a:active {
            color: #FFF;
        }

        .welcome {
            float: left;
            padding: 1px 0 13px;
            width: 199px;
        }

        .blacktext {
            color: #FFF;
        }
    </style>
    <script type="text/javascript">
        function MM_goToURL() { //v3.0
            var i, args = MM_goToURL.arguments;
            document.MM_returnValue = false;
            for (i = 0; i < (args.length - 1); i += 2) eval(args[i] + ".location='" + args[i + 1] + "'");
        }
        function MM_preloadImages() { //v3.0
            var d = document;
            if (d.images) {
                if (!d.MM_p) d.MM_p = new Array();
                var i, j = d.MM_p.length, a = MM_preloadImages.arguments;
                for (i = 0; i < a.length; i++)
                    if (a[i].indexOf("#") != 0) {
                        d.MM_p[j] = new Image;
                        d.MM_p[j++].src = a[i];
                    }
            }
        }
    </script>
</head>
<body>
<div class="page-wrapper chiller-theme">
    <?php
    include ('menu.php');
    ?>
    <!-- sidebar-wrapper  -->
    <main class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="logo" id="logo"><img src="img/LOGO.png" width="315" height="61"/>
                    <div class="top-right" id="top-right">
                        <div class="time" id="time">
                        </div>
                    </div>
                </div>
                <div class="buttons" id="buttons">
                    <div class="welcome" id="welcom"><img src="img/Welcome.png" width="185" height="56" alt="welcome"/></div>
                    <div class="select" id="select">
                        LP TRACE: Please select from the menu below
                    </div>
                    <br/>
                    <div class="BTNTABLE" id="BTNTABLE">
                        <table width="100%" border="0">
                            <tr>
                                <td align="left"><a href="/hptrace/"></a>
                                    <button class="btn btn-blue btn-fill-vert-o"
                                            onclick="MM_goToURL('parent','view_data.php');return document.MM_returnValue">
                                        <div class="BTN-TEXT" id="BTN-TEXT">
                                            <div class="image-btn" id="image-btn">
                                                <p><img src="img/ct-icon.png" width="128" height="88" alt="Shift Reporting"/><br/><br/>TRACEABILITY LIST
                                            </div>
                                        </div>
                                    </button>
                                </td>
                                <td>&nbsp;</td>
                                <td>
                                    <button class="btn btn-blue btn-fill-vert-o"
                                            onclick="MM_goToURL('parent','filter.php');return document.MM_returnValue">
                                        <div class="BTN-TEXT" id="BTN-TEXT">
                                            <div class="image-btn" id="image-btn">
                                                <p><img src="img/cmm.png" width="128" height="88" alt="Reporting Page"/><br/><br/>QUALITY
                                                    ANALYSIS
                                            </div>
                                        </div>
                                    </button>
                                </td>
                                <td>&nbsp;</td>
                                <td>
                                    <button class="btn btn-blue btn-fill-vert-o"
                                            onclick="MM_goToURL('parent','http://www.inspiredonline.co.uk/support/');return document.MM_returnValue">
                                        <div class="BTN-TEXT" id="BTN-TEXT2">
                                            <div class="image-btn" id="image-btn2">
                                                <p><img src="img/support.png" width="67" height="81" alt="Shift Reporting"/><br/>
                                                    <br/>
                                                    SUPPORT
                                                </p>
                                            </div>
                                        </div>
                                    </button>
                                </td>
                                <td>&nbsp;</td>
                                <td>
                                    <button class="btn btn-blue btn-fill-vert-o"
                                            onclick="MM_goToURL('parent','admin.php');return document.MM_returnValue">
                                        <div class="BTN-TEXT" id="BTN-TEXT3">
                                            <div class="image-btn" id="image-btn3">
                                                <p><img src="img/report_admin.png" width="100" height="100" alt="Shift Reporting"/><br/>
                                                    <br/>
                                                    ADMIN
                                                </p>
                                            </div>
                                        </div>
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="inspired" id="inspired">
                    <div class="inspiredlogo" id="inspiredlogo">
                        <p><img src="img/inspired-logo (1).png" width="204" height="23"/></p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
<script src="assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="assets/js/custom.js"></script>
</html>
