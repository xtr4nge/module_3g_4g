<? 
/*
    Copyright (C) 2013-2014 xtr4nge [_AT_] gmail.com

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/ 
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>FruityWifi</title>
<script src="../js/jquery.js"></script>
<script src="../js/jquery-ui.js"></script>
<link rel="stylesheet" href="../css/jquery-ui.css" />
<link rel="stylesheet" href="../css/style.css" />
<link rel="stylesheet" href="../../../style.css" />

<script>
$(function() {
    $( "#action" ).tabs();
    $( "#result" ).tabs();
});

</script>

</head>
<body>

<? include "../menu.php"; ?>

<br>

<?

include "../../config/config.php";
include "../../login_check.php";
include "_info_.php";
include "../../functions.php";

// Checking POST & GET variables...
if ($regex == 1) {
	regex_standard($_POST["newdata"], "msg.php", $regex_extra);
    regex_standard($_GET["logfile"], "msg.php", $regex_extra);
    regex_standard($_GET["action"], "msg.php", $regex_extra);
    regex_standard($_POST["service"], "msg.php", $regex_extra);
}

$newdata = $_POST['newdata'];
$logfile = $_GET["logfile"];
$action = $_GET["action"];
$tempname = $_GET["tempname"];
$service = $_POST["service"];

// DELETE LOG
if ($logfile != "" and $action == "delete") {
    $exec = "$bin_rm ".$mod_logs_history.$logfile.".log";
    //exec("$bin_danger \"$exec\"", $dump); //DEPRECATED
    exec_fruitywifi($exec);
}

// SET MODE
if ($_POST["change_mode"] == "1") {
    $ss_mode = $service;
    $exec = "/bin/sed -i 's/ss_mode.*/ss_mode = \\\"".$ss_mode."\\\";/g' _info_.php";
    //exec("$bin_danger \"" . $exec . "\"", $output); //DEPRECATED
    $output = exec_fruitywifi($exec);
}

?>

<div class="rounded-top" align="left"> &nbsp; <b><?=$mod_alias?></b> </div>
<div class="rounded-bottom">

    &nbsp;version <?=$mod_version?><br>
    <?
    if (file_exists( $bin_nmcli )) { 
        echo "&nbsp;&nbsp; nmcli <font style='color:lime'>installed</font><br>";
    } else {
	echo "&nbsp;&nbsp; nmcli <font style='color:red'>not installed</font><br>";
    } 
    ?>
    
    <?
    $ismoduleup = exec("$mod_isup");
    if ($ismoduleup != "") {
        echo "&nbsp;&nbsp; $mod_alias  <font color=\"lime\"><b>enabled</b></font>.&nbsp; | <a href=\"includes/module_action.php?service=3g_4g&action=stop&page=module\"><b>stop</b></a>";
    } else { 
        echo "&nbsp;&nbsp; $mod_alias  <font color=\"red\"><b>disabled</b></font>. | <a href=\"includes/module_action.php?service=3g_4g&action=start&page=module\"><b>start</b></a>"; 
    }
    ?>

</div>

<br>


<div id="msg" style="font-size:largest;">
Loading, please wait...
</div>

<div id="body" style="display:none;">

    <div id="result" class="module">
        <ul>
            <li><a href="#result-1">Output</a></li>
            <li><a href="#result-2">About</a></li>
        </ul>
        
        <!-- OUTPUT -->

        <div id="result-1">
			
            <?
            $value = "apn=";
            //$exec = "grep '^username=' FruityWifi-Mobile | sed 's/^username=//g'";
            $exec = "grep '^$value' includes/FruityWifi_Mobile | sed 's/^$value//g'";
            //exec($exec, $output);
            //exec("$bin_danger \"$exec\"", $output); //DEPRECATED
            $output = exec_fruitywifi($exec);
            $mb_apn = $output[0];
            unset($output);
            
            $value = "username=";
            $exec = "grep '^$value' includes/FruityWifi_Mobile | sed 's/^$value//g'";
            //exec("$bin_danger \"$exec\"", $output); //DEPRECATED
            $output = exec_fruitywifi($exec);
            $mb_username = $output[0];
            unset($output);
            
            $value = "password=";
            $exec = "grep '^$value' includes/FruityWifi_Mobile | sed 's/^$value//g'";
            //exec("$bin_danger \"$exec\"", $output); //DEPRECATED
            $output = exec_fruitywifi($exec);
            $mb_password = $output[0];
            unset($output);
            ?>
            
            <div class="rounded-top" align="center"> Mobile Broadband </div>
            <div class="rounded-bottom general">
                <form action="includes/save.php" method="POST" autocomplete="off">				
                    <table>
                        <tr>
                            <td align="right">apn: </td>
                            <td><input class="input" name="mb_apn" value="<?=$mb_apn?>"></td>
                        </tr>
                        <tr>
                            <td align="right">username: </td>
                            <td><input class="input" name="mb_username" value="<?=$mb_username?>"></td>
                        </tr>
                        <tr>
                            <td align="right">password: </td>
                            <td><input type="password" class="password" name="mb_password" value="<?=$mb_password?>"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input class="input" type="submit" value="Set"></td>
                        </tr>
                    </table>
                    <input type="hidden" name="type" value="save_mobile_broadband">
                </form>
            </div>
            
        </div>
		
	<!-- ABOUT -->

        <div id="result-2" class="history">
            <? include "includes/about.php"; ?>
        </div>
        
        <!-- END ABOUT -->
        
    </div>

    <div id="loading" class="ui-widget" style="width:100%;background-color:#000; padding-top:4px; padding-bottom:4px;color:#FFF">
        Loading...
    </div>

    <script>
    $('#formLogs').submit(function(event) {
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'includes/ajax.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (data) {
                console.log(data);

                $('#output').html('');
                $.each(data, function (index, value) {
                    $("#output").append( value ).append("\n");
                });
                
                $('#loading').hide();
            }
        });
        
        $('#output').html('');
        $('#loading').show()

    });

    $('#loading').hide();

    </script>

    <script>
    $('#form1').submit(function(event) {
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'includes/ajax.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (data) {
                console.log(data);

                $('#output').html('');
                $.each(data, function (index, value) {
                    if (value != "") {
                        $("#output").append( value ).append("\n");
                    }
                });
                
                $('#loading').hide();

            }
        });
        
        $('#output').html('');
        $('#loading').show()

    });

    $('#loading').hide();

    </script>

    <script>
    $('#formInject2').submit(function(event) {
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'includes/ajax.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (data) {
                console.log(data);

                $('#inject').html('');
                $.each(data, function (index, value) {
                    $("#inject").append( value ).append("\n");
                });
                
                $('#loading').hide();
                
            }
        });
        
        $('#output').html('');
        $('#loading').show()

    });

    $('#loading').hide();

    </script>

    <?
    if ($_GET["tab"] == 1) {
        echo "<script>";
        echo "$( '#result' ).tabs({ active: 0 });";
        echo "</script>";
    } else if ($_GET["tab"] == 2) {
        echo "<script>";
        echo "$( '#result' ).tabs({ active: 1 });";
        echo "</script>";
    } else if ($_GET["tab"] == 3) {
        echo "<script>";
        echo "$( '#result' ).tabs({ active: 3 });";
        echo "</script>";
    } else if ($_GET["tab"] == 4) {
        echo "<script>";
        echo "$( '#result' ).tabs({ active: 4 });";
        echo "</script>";
    } 
    ?>

</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#body').show();
    $('#msg').hide();
});
</script>

</body>
</html>
