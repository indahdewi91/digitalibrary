<?php
include 'koneksi.php';

$id_buku = $_GET['id_buku'];
$result = mysqli_query($koneksi, "SELECT * FROM buku WHERE id_buku = '$id_buku'");
$data = mysqli_fetch_assoc($result);

echo json_encode($data);
?>
