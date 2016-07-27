<?php
// Start the session
session_start();

#echo shell_exec("./wiwo.pl AC:CF:23:8D:87:7A status");

##############################################
############## Performs Tasks ################
##############################################

# Username and Password are hashed using bcrypt. To generate bcrypt password, use an online bcrypt generator or use line 20 below.

$myusername = '$2a$06$yzLoBu0NcAvZvR7tU41N/eSIvec//KbfybOrvr1.24vfqrcLMC5wW';   # Username: wiwo
$mypassword = '$2a$06$yzLoBu0NcAvZvR7tU41N/eSIvec//KbfybOrvr1.24vfqrcLMC5wW';   # Password: wiwo

#$user2username = '$2a$06$FCJxbdd4.LLFHbTr3AYImuj6HlxM9ba59tonmb1QqbhLYDM1xnuNu';     # Username: wiwo2
#$user2password = '$2a$06$FCJxbdd4.LLFHbTr3AYImuj6HlxM9ba59tonmb1QqbhLYDM1xnuNu';     # Password: wiwo2


#echo $password_hash = password_hash("ENTER PASSWORD HERE", PASSWORD_BCRYPT);  # Uncomment and run the website to see password in bcrypt hash


if(isset($_POST["username"]))     # Logs in the user
{ 
	if((password_verify($_POST["username"], $myusername)) && (password_verify($_POST["password"], $mypassword)))
	{
		$_SESSION['Login'] = "loginsuccess";
	}
	
	#elseif((password_verify($_POST["username"], $user2username)) && (password_verify($_POST["password"], $user2password)))
	#{
		#$_SESSION['Login'] = "loginsuccess";
	#}

}


if(isset($_POST["logout"]))     # Logs out the user
{
	unset($_SESSION);
	session_destroy();
	session_write_close();
	header('Location: index.php');
	die;
}


$_SESSION['SwitchName'] = trim($_SESSION['SwitchName']);     # Remove white space at beginning and end of string
$_SESSION['macaddr'] = trim($_SESSION['macaddr']);     # Remove white space at beginning and end of string
$_SESSION['status'] = trim($_SESSION['status']);     # Remove white space at beginning and end of string
$_SESSION['success'] = trim($_SESSION['success']);     # Remove white space at beginning and end of string


##############################################

?>

<html>

<head> 
	<title> WiWo S20 Control </title>
	
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="apple-touch-startup-image" href="./ios-icon.png">
	<link rel="apple-touch-icon" href="./ios-icon.png">
	
	<!-- Bootstrap Core CSS -->
	<link rel="stylesheet" href="./css/bootstrap.css">
	<script src="./js/bootstrap.js"></script>
	<script src="./js/jquery-2.1.3.js"></script>
    <link rel="stylesheet" href="./css/bootstrap.css">
	<script src="./js/bootstrap.js"></script>
	
	
	<script>     
	
		// Script to update text box when user interact with dropdown menu
		function updateText()
		{
			$('input[name="MAC"]').val($('select[name="SelectSwitch"] option:selected').val());
			$.post('ajax.php', {macaddr: $('select[name="SelectSwitch"]').val() , SwitchName: $('select[name="SelectSwitch"] option:selected').text()});   <!-- This passes the Javascript variable of choosen MAC addr to PHP via AJAX POST -->
			setTimeout("location.href = 'index.php'",500);
		}
		
		// Script to reload the website, clearing the fields
		function reload()
		{
			$.post('ajax.php', {reload: "reload"});
			setTimeout("location.href = 'index.php'",500);
		}
		
		// Script to send variables from form to ajax.php via AJAX to on/off the switch
		function ChangeStatus()
		{
			// Initiate Variables With Form details
    		var macaddr = $("#MAC").val();    	
    		var SwitchName = $('select[name="SelectSwitch"] option:selected').text();	
    		if($("#on").is(':checked')) { var status = $("#on").val(); var CurrentStatus = "off" }     // Check if on/off is selected
    		if($("#off").is(':checked')) { var status = $("#off").val(); var CurrentStatus = "on" }     // Check if on/off is selected
    		
    			// Ensure no variables are empty
    			if((macaddr) && (status)) 
    			{ 
    				var check = confirm("The switch '" + SwitchName + "'  " + macaddr + " is currently " + CurrentStatus + ". Are you sure you want to set it to " + status + "?");
					
					if (check == true)
					{	
						$.post('ajax.php', {macaddr: macaddr , status: status , change: "change"});
															
						window.alert("Command Sent");   // This line is needed in order for the website to work over VPN... Idk why...							
						
					}
					else
					{
						window.alert("Status of '" + SwitchName + "' " + macaddr + " was not changed.");
					}
    				
    			}
    			else 
    			{
    				window.alert("Please select an option!");
    				setTimeout("location.href = 'index.php'",500);
				}	
		}
			
	</script>
	
	
