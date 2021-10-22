<?php

session_start();

if (isset($_SESSION["login"])){
	header("Location: index.php");
	exit;
}
require 'koneksi.php';
$pesan = '';

$nama_siswa = (isset($_POST['nama_siswa'])) ? $_POST['nama_siswa'] : '' ;
$alamat = (isset($_POST['alamat'])) ? $_POST['alamat'] : '';
$jenis_kelamin = (isset($_POST['jenis_kelamin'])) ? $_POST['jenis_kelamin'] : '';
$agama = (isset($_POST['agama'])) ? $_POST['agama'] : '';
$sekolah_asal = (isset($_POST['sekolah_asal'])) ? $_POST['sekolah_asal'] : '';
$username = (isset($_POST['username'])) ? $_POST['username'] : '';
$password = (isset($_POST['password'])) ? $_POST['password'] : '';
$password2 = (isset($_POST['password2'])) ? $_POST['password2'] : '';
$status = '';

if(isset($_POST['register'])){
	if(strlen($username) > 5) {
		$pesan = "Username tidak boleh lebih dari 5 karakter!";
	} else {
		$sql = mysqli_query($koneksi, "select * from user where username='$username'");
		$cek = mysqli_num_rows($sql);
		if($cek > 0){
			$pesan = "Username telah digunakan!";
		} else {
			if($password != $password2) {
				$pesan  = "Password tidak sama!";
			} else {
				if(!empty($nama_siswa) && !empty($alamat) && !empty($jenis_kelamin) && !empty($agama) && !empty($sekolah_asal) && !empty($username) && !empty($password)){
					// Insert Data Siswa
					$sql= "INSERT INTO siswa (nama_siswa,alamat,jenis_kelamin,agama,sekolah_asal, status, username)
					VALUES('".$nama_siswa."','".$alamat."','".$jenis_kelamin."','".$agama."','".$sekolah_asal."', '".$status."','".$username."')";

					// echo $sql;

					$simpan = mysqli_query($koneksi, $sql);
					if($simpan){
						$sql2 = "INSERT INTO user VALUES ('".$username."','".md5($password)."', 0)";
						$simpan2 = mysqli_query($koneksi, $sql2);
						if($simpan2){
							header('location: login.php');
						}
					}
				} else {
					$pesan = "Tidak dapat menyimpan, data belum lengkap!";
				}
			}
		}
	}		
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
		<div class="col-md-8">
			<div class="card mb-5">
				<div class="card-header bg-transparent mb-0"><h5 class="text-center">Please <span class="font-weight-bold text-primary">Register</span></h5> </div>
				<div class="card-body">
					<form action="" method="post">
						<?php echo '<p class="text-danger">'.$pesan.'</p>'; ?>
						<div class="form-group row">
							<label class="col-sm-3 col-form-label">Nama</label>
							<div class="col-sm-9">
								<input type="text" name="nama_siswa" value="<?=$nama_siswa;?>" class="form-control" size="4" required>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-3 col-form-label">Alamat</label>
							<div class="col-sm-9">
								<textarea name="alamat" class="form-control" required><?=$alamat;?></textarea>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-3 col-form-label">Jenis Kelamin</label>
							<div class="col-sm-9">
								<div class="form-check">
									<input type="radio" class="form-check-input" name="jenis_kelamin" value="L" required <?php if ($jenis_kelamin == 'L') { echo 'checked';}   ?>>
									<label class="form-check-label">Laki-laki</label>
								</div>
								<div class="form-check">
									<input type="radio" class="form-check-input" name="jenis_kelamin" value="P" required <?php if ($jenis_kelamin == 'P') { echo 'checked';}   ?>>
									<label class="form-check-label">Perempuan</label>
								</div>	
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-3 col-form-label">Agama</label>
							<div class="col-sm-9">
								<select name="agama" class="form-control" required>
									<option value="">Pilih salah satu</option>
									<option value="Islam" <?php if($agama == 'Islam' ) {echo 'selected'; }  ?>>Islam</option>
									<option value="Kristen Protestan" <?php if($agama == 'Kristen Protestan' ) {echo 'selected'; }  ?>>Kristen Protestan</option>
									<option value="Katolik" <?php if($agama == 'Katolik' ) {echo 'selected'; }  ?>>Katolik</option>
									<option value="Hindu" <?php if($agama == 'Hindu' ) {echo 'selected'; }  ?>>Hindu</option>
									<option value="Budha" <?php if($agama == 'Budha' ) {echo 'selected'; }  ?>>Budha</option>
									<option value="Kepercayaan lainnya" <?php if($agama == 'Kepercayaan lainnya' ) {echo 'selected'; }  ?>>Kepercayaan lainnya</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-3 col-form-label">Sekolah Asal</label>
							<div class="col-sm-9">
								<input type="text" name="sekolah_asal" value="<?=$sekolah_asal;?>" class="form-control" required>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-3 col-form-label">Username</label>
							<div class="col-sm-9">
								<input type="text" name="username" value="<?=$username;?>" class="form-control" size="4" required>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-3 col-form-label">Password</label>
							<div class="col-sm-9">
								<input type="password" name="password" value="<?=$password;?>" class="form-control" size="4" required>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-3 col-form-label">Ulangi Password</label>
							<div class="col-sm-9">
								<input type="password" name="password2" value="<?=$password2;?>" class="form-control" size="4" required>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-12">
								<input type="submit" name="register" value="Register" class="btn btn-primary btn-block">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-12 text-center">
								<a href="./login.php">Kembali</a>
							</div>
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