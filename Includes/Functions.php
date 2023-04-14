<?php require_once("DB.php"); ?>
<?php


function Redirect_to($New_Location){
	header("Location:".$New_Location);
	exit;
}

function CheckUserNameExist($Username){
	global $ConnectingDB;
	$sql = "SELECT username FROM admins WHERE username=:usernamE";
	$stmt = $ConnectingDB->prepare($sql);
	$stmt->bindValue(':usernamE',$Username);
	$stmt->execute();
	$Result = $stmt->rowcount();
	if($Result==1) {
		return TRUE;
	}else{
		return FALSE;
	}
}

function Login_Attempt($Username, $Password){
	global $ConnectingDB;
	$sql = "SELECT * FROM admins WHERE username=:usernamE AND password=:passworD LIMIT 1";
	$stmt = $ConnectingDB->prepare($sql);
	$stmt->bindValue(':usernamE',$Username);
	$stmt->bindValue(':passworD',$Password);
	$stmt->execute();
	$Result = $stmt->rowcount();
	if($Result==1){
		return $Found_Account=$stmt->fetch();
	}else{
		return null;
	}
}

function Confirm_Login(){
	if(isset($_SESSION["Username"])){
		return true;
	}else{
		$_SESSION["ErrorMessage"]="Login Required";
		Redirect_to("Login.php");
	}
}

function TotalRows($table){
	global $ConnectingDB;
	$sql = "SELECT COUNT(*) FROM $table";
	$stmt = $ConnectingDB->query($sql);
	$TotalRows = $stmt->fetch();
	$Total = array_shift($TotalRows);
	echo $Total;
}

function TotalComments($id,$approved){
	global $ConnectingDB;
	$sql = "SELECT COUNT(*) FROM comments WHERE post_id='$id' AND status='$approved'";
	$stmt = $ConnectingDB->query($sql);
	$TotalRows = $stmt->fetch();
	$Total = array_shift($TotalRows);
	echo $Total;
}
?>