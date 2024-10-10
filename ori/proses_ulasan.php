<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_buku = $_POST['id_buku'];
    $ulasan = $_POST['ulasan'];
    $rating = $_POST['rating'];
    $username = $_POST['username'];

    
    $checkUser = mysqli_query($koneksi, "SELECT id_user FROM user WHERE username = '$username'");
    if (mysqli_num_rows($checkUser) > 0) {
        $userData = mysqli_fetch_assoc($checkUser);
        $id_user = $userData['id_user'];

        
        $sql = "INSERT INTO ulasan_buku (id_user, id_buku, ulasan, rating) VALUES ('$id_user', '$id_buku', '$ulasan', '$rating')";
        if (mysqli_query($koneksi, $sql)) {
            
            header("Location: buku.php?success=1");
            exit;
        } else {
            echo "Terjadi kesalahan: " . mysqli_error($koneksi);
        }
    } else {
        echo "Username tidak ditemukan.";
    }
}
?>



