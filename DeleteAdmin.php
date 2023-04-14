<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<?php Confirm_Login(); ?>
<?php

if(isset($_GET["id"])){
	$SearchQueryParameter = $_GET["id"];
	global $ConnectingDb;
	$sql = "DELETE FROM admins WHERE id='$SearchQueryParameter'";
	$Execute = $ConnectingDB->query($sql);
	if($Execute){
		$_SESSION["SuccessMessage"] = "Admin has been deleted.";
		Redirect_to("Admins.php");
	}else{
		$_SESSION["ErrorMessage"] = "Something went wrong.";
		Redirect_to("Admins.php");
	}
}

?>