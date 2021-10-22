<?php

session_start();

if (isset($_SESSION["login"])){
	header("Location: index.php");
	exit;
}
require 'koneksi.php';
$error=false;
if (isset($_POST["login"])){

	$username = $_POST["username"];
	$password = Md5($_POST["password"]);

	//menyeleksi data admin dengan username dan password yang sesuai
	$sql = mysqli_query($koneksi, "select * from user where username='$username' and password='$password'");
	$cek = mysqli_num_rows($sql);
	if($cek > 0){
		$data = mysqli_fetch_assoc($sql);
		$_SESSION["login"] = true;
		$_SESSION["username"] = $data["username"];
		$_SESSION["admin"] = $data["admin"];
		header("Location: index.php");
		exit;
	}
	$error = true;
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Pendaftaran</title>

	
	<!-- Bootstrap CSS -->
	<link rel="stylesheet"
	href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
	integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
	crossorigin="anonymous">
</head>
<body>
<div class="container">
	<div class="row justify-content-center mt-5">
		<div class="col-md-4">
			<div class="card">
				<div class="card-header bg-transparent mb-0"><h5 class="text-center">Please <span class="font-weight-bold text-primary">LOGIN</span></h5> </div>
				<div class="card-body">
					<?php 
					if ($error==true)
						echo"<div class='alert alert-warning'> Username atau Password Salah! </div>";
					?>
					<form action="" method="post">
						<div class="form-group row">
							<input type="text" name="username" class="form-control" placeholder="username">
						</div>
						<div class="form-group row">
							<input type="password" name="password" class="form-control" placeholder="password">
						</div>
						<div class="form-group row">
							<div class="col-md-6 form-group custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" id="customControlAutosizing">
								<label class="custom-control-label" for="customControlAutosizing">Remember me</label>
							</div>
							<div class="col-md-6 form-group text-right">
								<a href="./register.php">Daftar</a>
							</div>
						</div>
						<div class="form-group row">
							<input type="submit" name="login" value="login" class="btn btn-primary btn-block">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
	integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
	crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
	integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
	crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
	integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
	crossorigin="anonymous"></script>
</body>
</html>