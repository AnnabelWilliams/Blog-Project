<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<?php $_SESSION["TrackingURL"]=$_SERVER["PHP_SELF"]; ?>
<?php Confirm_Login(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<script src="https://kit.fontawesome.com/d6ea2f9932.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="CSS/styles.css">
	<title>Blog Posts</title>
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
					<h1><i class="fa-solid fa-blog" style="color:#27aae1;"></i> Blog Posts </h1>
				</div>
				<div class="col-lg-3 mb-2">
					<a href="AddNewPost.php" class="btn btn-primary btn-block">
						<i class="fas fa-edit"></i> Add New Post
					</a>
				</div>
				<div class="col-lg-3 mb-2">
					<a href="Categories.php?page=1" class="btn btn-info btn-block">
						<i class="fas fa-folder-plus"></i> Add New Category
					</a>
				</div>
				<div class="col-lg-3 mb-2">
					<a href="Admins.php?page=1" class="btn btn-warning btn-block">
						<i class="fas fa-user-plus"></i> Add New Admin
					</a>
				</div>
				<div class="col-lg-3 mb-2">
					<a href="Comments.php" class="btn btn-success btn-block">
						<i class="fas fa-check"></i> Approve Comments
					</a>
				</div>
			</div>
		</div>
	</header>
	
	<!-- HEADER END -->
	
	<!-- MAIN AREA -->
	
	<section class="container py-2 mb-4">
		<div class="row">
			<div class="col-lg-12">
				<?php echo ErrorMessage();
					  echo SuccessMessage(); ?>
				<table class="table table-striped">
				<thead class="thead-dark">
					<tr>
						<th>#</th>
						<th>Title</th>
						<th>Category</th>
						<th>Date&Time</th>
						<th>Author</th>
						<th>Banner</th>
						<th>Comments</th>
						<th>Action</th>
						<th>Live Preview</th>
					</tr>
				</thead>
					<?php 
					
					$ConnectingDB;
					//SQL when page is set for pagination
					if(isset($_GET["page"])){
						$Page = $_GET["page"];
						if($Page < 1){
							$ShowPostFrom=0;
						}else{
							$ShowPostFrom = ($Page*5)-5;
						}
						$sql = "SELECT * FROM posts ORDER BY id desc LIMIT $ShowPostFrom,5";
						$stmt = $ConnectingDB->query($sql);
						$i=$Page*5-5;
					//SQL Default
					}else{
						$sql = "SELECT * FROM posts ORDER BY id desc";
						$stmt = $ConnectingDB->query($sql);
						$i=0;
					}
					while ($DataRows = $stmt->fetch()){
						$Id = $DataRows["id"];
						$DateTime = $DataRows["datetime"];
						$PostTitle = $DataRows["title"];
						$CategoryName = $DataRows["category"];
						$Admin = $DataRows["author"];
						$Image = $DataRows["image"];
						$PostContent = $DataRows["post"];
						$i++;
					?>
					<tbody>
					<tr>
						<td><?php echo $i ?></td>
						<td><?php if(strlen($PostTitle)>20){
								  $PostTitle=substr($PostTitle,0,15)."...";} ?>
							<?php echo $PostTitle ?></td>
						<td><?php if(strlen($CategoryName)>8){
								  $CategoryName=substr($CategoryName,0,8)."...";} ?>
							<?php echo $CategoryName ?></td>
						<td><?php if(strlen($DateTime)>11){
								  $DateTime=substr($DateTime,0,11)."...";} ?>
							<?php echo $DateTime ?></td>
						<td><?php if(strlen($Admin)>20){
								  $Admin=substr($Admin,0,15)."...";} ?>
							<?php echo $Admin ?></td>
						<td><img src="Uploads/<?php echo $Image ?>" width="170px;"</td>
						<td>
							<span class="badge badge-success"><?php TotalComments($Id,"ON"); ?></span>
							<span class="badge badge-danger"><?php TotalComments($Id,"OFF"); ?></span>
						</td>
						<td>
							<a href="EditPost.php?id=<?php echo $Id; ?>"><span class="btn btn-warning mb-1">Edit</span></a>
							<a href="DeletePost.php?id=<?php echo $Id; ?>"><span class="btn btn-danger mb-1">Delete</span></a>
						</td>
						<td><a href="FullPost.php?id=<?php echo $Id; ?>" taget="_blank"><span class="btn btn-primary">Live Preview</span></a></td>
					</tr>
					</tbody>
					<?php } //End of while loop ?>
				</table>
			<!-- Pagination -->
				<nav>
					<ul class="pagination pagination-lg">
						<?php
							$ConnectingDB;
							$sql = "SELECT COUNT(*) FROM posts";
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
							<a href="Posts.php?page=<?php echo $Page-1; ?>" class="page-link">&laquo;</a>
						</li>
						<?php }} ?>
						<?php
						for($i=1;$i<=$PostPagination;$i++){
							if(isset($Page)&&!empty($Page)){
							if($i==$Page){
						?>
						<li class="page-item active">
							<a href="Posts.php?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
						</li>
							<?php }else{ ?>
						<li class="page-item">
							<a href="Posts.php?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
						</li>
							<?php }} ?>
						<?php } //End of for ?>
						<?php //Forward button
						if(isset($Page)&&!empty($Page)){
						if($Page+1<=$PostPagination){
						
						?>
						<li class="page-item">
							<a href="Posts.php?page=<?php echo $Page+1; ?>" class="page-link">&raquo;</a>
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