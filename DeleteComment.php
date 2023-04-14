<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<?php Confirm_Login(); ?>
<?php

if(isset($_GET["id"])){
	$SearchQueryParameter = $_GET["id"];
	global $ConnectingDb;
	$Admin = $_SESSION["AdminName"];
	$sql = "DELETE FROM comments WHERE id='$SearchQueryParameter'";
	$Execute = $ConnectingDB->query($sql);
	if($Execute){
		$_SESSION["SuccessMessage"] = "Comment has been deleted.";
		Redirect_to("Comments.php");
	}else{
		$_SESSION["ErrorMessage"] = "Something went wrong.";
		Redirect_to("Comments.php");
	}
}

?>