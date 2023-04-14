<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<?php $_SESSION["TrackingURL"]=$_SERVER["PHP_SELF"]; ?>
<?php Confirm_Login(); ?>
<?php
//Fetching existing admin data
$SearchQueryParameter= $_GET["username"];
$ConnectingDB;
$sql = "SELECT * FROM admins WHERE username='$SearchQueryParameter'";
$stmt = $ConnectingDB->query($sql);
$Result = $stmt->rowcount();
if($Result==1){
	while ($DataRows = $stmt->fetch()){
		$ExistingName = $DataRows["aname"];
		$ExistingUsername = $DataRows["username"];
		$ExistingHeadline = $DataRows["aheadline"];
		$ExistingBio = $DataRows["abio"];
		$ExistingImage = $DataRows["aimage"];
	}
}else{
	$_SESSION["ErrorMessage"]="Bad Request";
	Redirect_to("Blog.php?page=1");
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
	<title>Profile</title>
</head>
<body>
	<!-- NAVBAR -->
	<div style="height:10px; background:#27aae1;"></div>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container">
			<a href="Blog.php?page=1" class="navbar-brand">BELLE.COM</a>
			<button class="navbar-toggler" data-toggle="collapse" data-target="#navbarcollapseCMS">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarcollapseCMS">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item">
					<a href="Blog.php?page=1" class="nav-link">Home</a>
				</li>
			</ul>
			<ul class="navbar-nav ml-auto">
				<form class="form-inline" action="Blog.php">
					<div class="form-group">
					<input class="form-control mr-2" type="text" name="search" placeholder="Search here" value="">
					<button class="btn btn-primary" name="SearchButton">Go</button>
					</div>
				</form>
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
				<div class="col-md-6">
				<h1><i class="fas fa-user text-success mr-2" style="color:#27aae1;"></i> <?php echo $ExistingName; ?></h1>
				<h3><?php echo $ExistingHeadline; ?></h3>
				</div>
			</div>
		</div>
	</header>
	
	<!-- HEADER END -->
	<section class="container py-2 mb-4">
		<div class="row">
			<div class="col-md-3">
				<img src="Images/<?php echo $ExistingImage; ?>" class="d-blok img-fluid mb-3 rounded-circle" alt="">
			</div>
			<div class="col-md-9" style="min-height:400px;">
				<div class="card mb-3">
					<div class="card-body">
						<p class="lead"><?php echo $ExistingBio; ?></p>
					</div>
				</div>
				<div>
					<h1>Recent Posts</h1>
				</div>
				<?php
					$ConnectingDB;
					
					$sql = "SELECT * FROM posts WHERE author='$SearchQueryParameter' ORDER BY id desc LIMIT 0,3";
					$stmt = $ConnectingDB->query($sql);
					
					while ($DataRows = $stmt->fetch()){
						$Id = $DataRows["id"];
						$DateTime = $DataRows["datetime"];
						$PostTitle = $DataRows["title"];
						$CategoryName = $DataRows["category"];
						$Admin = $DataRows["author"];
						$Image = $DataRows["image"];
						$PostContent = $DataRows["post"];
					
				?>
				<div class="card mb-3">
					<?php if(empty($Image)) {$Image="grid_0.png";} ?>
					<img src="Uploads/<?php echo htmlentities($Image); ?>" style="max-height:450px;" class="img-fluid card-top" />
					<div class="card-body">
						<h4 class="card-title"><?php echo htmlentities($PostTitle); ?></h4>
						<small class="text-muted"> Category: <span class="text-dark"><a href="Blog.php?category=<?php echo $CategoryName ?>"><?php echo $CategoryName ?></a></span> & Written by <span class="text-dark"><a href="Profile.php?username=<?php echo htmlentities($Admin); ?>"><?php echo htmlentities($Admin); ?></a></span> on <?php echo htmlentities($DateTime); ?> </small>
						<span style="float:right" class="badge badge-dark text-light">Comments <?php TotalComments($Id,"ON"); ?></span>
						<hr>
						<p class="card-text">
							<?php if(strlen($PostContent)>150){
									$PostContent = substr($PostContent,0,150)."...";
								  }
							echo htmlentities($PostContent); ?></p>
						<a href="FullPost.php?id=<?php echo $Id; ?>" style="float:right">
							<span class="btn btn-info">Read More</span>
						</a>
					</div>
				</div>
				<?php } //End of while loop ?>
			</div>
		</div>
	</section>
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