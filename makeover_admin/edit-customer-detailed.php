<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid'] == 0)) {
	header('location:logout.php');
} else {

	if (isset($_POST['submit'])) {
		$email = $_POST['email'];
		$password = $_POST['password'];

		$eid = $_GET['editid'];

		$query = mysqli_query($con, "UPDATE users SET  email='$email', password='$password' where user_id='$eid'");
		if ($query) {
			$msg = "Customer Detail has been Updated.";
		} else {
			$msg = "Something Went Wrong. Please try again";
		}
	}

	?>
	<!DOCTYPE HTML>
	<html>

	<head>
		<title>Customer-list</title>

		<script
			type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
		<!-- Bootstrap Core CSS -->
		<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
		<!-- Custom CSS -->
		<link href="css/style.css" rel='stylesheet' type='text/css' />
		<!-- font CSS -->
		<!-- font-awesome icons -->
		<link href="css/font-awesome.css" rel="stylesheet">
		<!-- //font-awesome icons -->
		<!-- js-->
		<script src="js/jquery-1.11.1.min.js"></script>
		<script src="js/modernizr.custom.js"></script>
		<!--webfonts-->
		<link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,300,300italic,400italic,700,700italic'
			rel='stylesheet' type='text/css'>
		<!--//webfonts-->
		<!--animate-->
		<link href="css/animate.css" rel="stylesheet" type="text/css" media="all">
		<script src="js/wow.min.js"></script>
		<script>
			new WOW().init();
		</script>
		<!--//end-animate-->
		<!-- Metis Menu -->
		<script src="js/metisMenu.min.js"></script>
		<script src="js/custom.js"></script>
		<link href="css/custom.css" rel="stylesheet">
		<!--//Metis Menu -->
	</head>

	<body class="cbp-spmenu-push">
		<div class="main-content">
			<!--left-fixed -navigation-->
			<?php include_once('includes/sidebar.php'); ?>
			<!--left-fixed -navigation-->
			<!-- header-starts -->
			<?php include_once('includes/header.php'); ?>
			<!-- //header-ends -->
			<!-- main content start-->
			<div id="page-wrapper">
				<div class="main-page">
					<div class="forms">
						<h3 class="title1">Update Services</h3>
						<div class="form-grids row widget-shadow" data-example-id="basic-forms">
							<div class="form-title">
								<h4>Update Parlour Services:</h4>
							</div>
							<div class="form-body">
								<form method="post">
									<p style="font-size:16px; color:red" align="center">
										<?php if ($msg) {
											echo $msg;
										} ?>
									</p>
									<?php
									$cid = $_GET['editid'];
									$ret = mysqli_query($con, "select * from  users where user_id='$cid'");
									$cnt = 1;
									while ($row = mysqli_fetch_array($ret)) {

										?>



										<div class="form-group"> <label for="exampleInputPassword1">Email</label> <input
												type="text" id="email" name="email" class="form-control"
												value="<?php echo $row['email']; ?>" required="true"> </div>
										<div class="form-group"> <label for="exampleInputPassword1">Password</label> <input
												type="text" id="password" name="password" class="form-control"
												value="<?php echo $row['password']; ?>" required="true"> </div>

									<?php } ?>


									<button type="submit" name="submit" class="btn btn-default">Update</button>
								</form>
							</div>

						</div>


					</div>
				</div>
				<?php include_once('includes/footer.php'); ?>
			</div>
			<!-- Classie -->
			<script src="js/classie.js"></script>
			<script>
				var menuLeft = document.getElementById('cbp-spmenu-s1'),
					showLeftPush = document.getElementById('showLeftPush'),
					body = document.body;

				showLeftPush.onclick = function () {
					classie.toggle(this, 'active');
					classie.toggle(body, 'cbp-spmenu-push-toright');
					classie.toggle(menuLeft, 'cbp-spmenu-open');
					disableOther('showLeftPush');
				};

				function disableOther(button) {
					if (button !== 'showLeftPush') {
						classie.toggle(showLeftPush, 'disabled');
					}
				}
			</script>
			<!--scrolling js-->
			<script src="js/jquery.nicescroll.js"></script>
			<script src="js/scripts.js"></script>
			<!--//scrolling js-->
			<!-- Bootstrap Core JavaScript -->
			<script src="js/bootstrap.js"> </script>
	</body>

	</html>
<?php } ?>