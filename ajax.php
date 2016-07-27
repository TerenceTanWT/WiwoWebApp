<?php
session_start();

$_SESSION['status']="HI!";

if($_POST["reload"] == "reload")
{
	unset($_SESSION["SwitchName"]);
	unset($_SESSION["macaddr"]);
	unset($_SESSION["status"]);	
}


elseif( (isset($_POST["macaddr"])) && ($_POST["change"]!="change") && (!$_POST["reload"]) )
{
	$_SESSION['macaddr'] = $_POST["macaddr"];
	$_SESSION['SwitchName'] = $_POST["SwitchName"];
	$mac = 	$_POST["macaddr"];
	$_SESSION['status'] = shell_exec("./wiwo.pl {$mac} status");
}


elseif( ($_POST["change"]=="change") && (!$_POST["reload"]) )
{
	
	$_SESSION['macaddr'] = $_POST["macaddr"];
	$_SESSION['status'] = $_POST["status"];
	$mac = 	$_POST["macaddr"];
	$status = $_POST["status"];
	
	
	shell_exec("./wiwo.pl {$mac} {$status}");
	$success = shell_exec("echo $?");
	
	unset($_SESSION["status"]);
	$_SESSION['status'] = shell_exec("./wiwo.pl {$mac} status");
	
	if($success == 0) # If code executes successfully
	{
		$_SESSION['success']="success";
	}
	else
	{
		$_SESSION['success']="fail";
	}
	
}


?>

