<?php
//koneksi ke database mysql, silahkan dirubah dengan koneksi sendiri
$koneksi = mysqli_connect("localhost","root","","db_sicampus");

//cek jika koneksi ke mysql gagal, maka akan tampil pesan berikut
if(mysqli_connect_errno()){
	echo "Gagal melakukan koneksi ke MySQL: " . mysqli_connect_error();
}

?>