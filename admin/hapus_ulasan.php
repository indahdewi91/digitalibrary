<?php
include 'koneksi.php';

if (isset($_POST['id_ulasan'])) {
    $id_ulasan = $_POST['id_ulasan'];

    // Query untuk menghapus ulasan berdasarkan id_ulasan
    $query = "DELETE FROM ulasan_buku WHERE id_ulasan = '$id_ulasan'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        echo "<script>
            alert('Ulasan berhasil dihapus.');
            window.location.href = 'ulasan_admin.php';
        </script>";
    } else {
        echo "<script>
            alert('Gagal menghapus ulasan: " . mysqli_error($koneksi) . "');
            window.location.href = 'ulasan_admin.php';
        </script>";
    }
} else {
    echo "<script>
        alert('ID ulasan tidak ditemukan.');
        window.location.href = 'ulasan_admin.php';
    </script>";
}
?>
