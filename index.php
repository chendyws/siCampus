<?php
//ob_start() untuk hilangkan warning header location biar tidak error
ob_start();
session_start();

if (!isset($_SESSION["login"])){
	header("Location: login.php");
	exit;
}

$admin = $_SESSION["admin"];

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
	<!-- HEADER -->
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<div class="container">
			<a class="navbar-brand" href="index.php">ONLINE SISTEM</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav mr-auto">
					<?php if ($admin == 0) { ?>
						<li class="nav-item">
							<a class="nav-link" href="index.php?fungsi=getDataByUser">Data Saya</a>
						</li>
					<?php } else { ?>
						<li class="nav-item">
							<a class="nav-link" href="index.php?fungsi=read">Calon Mahasiswa</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="index.php?fungsi=create">Daftar Baru</a>
						</li>
					<?php } ?>
					<li class="nav-item">
						<a class="nav-link" href="index.php?fungsi=logout">Keluar</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>
<?php
include('koneksi.php');

// --- Program Utama ---
if (isset($_GET['fungsi'])){
	switch($_GET['fungsi']){
		case "create":
			if ($admin == 0) {
				header('location: index.php');
			}
			create($koneksi);
			break;
		case "create_success":
			create_success();
			break;
		case "read":
			if ($admin == 0) {
				header('location: index.php');
			}
			read($koneksi);
			break;
		case "getDataByUser":
			$pengguna = (isset($_GET['pengguna'])) ? $_GET['pengguna'] : $_SESSION['username'];
			getDataByUser($koneksi, $pengguna);
			break;
		case "update":
			if ($admin == 0) {
				header('location: index.php');
			}
			update($koneksi);
			break;
		case "update_success":
			update_success();
			break;
		case "delete":
			if ($admin == 0) {
				header('location: index.php');
			}
			delete($koneksi);
			break;
		case "logout":
			logout();
			break;
		default:
			header('location: index.php');
	}
} else {
	mainpage();
}


// --- Fungsi Tampilan halaman awal ---
function mainpage(){
	echo'
	<div class="container" style="margin-top:20px">
	<h3>Pendaftaran Calon Mahasiswa Baru </h3>

	<hr>

	<p> Selamat Datang di<b> SiCampus</b>  </p>

	</div>';
}

