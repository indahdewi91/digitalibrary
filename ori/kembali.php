<?php
include "koneksi.php";
$id_buku = $_GET['id_buku'];

// Memperbarui status buku menjadi 'tersedia'
$sql = "UPDATE buku SET status = 'tersedia' WHERE id_buku = $id_buku";
$query = mysqli_query($koneksi, $sql);

// Menampilkan notifikasi dan melakukan redirect setelah 3 detik
if ($query) {
    echo "<script>
            alert('Buku berhasil dikembalikan!');
            setTimeout(function() {
                window.location.href = 'buku.php';
            }); 
          </script>";
} else {
    echo "<script>
            alert('Gagal mengembalikan buku!');
            setTimeout(function() {
                window.location.href = 'buku.php';
            } );
          </script>";
}
?>
