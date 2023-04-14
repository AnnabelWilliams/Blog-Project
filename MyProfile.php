<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<?php $_SESSION["TrackingURL"]=$_SERVER["PHP_SELF"]; ?>
<?php Confirm_Login(); ?>
<?php
//Fetching existing admin data
$AdminId= $_SESSION["User_ID"];
$ConnectingDB;
$sql = "SELECT * FROM admins WHERE id='$AdminId'";
$stmt = $ConnectingDB->query($sql);
while ($DataRows = $stmt->fetch()){
	$ExistingName = $DataRows["aname"];
	$ExistingUsername = $DataRows["username"];
	$ExistingHeadline = $DataRows["aheadline"];
	$ExistingBio = $DataRows["abio"];
	$ExistingImage = $DataRows["aimage"];
}

if(isset($_POST["Submit"])){
	$AName=$_POST["Name"];
	$AHeadline=$_POST["Headline"];
	$ABio=$_POST["Bio"];
	$Image=$_FILES["image"]["name"];
	$Target="Images/".basename($_FILES["image"]["name"]);
	date_default_timezone_set("Europe/London");
	$CurrentTime=time();
	$DateTime=strftime("%B-%d-%Y %H:%M:%S",$CurrentTime);
	
	if(strlen($AHeadline)>12){
		$_SESSION["ErrorMessage"] = "Headline should be less than 12 characters.";
		Redirect_to("MyProfile.php");
	}elseif(strlen($ABio)>500){
		$_SESSION["ErrorMessage"] = "Bio should be less than 500 characters.";
		Redirect_to("MyProfile.php");
	}elseif(strlen($ABio)<1||strlen($AName)<1||strlen($AHeadline)<1){
		$_SESSION["ErrorMessage"] = "Please complete all mandatory fields.";
		Redirect_to("MyProfile.php");
	}else{	
		$ConnectingDB;
		if (!empty($Image)){
			$sql = "UPDATE admins 
					SET aname='$AName', aheadline='$AHeadline', abio='$ABio', aimage='$Image' 
					WHERE id='$AdminId'";
		}else{
			$sql = "UPDATE admins 
					SET aname='$AName', aheadline='$AHeadline', abio='$ABio'
					WHERE id='$AdminId'";
		}
		
		$Execute = $ConnectingDB->query($sql);
		move_uploaded_file($_FILES["image"]["tmp_name"],$Target);
		if($Execute){
			$_SESSION["SuccessMessage"] = "Details updated successfully.";
			Redirect_to("MyProfile.php");
		}else{
			$_SESSION["ErrorMessage"] = "Something went wrong.";
			Redirect_to("MyProfile.php");
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
	<link rel="stylesheet" type="text/css" href="CSS/styles.css">
	<title>My Profile</title>
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
			<ul class="navbar-nav mr-auto">
				<li class="nav-item">
					 <?php
					if(empty($_SESSION["Username"])){
						?> <a href="Login.php" class="nav-link text-success"><i class="fa-solid fa-user"></i> Login
					<?php }else {
						?> <a href="MyProfile.php" class="nav-link text-success"><i class="fa-solid fa-user"></i> <?php echo $_SESSION["Username"];
					 } ?>
					</a>
				</li>
				<li class="nav-item">
					<a href="Dashboard.php" class="nav-link">Dashboard</a>
				</li>
				<li class="nav-item">
					<a href="Posts.php?page=1" class="nav-link">Posts</a>
				</li>
				<li class="nav-item">
					<a href="Categories.php?page=1" class="nav-link">Categories</a>
				</li>
				<li class="nav-item">
					<a href="Admins.php?page=1" class="nav-link">Manage Admins</a>
				</li>
				<li class="nav-item">
					<a href="Comments.php?upage=1&apage=1" class="nav-link">Comments</a>
				</li>
				<li class="nav-item">
					<a href="Blog.php?page=1" class="nav-link">Live Blog</a>
				</li>
			</ul>
			<ul class="navbar-nav ml-auto">
				<li class="nav-item"><a href="Logout.php" class="nav-link text-danger">
				<i class="fa-solid fa-user-times"></i> Logout</a></li>
			</ul>
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
				<h1><i class="fas fa-user text-success mr-2"></i>@<?php echo $ExistingUsername; ?></h1>
				<h2><?php echo $ExistingHeadline; ?></h2>
				</div>
			</div>
		</div>
	</header>
	
	<!-- HEADER END -->
	
	<!-- MAIN AREA -->
	
	<section class="container py-2 mb-4">
		<div class="row">
			<!-- Left Area -->
			<div class="col-md-3">
				<div class="card">
					<div class="card-header bg-dark text-light">
						<h3><?php echo $ExistingName; ?></h3>
					</div>
					<div class="card-body">
						<img src="Images/<?php echo $ExistingImage; ?>" class="block img-fluid mb-3" alt="">
						<div class="">
							<?php echo $ExistingBio; ?>
						</div>
					</div>
				</div>
			</div>
			<!-- Left Area End -->
			<!-- Right Area -->
			<div class="col-md-9" style="min-height:400px">
				<?php echo ErrorMessage();
					  echo SuccessMessage(); ?>
				<form class="" action="MyProfile.php" method="post" enctype="multipart/form-data">
					<div class="card bg-secondary text-light mb-3">
						<div class="card-header bg-secondary text-light">
							<h4>Edit Profile</h4>
						</div>
						<div class="card-body bg-dark">
							<div class="form-group">
								<input class="form-control" type="text" name="Name" id="title" placeholder="Your name here" value="<?php echo $ExistingName; ?>"></input>
							</div>
							<div class="form-group">
								<input class="form-control" type="text" name="Headline" id="title" placeholder="Headline" value="<?php echo $ExistingHeadline; ?>"></input>
								<small class="text-muted">Add a professional headline</small>
								<span class="text-danger">Not more than 12 characters</span>
							</div>
							<div class="form-group">
								<textarea placeholder="Bio" class="form-control" id="Post" name="Bio" rows="8" cols="80"><?php echo $ExistingBio; ?></textarea>
							</div>
							<div class="form-group">
								<div class="custom-file">
									<input class="custom-file-input" type="File" name="image" id="imageSelect" value="">
									<label for="imageSelect" class="custom-file-label">Upload an image</label>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6 mb-2">
									<a href="Dashboard.php" class="btn btn-warning btn-block"><i class="fas fa-arrow-left"></i>Back to Dashboard</a>
								</div>
								<div class="col-lg-6 mb-2">
									<button type="submit" name="Submit" class="btn btn-success btn-block">
										<i class="fas fa-check"></i>Publish
									</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<!-- Right Area End -->
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