// ---Fungsi tambah data (Create)
function create($koneksi){
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

	if(isset($_POST['btn_simpan'])){
		if(strlen($username) > 5) {
			$pesan = "Username tidak boleh lebih dari 5 karakter!";
		} else {
			$sql = mysqli_query($koneksi, "select * from pengguna where username='$username'");
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
						if($simpan && isset($_GET['fungsi'])){
							$sql2 = "INSERT INTO pengguna VALUES ('".$username."','".md5($password)."', 0)";
							$simpan2 = mysqli_query($koneksi, $sql2);
							if($simpan2){
								if($_GET['fungsi'] == 'create'){
									header('location: index.php?fungsi=create_success');
								}
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
<div class="container" style="margin-top:20px">
	<h2>Tambah Data Calon Mahasiswa</h2>
	<form action="index.php?fungsi=create" method="post">
		<?php echo '<p class="text-danger">'.$pesan.'</p>'; ?>
		<div class="form-group row">
			<label class="col-sm-2 col-form-label">Nama</label>
			<div class="col-sm-10">
				<input type="text" name="nama_siswa" value="<?=$nama_siswa;?>" class="form-control" size="4" required>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-2 col-form-label">Alamat</label>
			<div class="col-sm-10">
				<textarea name="alamat" class="form-control" required><?=$alamat;?></textarea>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-2 col-form-label">Jenis Kelamin</label>
			<div class="col-sm-10">
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
			<label class="col-sm-2 col-form-label">Agama</label>
			<div class="col-sm-10">
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
			<label class="col-sm-2 col-form-label">Sekolah Asal</label>
			<div class="col-sm-10">
				<input type="text" name="sekolah_asal" value="<?=$sekolah_asal;?>" class="form-control" required>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-2 col-form-label">Username</label>
			<div class="col-sm-10">
				<input type="text" name="username" value="<?=$username;?>" class="form-control" size="4" required>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-2 col-form-label">Password</label>
			<div class="col-sm-10">
				<input type="password" name="password" value="<?=$password;?>" class="form-control" size="4" required>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-2 col-form-label">Ulangi Password</label>
			<div class="col-sm-10">
				<input type="password" name="password2" value="<?=$password2;?>" class="form-control" size="4" required>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-2 col-form-label">&nbsp;</label>
			<div class="col-sm-10">
				<input type="submit" name="btn_simpan" class="btn btn-primary" value="Simpan">
				<input type="reset" name="btn_reset" class="btn btn-info" value="Reset">
				<a href="index.php" class="btn btn-success" role="button">Kembali</a>
			</div>
		</div>
	</form>
</div>

<?php
}

// ---Fungsi Tampilan halaman berhasil tambah data
function create_success(){
	echo'
	<div class="container" style="margin-top:20px">
	<h3>Data Calon Mahasiswa</h3>
	<hr>

	<p> Pendaftaran Berhasil </p>

	</div>';
}

// --- Fungsi Baca Data (Read)
function read($koneksi){
// --- Fungsi Baca Data (Read)
echo'
<div class="container" style="margin-top:20px">
<h2>Pendaftaran Mahasiswa Baru</h2>
<hr>

<table class="table table-striped table-hover table-sm table-bordered">
	<thead>
		<tr>
			<th>No</th>
			<th>Nama Mahasiswa</th>
			<th>Alamat</th>
			<th>Jenis Kelamin</th>
			<th>Agama</th>
			<th>Sekolah Asal</th>
			<th>Username</th>
			<th>Status</th>
			<th>Tindakan</th>
		</tr>
	</thead>
	<tbody>';

	//query ke database SELECT tabel mahasiswa urut berdasarkan id yang paling besar
	$sql = "SELECT * FROM siswa";
	$query = mysqli_query($koneksi, $sql);

		//jika query diatas menghasilkan nilai > 0 maka menjalankan script di bawah if...
		if(mysqli_num_rows($query) > 0){
			$no=1;
			//melakukan perulangan while dengan dari dari query $sql
			while($data = mysqli_fetch_assoc($query)){
				//menampilkan data perulangan
				echo '
				<tr>
					<td>'.$no.'</td>
					<td>'.$data['nama_siswa'].'</td>
					<td>'.$data['alamat'].'</td>
					<td>'.$data['jenis_kelamin'].'</td>
					<td>'.$data['agama'].'</td>
					<td>'.$data['sekolah_asal'].'</td>
					<td>'.$data['username'].'</td>
					<td>'.$data['status'].'</td>
					<td>
						<a href="index.php?fungsi=update&id_siswa='.$data['id_siswa'].'" class="badge badge-warning">Ubah</a>
						<a href="index.php?fungsi=delete&id_siswa='.$data['id_siswa'].'&username='.$data['username'].'" class="badge badge-danger" onclick="return confirm(\'Yakin ingin menghapus data ini?\')">Hapus</a>
						<a href="index.php?fungsi=getDataByUser&pengguna='.$data['username'].'" class="badge badge-success">Lihat</a>
					</td>
				</tr>';
				$no++;
			}
		//jika query menghasilkan nilai 0
		} else {
			echo '
			<tr>
				<td colspan="9" align="center">Tidak ada data.</td>
			</tr>
			';
		}
	echo'	
	</tbody>

</table>
</div>';

}

// --- Fungsi Ubah Data (Update)
function update($koneksi){
	$pesan = '';

	$id_siswa = (isset($_POST['id_siswa'])) ? $_POST['id_siswa'] : '' ;
	$nama_siswa = (isset($_POST['nama_siswa'])) ? $_POST['nama_siswa'] : '' ;
	$alamat = (isset($_POST['alamat'])) ? $_POST['alamat'] : '';
	$jenis_kelamin = (isset($_POST['jenis_kelamin'])) ? $_POST['jenis_kelamin'] : '';
	$agama = (isset($_POST['agama'])) ? $_POST['agama'] : '';
	$sekolah_asal = (isset($_POST['sekolah_asal'])) ? $_POST['sekolah_asal'] : '';
	$status = (isset($_POST['status'])) ? $_POST['status'] : '';

	if (isset($_POST['btn_simpan'])){


		if(!empty($nama_siswa) && !empty($alamat) && !empty($jenis_kelamin) && !empty($agama) && !empty($sekolah_asal)){
			$sql= "UPDATE siswa SET nama_siswa='$nama_siswa', alamat='$alamat', jenis_kelamin='$jenis_kelamin', agama='$agama', sekolah_asal='$sekolah_asal', status='$status' WHERE id_siswa='$id_siswa'";
			//echo $sql;
			$update = mysqli_query($koneksi, $sql);
			if($update && isset($_GET['fungsi'])){
				if($_GET['fungsi'] == 'update'){
					header('location: index.php?fungsi=update_success');
				}
			}
		} else {
			$pesan = "Tidak dapat menyimpan, data belum lengkap!";
		}
	
	} else {
		$id_siswa = $_GET['id_siswa'];


		//ambil data mahasiswa untuk ditampilkan ke dalam form update
		$sql_mahasiswa ="SELECT * FROM siswa WHERE id_siswa=" . $id_siswa;
		$query_mahasiswa = mysqli_query($koneksi, $sql_mahasiswa);
		$data_mahasiswa = mysqli_fetch_assoc($query_mahasiswa);
	}

?>

<div class="container" style="margin-top:20px">
	<h2>Update Data Mahasiswa</h2>
	<?php echo '<p class="text-danger">'.$pesan.'</p>'; ?>
	<form action="index.php?fungsi=update" method="post">
		<div class="form-group row">
			<label class="col-sm-2 col-form-label">Nama</label>
			<div class="col-sm-10">
				<input type="text" name="nama_siswa" class="form-control" size="4" value="<?php echo $data_mahasiswa['nama_siswa']; ?>" required>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-2 col-form-label">Alamat</label>
			<div class="col-sm-10">
				<textarea name="alamat" class="form-control" required><?php echo $data_mahasiswa['alamat']; ?></textarea>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-2 col-form-label">Jenis Kelamin</label>
			<div class="col-sm-10">
				<div class="form-check">
				<input type="radio" class="form-check-input" name="jenis_kelamin" value="L" <?php if ($data_mahasiswa['jenis_kelamin'] == 'L') { echo 'checked';}   ?> required>
				<label class="form-check-label">Laki-laki</label>
				</div>
				<div class="form-check">
				<input type="radio" class="form-check-input" name="jenis_kelamin" value="P" <?php if ($data_mahasiswa['jenis_kelamin'] == 'P') { echo 'checked';}   ?> required>
				<label class="form-check-label">Perempuan</label>
				</div>	
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-2 col-form-label">Agama</label>
			<div class="col-sm-10">
				<select name="agama" class="form-control" required>
					<option value="">Pilih salah satu</option>
					<option value="Islam" <?php if($data_mahasiswa['agama'] == 'Islam' ) {echo 'selected'; }  ?>>Islam</option>
					<option value="Kristen Protestan" <?php if($data_mahasiswa['agama'] == 'Kristen Protestan' ) {echo 'selected'; }  ?>>Kristen Protestan</option>
					<option value="Katolik" <?php if($data_mahasiswa['agama'] == 'Katolik' ) {echo 'selected'; }  ?>>Katolik</option>
					<option value="Hindu" <?php if($data_mahasiswa['agama'] == 'Hindu' ) {echo 'selected'; }  ?>>Hindu</option>
					<option value="Budha" <?php if($data_mahasiswa['agama'] == 'Budha' ) {echo 'selected'; }  ?>>Budha</option>
					<option value="Kepercayaan lainnya" <?php if($data_mahasiswa['agama'] == 'Kepercayaan lainnya' ) {echo 'selected'; }  ?>>Kepercayaan lainnya</option>
				</select>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-2 col-form-label">Sekolah Asal</label>
			<div class="col-sm-10">
				<input type="text" name="sekolah_asal" class="form-control" value="<?php echo $data_mahasiswa['sekolah_asal']; ?>" required>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-2 col-form-label">Status</label>
			<div class="col-sm-10">
				<select name="status" class="form-control" required>
					<option value="">Pilih salah satu</option>
					<option value="Diterima" <?php if($data_mahasiswa['status'] == 'Diterima' ) {echo 'selected'; }  ?>>Diterima</option>
					<option value="Cadangan" <?php if($data_mahasiswa['status'] == 'Cadangan' ) {echo 'selected'; }  ?>>Cadangan</option>
					<option value="Tidak Diterima" <?php if($data_mahasiswa['status'] == 'Tidak Diterima' ) {echo 'selected'; }  ?>>Tidak Diterima</option>
				</select>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-2 col-form-label">&nbsp;</label>
			<div class="col-sm-10">
				<input type="hidden" name="id_siswa" value="<?php echo $id_siswa; ?>">
				<input type="submit" name="btn_simpan" class="btn btn-primary" value="Simpan">
				<a href="index.php" class="btn btn-success" role="button">Kembali</a>
			</div>
		</div>
	</form>
</div>

<?php 
	}
?>

<?php

// Fungsi get data siswa by ID
function getDataByUser($koneksi, $pengguna) {
	$username = $pengguna;

	// ambil data mahasiswa untuk ditampilkan ke dalam form update
	$sql ="SELECT * FROM siswa WHERE username='$username'";
	$query = mysqli_query($koneksi, $sql);
	$data = mysqli_fetch_assoc($query);
?>
<div class="container" style="margin-top:20px">
	<h2>Data Saya</h2>
	<hr>

	<table class="table table-striped table-hover table-sm">
		<tr>
			<td>Nama</td>
			<td><?=$data['nama_siswa'];?></td>
		</tr>
		<tr>
			<td>Alamat</td>
			<td><?=$data['alamat'];?></td>
		</tr>
		<tr>
			<td>Jenis Kelamin</td>
			<td><?=$data['jenis_kelamin'];?></td>
		</tr>
		<tr>
			<td>Agama</td>
			<td><?=$data['agama'];?></td>
		</tr>
		<tr>
			<td>Sekolah Asal</td>
			<td><?=$data['sekolah_asal'];?></td>
		</tr>
		<tr>
			<td>Username</td>
			<td><?=$data['username'];?></td>
		</tr>
		<tr>
			<td>Status</td>
			<td><?=$data['status'];?></td>
		</tr>
	</table>
<div>
<?php
	}
?>

<?php

//---Fungsi update data mahasiswa berhasil tambah data
function update_success(){
	echo'
	<div class="container" style="margin-top:20px">
		<h3>Data Calon Mahasiswa</h3>
		<hr>
		<p>Update Data Mahasiswa Berhasil</p>
	</div>';
}

// --- Fungsi Delete
function delete ($koneksi){
	if(isset($_GET['id_siswa']) && isset($_GET['username']) && isset($_GET['fungsi'])){
		$id_siswa = $_GET['id_siswa'];
		$sql_hapus = "DELETE FROM siswa WHERE id_siswa=".$id_siswa;
		$hapus =  mysqli_query($koneksi, $sql_hapus);

		if($hapus) {
			$username = $_GET['username'];
			$sql = "DELETE FROM pengguna WHERE username = '".$username."'";
			$hapus_user = mysqli_query($koneksi, $sql);
			if($_GET['fungsi'] == 'delete' && $hapus_user){
				header('location: index.php?fungsi=delete_success');
			}
		}
	}
}

// --- fungsi delete data mahasiswa berhasil tambah data
function delete_success(){
	echo'
	<div class="container" style="margin-top:20px">
	<h3>Data Calon Mahasiswa</h3>
	<hr>
	<p> Delete Data Mahasiswa Berhasil </p>
	</div>';
}

// -- fungsi logout
function logout(){
	require ('logout.php');
}

?>

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
