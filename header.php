<?php
if ($page_name == "Cast Trend") {
    $active_index = "class='active'";
} else {
    $active_index = "";
}

if ($page_name == "Setting") {
    $active_setting = "class='active'";
} else {
    $active_setting = "";
}

?>
<nav class="navbar navbar-default">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="./index.php">
               <img src="images/Inspired-Logo.png" width="229" height="41" /></span>
               <div class="logo" id="logo" style="padding-top: 0px;">
                   <h2 style="font-weight: bold;">Low Pressure TraceAbility</h2>
               </div>
            </a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li style="padding: 15px;">
                    <span style="color: #88898a; font-weight: bold; font-size: 16px;"><?php echo date('d / m / Y'); ?></span>
                    <span id="current_time" style="margin-left: 10px; color: #88898a; font-weight: bold;font-size: 16px;"><?php echo date('G:i:s A'); ?></span>
                </li>
                <li></li>
                <li <?php echo $active_index; ?>><a href="index.php"><img src="./images/home.png" style="width: 25px;height: 25px"> </a></li>
                <li <?php echo $active_setting; ?>><a href="admin.php"><img src="./images/settingsicon.png" style="width: 25px;height: 25px"> </a></li>
            </ul>
        </div>
    </div>
</nav>