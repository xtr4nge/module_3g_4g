<?
$mod_name="3g_4g";
$mod_version="1.0";
$mod_path="/usr/share/FruityWifi/www/modules/$mod_name";
$mod_logs="/usr/share/FruityWifi/logs/$mod_name.log"; 
$mod_logs_history="$mod_path/includes/logs/";
$mod_logs_panel="disabled";
$mod_panel="show";
$mod_type="service";
$mod_alias="3g|4g";
$supplicant_ssid="";
$supplicant_psk="";
$ss_mode = "mode_mobile";

# EXEC
$bin_danger = "/usr/share/FruityWifi/bin/danger";
$bin_ifconfig = "/sbin/ifconfig";
$bin_iwlist = "/sbin/iwlist";
$bin_sh = "/bin/sh";
$bin_echo = "/bin/echo";
$bin_grep = "/usr/bin/ngrep";
$bin_killall = "/usr/bin/killall";
$bin_cp = "/bin/cp";
$bin_chmod = "/bin/chmod";
$bin_sed = "/bin/sed";
$bin_rm = "/bin/rm";
$bin_route = "/sbin/route";
$bin_perl = "/usr/bin/perl";
$bin_sleep = "/bin/sleep";
$bin_nmcli = "/usr/share/FruityWifi/www/modules/nmcli/includes/NetworkManager/cli/src/nmcli";

# ISUP
$mod_isup="$bin_danger \"$bin_nmcli -n d | grep -iEe 'gsm.+ connected'\"";
?>
