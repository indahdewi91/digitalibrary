<?php
session_start();


//untuk proses daftarnya

// koneksi database
$koneksi = new mysqli ("localhost", "root", "", "perpustakaan");
 
// menangkap data yang di kirim dari form
$id_user = $_POST['id_user'];
$nama_lengkap = $_POST['nama_lengkap'];
$email = $_POST['email'];
$alamat = $_POST['alamat'];
$username = $_POST['username'];
$password = md5($_POST['password']);
$role = $_POST['role'];

 
// menginput data ke database
mysqli_query($koneksi,"INSERT into user(id_user,nama_lengkap,email,alamat,username,password,role) 
									values('$id_user','$nama_lengkap','$email','$alamat','$username','$password','$role')");
 
// mengalihkan halaman kembali ke index.php
header("location:../login.php");
?>