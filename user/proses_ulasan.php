<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_buku = $_POST['id_buku'];
    $ulasan = $_POST['ulasan'];
    $rating = $_POST['rating'];
    $id_user = $_SESSION['id_user']; 

    $sql = "INSERT INTO ulasan_buku (id_user, id_buku, ulasan, rating) VALUES ('$id_user', '$id_buku', '$ulasan', '$rating')";
    if (mysqli_query($koneksi, $sql)) {
        header("Location: cobakoleksi.php?success=1");
        exit;
    } else {
        echo "Terjadi kesalahan: " . mysqli_error($koneksi);
    }
}
?>



