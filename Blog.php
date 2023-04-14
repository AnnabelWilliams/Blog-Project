<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<?php

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
	<title>Blog Page</title>
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
	
	<div class="container">
		<div class="row mt-4">
			<!-- Main Area -->
			<div class="col-sm-8" style="min-height:40px;">
				<?php echo ErrorMessage();
					  echo SuccessMessage(); ?>
				<h1>The Blog</h1>
				<?php
					$ConnectingDB;
					$sql;
					// SQL when search button active
					if(isset($_GET["SearchButton"])){
						$Search = $_GET["search"];
						$sql = "SELECT * FROM posts
						WHERE datetime LIKE :search
						OR category LIKE :search
						OR post LIKE :search
						OR title LIKE :search
						ORDER BY id desc";
						$stmt = $ConnectingDB->prepare($sql);
						$stmt->bindValue(':search','%'.$Search.'%');
						$stmt->execute();
					// SQL when pagination is active
					}elseif(isset($_GET["page"])){
						$Page = $_GET["page"];
						if($Page < 1){
							$ShowPostFrom=0;
						}else{
							$ShowPostFrom = ($Page*5)-5;
						}
						$sql = "SELECT * FROM posts ORDER BY id desc LIMIT $ShowPostFrom,5";
						$stmt = $ConnectingDB->query($sql);
					}elseif(isset($_GET["category"])) {
						$Category = $_GET["category"];
						$sql = "SELECT * FROM posts WHERE category='$Category' ORDER BY id desc";
						$stmt = $ConnectingDB->query($sql);
					// SQL default
					}else{
						$sql = "SELECT * FROM posts ORDER BY id desc";
						$stmt = $ConnectingDB->query($sql);
					}
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
							<a href="Blog.php?page=<?php echo $Page-1; ?>" class="page-link">&laquo;</a>
						</li>
						<?php }} ?>
						<?php
						for($i=1;$i<=$PostPagination;$i++){
							if(isset($Page)&&!empty($Page)){
							if($i==$Page){
						?>
						<li class="page-item active">
							<a href="Blog.php?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
						</li>
							<?php }else{ ?>
						<li class="page-item">
							<a href="Blog.php?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
						</li>
							<?php }} ?>
						<?php } //End of for ?>
						<?php //Forward button
						if(isset($Page)&&!empty($Page)){
						if($Page+1<=$PostPagination){
						
						?>
						<li class="page-item">
							<a href="Blog.php?page=<?php echo $Page+1; ?>" class="page-link">&raquo;</a>
						</li>
						<?php }} ?>
					</ul>
				</nav>
				
				<!-- Pagination End -->
			</div>
			<!-- Main Area End -->
			<!-- Side Area -->
			
			<div class="col-sm-4" style="min-height:40px;">
				<div class="card mt-4">
					<div class="card-body">
						<img src="images/Green_Slime_Dangerous.png" class="d-block img-fluid mb-3" style="min-height:350px;" alt="">
						<div class="text-center">
							 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse feugiat non sapien a lacinia. Fusce vel dapibus sapien. Maecenas dapibus nunc vitae condimentum eleifend. Maecenas vulputate suscipit dictum. Fusce vestibulum pharetra tortor. Nam purus sapien, ultrices rhoncus quam eget, imperdiet tristique ipsum. Mauris varius vitae ligula eget efficitur. Nullam mattis nibh eros, eu elementum sem scelerisque at. Nullam nec fermentum metus. Vivamus quis nisi diam. 
						</div>
					</div>
				</div>
				<div class="card mt-4">
					<div class="card-header bg-dark text-light">
						<h2 class="lead">Sign up!</h2>
					</div>
					<div class="card-body">	
						<button type="button" class="btn btn-success btn-block text-center text-white mb-4" name="button">Join the forum</button>
						<button type="button" class="btn btn-danger btn-block text-center text-white mb-4" name="button">Login</button>
						<div class="input-group mb-3">
							<input type="text" class="form-control" name="" placeholder="Enter your email" value="">
							<div class="input-group-append">
								<button type="button" class="btn btn-primary btn-sm text-center text-white" name="button">Subscribe</button>
							</div>
						</div>
					</div>
				</div>
				<br>
				<div class="card">
					<div class="card-header bg-primary text-light">
						<h2 class="lead">Categories</h2>
					</div>
						<div class="card-body">
							<?php
							$ConnectingDB;
							$sql = "SELECT * FROM category";
							$stmt = $ConnectingDB->query($sql);
							while ($DataRows = $stmt->fetch()){
								$CategoryId = $DataRows["id"];
								$CategoryName = $DataRows["title"];
							?>
							<a href="Blog.php?category=<?php echo $CategoryName; ?>"> <span class="heading"> <?php echo $CategoryName; ?></span><br> </a>
							<?php } //End of while loop ?>
						
						</div>
				</div>
				<br>
				<div class="card">
					<div class="card-header bg-info text-white">
						<h2 class="lead">Recent Posts</h2>
					</div>
						<?php
						$ConnectingDB;
						$sql="SELECT * FROM posts ORDER BY id desc LIMIT 0,5";
						$stmt = $ConnectingDB->query($sql);
						while ($DataRows=$stmt->fetch()){
							$Id = $DataRows["id"];
							$Title = $DataRows["title"];
							$DateTime = $DataRows["datetime"];
							$Image = $DataRows["image"];
						?>
						<div class="media">
							<img src="Uploads/<?php echo htmlentities($Image); ?>" class="d-block img-fluid align-self-start" width="90" height="94" alt="">
							<div class="media-body">
								<a href="FullPost.php?id=<?php echo htmlentities($Id); ?>" target="_blank" ><h6 class="lead"><?php echo htmlentities($Title); ?></h6></a>
								<p class="small"><?php echo htmlentities($DateTime); ?></p>
							</div>
						</div>
						<hr>
						<?php } //End of while loop ?>
					</div>
			</div>
			
			<!-- Side Area End -->
		</div>
	</div>
	
	<!-- HEADER END -->
	<br>
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