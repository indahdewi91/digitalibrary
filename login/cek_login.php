<?php 
// mengaktifkan session pada php
session_start();

//cek login sudah punya username dan pass atau belum
 
// menghubungkan php dengan koneksi database
$koneksi = new mysqli("localhost", "root", "", "perpustakaan");
 
// menangkap data yang dikirim dari form login
$username = $_POST['username'];
$password = md5($_POST['password']);
  // Simpan id_user dalam session

 
// menyeleksi data user dengan username dan password yang sesuai
$login = mysqli_query($koneksi,"SELECT * from user where username='$username' and password='$password'");
// menghitung jumlah data yang ditemukan
$cek = mysqli_num_rows($login);
 
// cek apakah username dan password di temukan pada database
if($cek > 0){
 
	$data = mysqli_fetch_assoc($login);
 
	// cek jika user login sebagai admin
	if($data['role']=="admin"){
 
		// buat session login dan username
		$_SESSION['username'] = $username;
		$_SESSION['role'] = "admin";
		$_SESSION['id_user'] = $id_user;
		// alihkan ke halaman dashboard admin
		header("location:../admin/index.php");
 
	// cek jika user login sebagai pengurus
	}else if($data['role']=="user"){
		// buat session login dan username
		$_SESSION['username'] = $username;
		$_SESSION['role'] = "user";
		$_SESSION['id_user'] = $id_user;
		header("location:../user/index.php");
 
	}else{
 
		// alihkan ke halaman login kembali
		header("location:../cobalog.php?pesan=gagal");
	}	
}else{
	header("location:../cobalog.php?pesan=gagal");
}
 
?>