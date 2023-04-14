<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<?php $_SESSION["TrackingURL"]=$_SERVER["PHP_SELF"]; ?>
<?php Confirm_Login(); ?>
<?php

if(isset($_POST["Submit"])){
	$PostTitle=$_POST["PostTitle"];
	$Category=$_POST["CategoryTitle"];
	$Image=$_FILES["image"]["name"];
	$Target="Uploads/".basename($_FILES["image"]["name"]);
	$PostContent=$_POST["Post"];
	$Admin = $_SESSION["Username"];
	date_default_timezone_set("Europe/London");
	$CurrentTime=time();
	$DateTime=strftime("%B-%d-%Y %H:%M:%S",$CurrentTime);
	
	if(empty($PostTitle)){
		$_SESSION["ErrorMessage"] = "Post title must be filled in.";
		Redirect_to("AddNewPost.php");
	}elseif(strlen($PostTitle)<5){
		$_SESSION["ErrorMessage"] = "Post title should be greater than 5 characters.";
		Redirect_to("AddNewPost.php");
	}elseif(strlen($PostTitle)>49){
		$_SESSION["ErrorMessage"] = "Post title should be less than 50 characters.";
		Redirect_to("AddNewPost.php");
	}elseif(strlen($PostContent)>9999){
		$_SESSION["ErrorMessage"] = "Post content should be less than 10000 characters.";
		Redirect_to("AddNewPost.php");
	}elseif(!preg_match("/^[A-Za-z0-9. _,.?!]+$/",$PostTitle)){
		$_SESSION["ErrorMessage"] = "Post title contains invalid characters.";
		Redirect_to("AddNewPost.php");
	}else{	
		$ConnectingDB;
		$sql = "INSERT INTO posts(datetime,title,category,author,image,post)
		VALUES(:datetimE,:titlE,:categorY,:authoR,:imagE,:posT)";
		$stmt = $ConnectingDB->prepare($sql);
		$stmt->bindValue(':datetimE',$DateTime);
		$stmt->bindValue(':titlE',$PostTitle);
		$stmt->bindValue(':categorY',$Category);
		$stmt->bindValue(':authoR',$Admin);
		$stmt->bindValue(':imagE',$Image);
		$stmt->bindValue(':posT',$PostContent);
		$Execute = $stmt->execute();
		move_uploaded_file($_FILES["image"]["tmp_name"],$Target);
		if($Execute){
			$_SESSION["SuccessMessage"] = "Post with id: ".$ConnectingDB->lastInsertId() . " added successfully.";
			Redirect_to("AddNewPost.php");
		}else{
			$_SESSION["ErrorMessage"] = "Something went wrong.";
			Redirect_to("AddNewPost.php");
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
	<title>Add New Post</title>
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
				<h1><i class="fas fa-edit"></i>Add New Post</h1>
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
					  echo SuccessMessage(); ?>
				<form class="" action="AddNewPost.php" method="post" enctype="multipart/form-data">
					<div class="card bg-secondary text-light mb-3">
						<div class="card-body bg-dark">
							<div class="form-group">
								<label for="title"> <span class="FieldInfo"> Post Title: </span></label>
								<input class="form-control" type="text" name="PostTitle" id="title" placeholder="Type title here"></input>
							</div>
							<div class="form-group">
								<label for="CategoryTitle"> <span class="FieldInfo"> Choose Category: </span></label>
								<select class="form-control" id="CategoryTitle" name="CategoryTitle">
									<?php //Fetch category names from category table
									
									$ConnectingDB;
									$sql = "SELECT id,title FROM category";
									$stmt = $ConnectingDB->query($sql);
									while ($DataRows = $stmt->fetch()){
										$Id = $DataRows["id"];
										$CategoryName = $DataRows["title"];
									
									?>
									<option> <?php echo $CategoryName ?> </option>
									<?php } //while loop ending ?>
								</select>
							</div>
							<div class="form-group">
								<label for="image"> <span class="FieldInfo"> Image: </span></label>
								<div class="custom-file">
									<input class="custom-file-input" type="File" name="image" id="imageSelect" value="">
									<label for="imageSelect" class="custom-file-label">Upload an image</label>
								</div>
							</div>
							<div class="form-group">
								<label for="PostTitle"> <span class="FieldInfo"> Post: </span></label>
								<textarea class="form-control" id="Post" name="Post" rows="8" cols="80"></textarea>
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