</head>

<body>

<div align="center"> <img src="./switch.jpg" class="img-responsive center-block" onclick="reload()" /> </div>

<?php
if(!isset($_SESSION['Login']))     # If user is not logged in
{     
	?>
	
	<div align="center">
	<form action="index.php" method="post">
		<div align="left" class="form-group" style="width: 200px;">
			<label for="username">Username</label>
			<input type="text" class="form-control" name="username" placeholder="Username">
		</div>
		<div align="left" class="form-group" style="width: 200px;">
			<label for="password">Password</label>
			<input type="password" class="form-control" name="password" placeholder="Password">
		</div>	
		<div class="form-group" style="width: 200px;">
			<button type="submit" class="btn btn-success"> Login </button>
		</div>
	</form>
	</div>
	
	<?php
}

elseif(isset($_SESSION['Login']))     # If user is logged in
{     
	if($_SESSION['Login'] == "loginsuccess")     # Here is the codes when user login success
	{ ?>
		
		<!-- Logout Button -->
		<form action="index.php" method="post">
			<input type="text" name="logout" value="logout" style="display: none" readonly>
			<button type="submit" class="btn btn-danger"> Logout </button>
		</form>
		
		
		<!-- These are the hidden alerts -->
		<div align="center">
			<div id="alert_success" class="alert alert-success fade in" style="width: 400px;">
				<a href="#" class="close" data-dismiss="alert">&times;</a>
				<strong>Success!</strong> The status has successfully been changed.
				<script> $("#alert_success").hide()	</script>
			</div>
		
			<div id="alert_fail" class="alert alert-danger fade in" style="width: 400px;">
			<a href="#" class="close" data-dismiss="alert">&times;</a>
				<strong>Oh no!</strong> Something went wrong and status has not been changed.
				<script> $("#alert_fail").hide()	</script>
			</div>
		</div>
		
		
		<!-- Show alert if change is successful -->
		<?php
		
		if($_SESSION['success']=="success")
		{
			unset($_SESSION["success"]); 
			
			?> 
				<script type="text/javascript"> 
					$("#alert_success").show();
					window.setTimeout(function() { $("#alert_success").alert('close'); }, 5000);
				</script> 
				
			<?php
		}
		elseif($_SESSION['success']=="fail")
		{
			unset($_SESSION["success"]); 
			
				?> 
					<script type="text/javascript"> 
						$("#alert_fail").show();
						window.setTimeout(function() { $("#alert_fail").alert('close'); }, 5000);
					</script> 
					
				<?php
		}
		
		?>
		
 
		<!-- Dropdown Menu to select switch -->
		<div align="center">
		<div align="center" style="width: 300px;">
		<div class="form-group">
			<label for="SelectSwitch"> Select Switch: </label>
			<select class="form-control" name="SelectSwitch" onchange="return updateText()">
  				<option value = ''> Select Switch </option>
  				<option value = '00:00:00:00:00:00' <?php if($_SESSION["SwitchName"]=="Switch 1"){ echo "selected"; } ?> > Switch 1 </option>
<!-- 			<option value = '00:00:00:00:00:00' <?php if($_SESSION["SwitchName"]=="Switch 2"){ echo "selected"; } ?> > Switch 2 </option> -->   <!--Uncomment this line to add more switch options-->
			</select>
		</div>

		
		<!-- Show Information about selected Switch -->
		<form id="ChangeStatus" method="post">
			<div class="form-group">
				<label for="MAC"> MAC Address: </label>
				<input type='text' class="form-control" name="MAC" id="MAC" placeholder="Enter MAC Address" required readonly <?php if(isset($_SESSION["macaddr"])){echo "value=", $_SESSION["macaddr"];} ?> >
			</div>
			
			<div class="form-group">
				<label for="Status"> Status: </label> 
				<br>
				<input type='radio' name="status" id="on" value="on" <?php if($_SESSION['status']=="on"){ echo " checked"; } ?> > On 
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type='radio' name="status" id="off" value="off" <?php if($_SESSION['status']=="off"){ echo " checked"; } ?> > Off
			</div>
			
			<br>
			
			<div class="form-group">
			<button type="submit" onclick="ChangeStatus()" class="btn btn-success"> Submit </button>
			
		</div>
		</div>
		</form>
		
		
		<?php 
	} 
	else
	{
		echo "ERROR! Suspected Session Hijacking! Administrator will be notified!";
		exit;
	}
}


else 
{     
	echo "ERROR!";
	exit;
}


?>

</body>


</html>
