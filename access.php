<?php
$res = shell_exec("apcaccess");
$res_array = explode("\n", $res);
unset($res_array[count($res_array) - 1]);
$array = array();
foreach ($res_array as $item){
	$single = explode(": ", $item);
	$key = trim($single[0]);
	$content = trim($single[1]);
	$array[$key] = $content;
}
$array["Client_IP"] = $_SERVER["REMOTE_ADDR"];
?>
<?php
if(isset($_GET["status"])){
	echo "status: ".$array["STATUS"]."<br>";
	echo "timeleft: ".(int)$array["TIMELEFT"];
	exit();
}
?>
<?php
if(isset($_GET["isSafeMode"])){
	$status = $array["STATUS"];
	$time = (int)$array["TIMELEFT"];
	if ($time > 3){
		if ($status != "ONLINE" && $time < 10){
			echo "yes";
		}else{
			echo "no";
		}
	}
	exit();
}
?>
<?php
if (isset($_GET["isSafeModeDebug"])){
echo $array["STATUS"].'<br>'.intval($array["TIMELEFT"]);
exit();
}
?>
<?php
header('Content-Type: application/json');
$allowed_ip_prefix = "10.194.102.";
if (substr($array["Client_IP"], 0, strlen($allowed_ip_prefix)) != $allowed_ip_prefix){
	exit("Forbidden. IP Rejected.");
}
echo json_encode($array);
?>
