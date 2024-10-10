<?php
include 'koneksi.php';

if (isset($_GET['id_buku'])) {
    
    $id_buku = $_GET['id_buku'];

    $id_buku = mysqli_real_escape_string($koneksi, $id_buku);

    $sql = "DELETE FROM buku WHERE id_buku = '$id_buku'";
    
    if (mysqli_query($koneksi, $sql)) {
        header("Location: daftarbuku.php"); 
        exit;
    } else {
        echo "Terjadi kesalahan saat menghapus data: " . mysqli_error($koneksi);
    }
}
?>
