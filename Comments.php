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
	<link rel="stylesheet" href="CSS/styles.css">
	<title>Comments</title>
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
					<a href="MyProfile.php" class="nav-link text-success"><i class="fa-solid fa-user"></i> My Profile</a>
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
				<h1><i class="fas fa-comments" style="color:#27aae1;"></i> Manage Comments</h1>
				</div>
			</div>
		</div>
	</header>
	
	<!-- HEADER END -->
	
	<!-- MAIN AREA -->
	
	<section class="container py-2 mb-4">
		<div class="row" style="min-height:30px;">
			<div class="col-lg-12" style="min-height:400px;">
				<?php echo ErrorMessage();
					  echo SuccessMessage(); ?>
				<h2>Unapproved Comments</h2>
				<table class="table table-striped table-hover">
					<thead class="thead-dark">
						<tr>
							<th>No. </th>
							<th>Name</th>
							<th>Date&Time</th>
							<th>Comment</th>
							<th>Approve</th>
							<th>Delete</th>
							<th>Details</th>
						</tr>
					</thead>
				<?php
				
				$ConnectingDB;
				//SQL for pagination
				if(isset($_GET["upage"])){
					$UPage = $_GET["upage"];
					if($UPage < 1){
						$ShowPostFrom=0;
					}else{
						$ShowPostFrom = ($UPage*5)-5;
					}
					$sql = "SELECT * FROM comments WHERE status='OFF' ORDER BY id desc LIMIT $ShowPostFrom,5";
					$Execute = $ConnectingDB->query($sql);
					$SrNo=$UPage*5-5;
				//SQL default
				}else{
					$sql="SELECT * FROM comments WHERE status='OFF' ORDER BY id desc";
					$Execute = $ConnectingDB->query($sql);
					$SrNo = 0;
				}
				while($DataRows=$Execute->fetch()){
					$CommentId = $DataRows["id"];
					$DateTime = $DataRows["datetime"];
					$CommenterName = $DataRows["name"];
					$CommentContent = $DataRows["comment"];
					$CommentPostId = $DataRows["post_id"];
					$SrNo++;
				?>
					<tbody>
						<tr>
							<td><?php echo htmlentities($SrNo); ?></td>
							<td style="word-wrap: break-word;min-width: 160px;max-width: 450px;"><?php echo htmlentities($CommenterName); ?></td>
							<td><?php echo htmlentities($DateTime); ?></td>
							<td style="word-wrap: break-word;min-width: 160px;max-width: 450px;"><?php echo htmlentities($CommentContent); ?></td>
							<td><a class="btn btn-success" href="ApproveComment.php?id=<?php echo $CommentId; ?>">Approve</a></td>
							<td><a class="btn btn-danger" href="DeleteComment.php?id=<?php echo $CommentId; ?>">Delete</a></td>
							<td><a class="btn btn-primary" href="FullPost.php?id=<?php echo $CommentPostId; ?>">Live Preview</a></td>
						</tr>
					</tbody>
					<?php } // End of while loop ?>
				</table>
				<!-- Pagination -->
				<nav>
					<ul class="pagination pagination-lg">
						<?php
							$ConnectingDB;
							$sql = "SELECT COUNT(*) FROM comments WHERE status='OFF'";
							$stmt=$ConnectingDB->query($sql);
							$RowPagination = $stmt->fetch();
							$TotalPosts = array_shift($RowPagination);
							$PostPagination = ceil($TotalPosts/5);
						?>
						<?php //Backward button
						if(isset($UPage)&&!empty($UPage)){
						if($UPage>1){
						
						?>
						<li class="page-item">
							<a href="Comments.php?upage=<?php echo $UPage-1; ?>&apage=1" class="page-link">&laquo;</a>
						</li>
						<?php }} ?>
						<?php
						for($i=1;$i<=$PostPagination;$i++){
							if(isset($UPage)&&!empty($UPage)){
							if($i==$UPage){
						?>
						<li class="page-item active">
							<a href="Comments.php?upage=<?php echo $i; ?>&apage=1" class="page-link"><?php echo $i; ?></a>
						</li>
							<?php }else{ ?>
						<li class="page-item">
							<a href="Comments.php?upage=<?php echo $i; ?>&apage=1" class="page-link"><?php echo $i; ?></a>
						</li>
							<?php }} ?>
						<?php } //End of for ?>
						<?php //Forward button
						if(isset($UPage)&&!empty($UPage)){
						if($UPage+1<=$PostPagination){
						
						?>
						<li class="page-item">
							<a href="Comments.php?upage=<?php echo $UPage+1; ?>&apage=1" class="page-link">&raquo;</a>
						</li>
						<?php }} ?>
					</ul>
				</nav>
			<!-- Pagination End -->
				<h2>Approved Comments</h2>
				<table class="table table-striped table-hover">
					<thead class="thead-dark">
						<tr>
							<th>No. </th>
							<th>Name</th>
							<th>Date&Time</th>
							<th>Comment</th>
							<th>Disapprove</th>
							<th>Delete</th>
							<th>Details</th>
						</tr>
					</thead>
				<?php
				
				$ConnectingDB;
				//SQL for pagination
				if(isset($_GET["apage"])){
					$APage = $_GET["apage"];
					if($APage < 1){
						$ShowPostFrom=0;
					}else{
						$ShowPostFrom = ($APage*5)-5;
					}
					$sql = "SELECT * FROM comments WHERE status='ON' ORDER BY id desc LIMIT $ShowPostFrom,5";
					$Execute = $ConnectingDB->query($sql);
					$SrNo=$APage*5-5;
				//SQL default
				}else{
					$sql="SELECT * FROM comments WHERE status='ON' ORDER BY id desc";
					$Execute = $ConnectingDB->query($sql);
					$SrNo = 0;
				}
				while($DataRows=$Execute->fetch()){
					$CommentId = $DataRows["id"];
					$DateTime = $DataRows["datetime"];
					$CommenterName = $DataRows["name"];
					$CommentContent = $DataRows["comment"];
					$CommentPostId = $DataRows["post_id"];
					$SrNo++;
				?>
					<tbody>
						<tr>
							<td><?php echo htmlentities($SrNo); ?></td>
							<td style="word-wrap: break-word;min-width: 160px;max-width: 450px;"><?php echo htmlentities($CommenterName); ?></td>
							<td><?php echo htmlentities($DateTime); ?></td>
							<td style="word-wrap: break-word;min-width: 160px;max-width: 450px;"><?php echo htmlentities($CommentContent); ?></td>
							<td><a class="btn btn-warning" href="DisapproveComment.php?id=<?php echo $CommentId; ?>">Disapprove</a></td>
							<td><a class="btn btn-danger" href="DeleteComment.php?id=<?php echo $CommentId; ?>">Delete</a></td>
							<td><a class="btn btn-primary" href="FullPost.php?id=<?php echo $CommentPostId; ?>">Live Preview</a></td>
						</tr>
					</tbody>
					<?php } // End of while loop ?>
				</table>
				<!-- Pagination -->
				<nav>
					<ul class="pagination pagination-lg">
						<?php
							$ConnectingDB;
							$sql = "SELECT COUNT(*) FROM comments WHERE status='ON'";
							$stmt=$ConnectingDB->query($sql);
							$RowPagination = $stmt->fetch();
							$TotalPosts = array_shift($RowPagination);
							$PostPagination = ceil($TotalPosts/5);
						?>
						<?php //Backward button
						if(isset($APage)&&!empty($APage)){
						if($APage>1){
						
						?>
						<li class="page-item">
							<a href="Comments.php?apage=<?php echo $APage-1; ?>&upage=1" class="page-link">&laquo;</a>
						</li>
						<?php }} ?>
						<?php
						for($i=1;$i<=$PostPagination;$i++){
							if(isset($APage)&&!empty($APage)){
							if($i==$APage){
						?>
						<li class="page-item active">
							<a href="Comments.php?apage=<?php echo $i; ?>&upage=1" class="page-link"><?php echo $i; ?></a>
						</li>
							<?php }else{ ?>
						<li class="page-item">
							<a href="Comments.php?apage=<?php echo $i; ?>&upage=1" class="page-link"><?php echo $i; ?></a>
						</li>
							<?php }} ?>
						<?php } //End of for ?>
						<?php //Forward button
						if(isset($APage)&&!empty($APage)){
						if($APage+1<=$PostPagination){
						
						?>
						<li class="page-item">
							<a href="Comments.php?apage=<?php echo $APage+1; ?>&upage=1" class="page-link">&raquo;</a>
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