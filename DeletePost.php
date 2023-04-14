<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<?php $_SESSION["TrackingURL"]=$_SERVER["PHP_SELF"]; ?>
<?php Confirm_Login(); ?>
<?php

$SearchQueryParameter = $_GET["id"];$ConnectingDB;

$sql2 = "SELECT * FROM posts WHERE id='$SearchQueryParameter'";
$stmtPost = $ConnectingDB->query($sql2);
while ($DataRows=$stmtPost->fetch()){
	$PostTitleUpdate = $DataRows["title"];
	$CategoryNameUpdate = $DataRows["category"];
	$ImageUpdate = $DataRows["image"];
	$PostContentUpdate = $DataRows["post"];

if(isset($_POST["Submit"])){
	$ConnectingDB;
	$sql = "DELETE FROM posts WHERE id='$SearchQueryParameter'";
	$Execute = $ConnectingDB->query($sql);
	if($Execute){
		$Target_Path_Delete_Image = "Uploads/$ImageUpdate";
		unlink($Target_Path_Delete_Image);
		$_SESSION["SuccessMessage"] = "Post deleted successfully.";
		Redirect_to("Posts.php");
	}else{
		$_SESSION["ErrorMessage"] = "Something went wrong.";
		Redirect_to("Posts.php");
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
	<title>Delete Post</title>
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
				<h1><i class="fas fa-edit"></i>Delete Post</h1>
				</div>
			</div>
		</div>
	</header>
	
	<!-- HEADER END -->
	
	<!-- MAIN AREA -->
	
	<section class="container py-2 mb-4">
		<div class="row">
			<div class="offset-lg-1 col-lg-10" style="min-height:400px">
				<?php echo ErrorMessage();
					  echo SuccessMessage();
						}
						?>
				<form class="" action="DeletePost.php?id=<?php echo $SearchQueryParameter; ?>" method="post" enctype="multipart/form-data">
					<div class="card bg-secondary text-light mb-3">
						<div class="card-body bg-dark">
							<div class="form-group">
								<label for="title"> <span class="FieldInfo"> Post Title: </span></label>
								<input disabled class="form-control" type="text" name="PostTitle" id="title" placeholder="Type title here" value="<?php echo $PostTitleUpdate;?>"></input>
							</div>
							<div class="form-group">
								<span class="FieldInfo">Existing Category: </span>
								<?php echo $CategoryNameUpdate; ?>
							</div>
							<div class="form-group">
								<span class="FieldInfo">Existing Image: </span>
								<img src="Uploads/<?php echo $ImageUpdate; ?>">
							</div>
							<div class="form-group">
								<label for="PostTitle"> <span class="FieldInfo"> Post: </span></label>
								<textarea disabled class="form-control" id="Post" name="Post" rows="8" cols="80"><?php echo $PostContentUpdate;?></textarea>
							</div>
							<div class="row">
								<div class="col-lg-6 mb-2">
									<a href="Dashboard.php" class="btn btn-warning btn-block"><i class="fas fa-arrow-left"></i>Back to Dashboard</a>
								</div>
								<div class="col-lg-6 mb-2">
									<button type="submit" name="Submit" class="btn btn-danger btn-block">
										<i class="fas fa-trash"></i>Delete
									</button>
								</div>
							</div>
						</div>
					</div>
				</form>
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