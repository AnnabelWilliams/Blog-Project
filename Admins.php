<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<?php $_SESSION["TrackingURL"]=$_SERVER["PHP_SELF"]; ?>
<?php Confirm_Login(); ?>
<?php

if(isset($_POST["Submit"])){
	$Username=$_POST["Username"];
	$Name=$_POST["Name"];
	$Password=$_POST["Password"];
	$ConfirmPassword=$_POST["ConfirmPassword"];
	$Admin = $_SESSION["Username"];
	date_default_timezone_set("Europe/London");
	$CurrentTime=time();
	$DateTime=strftime("%B-%d-%Y %H:%M:%S",$CurrentTime);
	
	if(empty($Username)||empty($Password)||empty($ConfirmPassword)){
		$_SESSION["ErrorMessage"] = "All fields must be filled in.";
		Redirect_to("Admins.php");
	}elseif($Password != $ConfirmPassword){
		$_SESSION["ErrorMessage"] = "Passwords must match.";
		Redirect_to("Admins.php");
	}elseif(CheckUserNameExist($Username)){
		$_SESSION["ErrorMessage"] = "Username already exists.";
		Redirect_to("Admins.php");
	}else{	
		$ConnectingDB;
		$sql = "INSERT INTO admins(datetime,username,password,aname,addedby)
		VALUES(:datetimE,:usernamE,:passworD,:anamE,:addedbY)";
		$stmt = $ConnectingDB->prepare($sql);
		$stmt->bindValue(':datetimE',$DateTime);
		$stmt->bindValue(':usernamE',$Username);
		$stmt->bindValue(':passworD',$Password);
		$stmt->bindValue(':anamE',$Name);
		$stmt->bindValue(':addedbY',$Admin);
		$Execute = $stmt->execute();
		if($Execute){
			$_SESSION["SuccessMessage"] = "Admin with username: $Username added successfully.";
			Redirect_to("Admins.php");
		}else{
			$_SESSION["ErrorMessage"] = "Something went wrong.";
			Redirect_to("Admins.php");
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
	<title>Admin Page</title>
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
				<h1><i class="fas fa-user"></i>Manage Admins</h1>
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
				<form class="" action="Admins.php" method="post">
					<div class="card bg-secondary text-light mb-3">
						<div class="card-header">
							<h1>Add New Admin</h1>
						</div>
						<div class="card-body bg-dark">
							<div class="form-group">
								<label for="Username"> <span class="FieldInfo"> Username: </span></label>
								<input class="form-control" type="text" name="Username" id="Username" ></input>
							</div>
							<div class="form-group">
								<label for="Name"> <span class="FieldInfo"> Name: </span></label>
								<input class="form-control" type="text" name="Name" id="Name" ></input>
								<small class="text-muted">*Optional</small>
							</div>
							<div class="form-group">
								<label for="Password"> <span class="FieldInfo"> Password: </span></label>
								<input class="form-control" type="password" name="Password" id="Password"></input>
							</div>
							<div class="form-group">
								<label for="ConfirmPassword"> <span class="FieldInfo"> Confirm Password: </span></label>
								<input class="form-control" type="password" name="ConfirmPassword" id="ConfirmPassword"></input>
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
				<h2>Existing Admins</h2>
				<table class="table table-striped table-hover">
					<thead class="thead-dark">
						<tr>
							<th>No. </th>
							<th>Username</th>
							<th>Admin Name</th>
							<th>Added by</th>
							<th>Date&Time</th>
							<th>Delete</th>
						</tr>
					</thead>
				<?php
				
				$ConnectingDB;
				//SQL for pagination
				if(isset($_GET["page"])){
					$Page = $_GET["page"];
					if($Page < 1){
						$ShowPostFrom=0;
					}else{
						$ShowPostFrom = ($Page*5)-5;
					}
					$sql = "SELECT * FROM admins ORDER BY id asc LIMIT $ShowPostFrom,5";
					$Execute = $ConnectingDB->query($sql);
					$SrNo=$Page*5-5;
				//SQL default
				}else{
					$sql="SELECT * FROM admins ORDER BY id asc";
					$Execute = $ConnectingDB->query($sql);
					$SrNo = 0;
				}
				while($DataRows=$Execute->fetch()){
					$AdminId = $DataRows["id"];
					$AdminName = $DataRows["aname"];
					if(empty($AdminName)){
						$AdminName="N/A";
					}
					$AdminUsername = $DataRows["username"];
					$AddedBy = $DataRows["addedby"];
					$DateTime = $DataRows["datetime"];
					$SrNo++;
				?>
					<tbody>
						<tr>
							<td><?php echo htmlentities($SrNo); ?></td>
							<td><?php echo htmlentities($AdminUsername); ?></td>
							<td><?php echo htmlentities($AdminName); ?></td>
							<td><?php echo htmlentities($AddedBy); ?></td>
							<td><?php echo htmlentities($DateTime); ?></td>
							<td><a class="btn btn-danger" href="DeleteAdmin.php?id=<?php echo $AdminId; ?>">Delete</a></td>
						</tr>
					</tbody>
					<?php } // End of while loop ?>
				</table>
				<!-- Pagination -->
				<nav>
					<ul class="pagination pagination-lg">
						<?php
							$ConnectingDB;
							$sql = "SELECT COUNT(*) FROM admins";
							$stmt=$ConnectingDB->query($sql);
							$RowPagination = $stmt->fetch();
							$TotalPosts = array_shift($RowPagination);
							$PostPagination = ceil($TotalPosts/5);
						?>
						<?php //Backward button
						if(isset($Page)&&!empty($Page)){
						if($Page>1){
						
						?>
						<li class="page-item">
							<a href="Admins.php?page=<?php echo $Page-1; ?>" class="page-link">&laquo;</a>
						</li>
						<?php }} ?>
						<?php
						for($i=1;$i<=$PostPagination;$i++){
							if(isset($Page)&&!empty($Page)){
							if($i==$Page){
						?>
						<li class="page-item active">
							<a href="Admins.php?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
						</li>
							<?php }else{ ?>
						<li class="page-item">
							<a href="Admins.php?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
						</li>
							<?php }} ?>
						<?php } //End of for ?>
						<?php //Forward button
						if(isset($Page)&&!empty($Page)){
						if($Page+1<=$PostPagination){
						
						?>
						<li class="page-item">
							<a href="Admins.php?page=<?php echo $Page+1; ?>" class="page-link">&raquo;</a>
						</li>
						<?php }} ?>
					</ul>
				</nav>
			<!-- Pagination End -->
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