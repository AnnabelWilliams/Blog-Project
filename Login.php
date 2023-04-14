<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<?php

if(isset($_SESSION["Username"])){
	Redirect_to("Dashboard.php");
}

if(isset($_POST["Submit"])){
	$Username = $_POST["Username"];
	$Password = $_POST["Password"];
	if(empty($Username)||empty($Password)){
		$_SESSION["ErrorMessage"] = "All fields must be filled in.";
		Redirect_to("Login.php");
	}else{
		$Found_Account = Login_Attempt($Username, $Password);
		if($Found_Account){
			$_SESSION["User_ID"] = $Found_Account["id"];
			$_SESSION["Username"] = $Found_Account["username"];
			$_SESSION["AdminName"] = $Found_Account["aname"];
			$_SESSION["SuccessMessage"] = "Welcome back, ".$_SESSION["AdminName"];
			if(isset($_SESSION["TrackingURL"])){
				Redirect_to($_SESSION["TrackingURL"]);
			}else{
				Redirect_to("Dashboard.php");
			}
		}else{
			$_SESSION["ErrorMessage"] = "Username or password does not exist.";
			Redirect_to("Login.php");
		}
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<script src="https://kit.fontawesome.com/d6ea2f9932.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="CSS/styles.css">
	<title>Logout</title>
</head>
<body>
	<!-- NAVBAR -->
	<div style="height:10px; background:#27aae1;"></div>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container">
			<a href="#" class="navbar-brand">BELLE.COM</a>
			<button class="navbar-toggler" data-toggle="collapse" data-target="#navbarcollapseCMS">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarcollapseCMS">
			</div>
		</div>
	</nav>
	<div style="height:10px; background:#27aae1;"></div>
	<!-- NAVBAR END -->
	
	<!-- HEADER -->
	
	<header class="bg-dark text-white py-3">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
				</div>
			</div>
		</div>
	</header>
	
	<!-- HEADER END -->
	
	<!-- MAIN AREA -->
	
	<section class="container py-2 mb-4">
		<div class="row">
			<div class="offset-sm-3 col-sm-6" style="min-height:400px">
				<?php echo ErrorMessage();
					  echo SuccessMessage(); ?>
				<div class="card bg-secondary text-light">
					<div class="card-header">
						<h4>Login</h4>
					</div>
					<div class="card-body bg-dark">
						<form class="" action"Login.php" method="post">
							<div class="form-group">
								<label for="username"><span class="FieldInfo">Username:</span></label>
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<span class="input-group-text text-white bg-info"><i class="fas fa-user"></i><span>
									</div>
									<input type="text" class="form-control" name="Username" id="username">
								</div>
							</div>
							<div class="form-group">
								<label for="password"><span class="FieldInfo">Password:</span></label>
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<span class="input-group-text text-white bg-info"><i class="fas fa-lock"></i><span>
									</div>
									<input type="password" class="form-control" name="Password" id="password">
								</div>
							</div>
							<input type="submit" name="Submit" class="btn btn-info btn-block" value="Login">
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	
	<!-- MAIN AREA END -->
	
	<!-- FOOTER -->
	
	<div style="height:10px; background:#27aae1;"></div>
	<footer class="bg-dark text-white">
		<div class="container">
			<div class="row">
				<div class="col">
				<p class="lead text-center">Annabel's PHP Training <span id="year"></span></p>
				</div>
			</div>
		</div>
	</footer>
	<div style="height:10px; background:#27aae1;"></div>
	
	<!-- FOOTER END -->
	
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script>
		$('#year').text(new Date().getFullYear());
	</script>
</body>
</html>