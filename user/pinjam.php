<?php
    include "koneksi.php"; // Koneksi ke database
    session_start(); // Memulai session
    
    // Cek apakah pengguna sudah login
    if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'user') {
        header("Location: ../login.php"); // Arahkan ke halaman login jika tidak memiliki akses
        exit();
    }
    
    // Menampilkan pesan sukses jika ada
    if (isset($_SESSION['success_message'])) {
        echo "<div class='alert alert-success'>" . $_SESSION['success_message'] . "</div>";
        unset($_SESSION['success_message']);
    }
?>

<!DOCTYPE html>
<html lang="en"> 
<head>
    <title>Perpustakaan Digital</title>
    
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta name="description" content="Portal - Bootstrap 5 Admin Dashboard Template For Developers">
    <meta name="author" content="Xiaoying Riley at 3rd Wave Media">    
    <link rel="shortcut icon" href="img\buk.svg"> 
    
    <!-- FontAwesome JS-->
    <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>
    
    <!-- App CSS -->  
    <link id="theme-style" rel="stylesheet" href="assets/css/portal.css">

    <style>

        
        .settings-form {
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        background-color: #fff;
        margin-bottom: 20px;
    }

    .book-image {
        max-width: 200px;
        height: auto;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .ulasan-container {
        padding: 20px;
        border-radius: 8px;
        background-color: #f8f9fa; /* Warna latar belakang untuk ulasan */
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .ulasan-card {
        border: none;
        border-radius: 8px;
        background-color: #fff;
        margin-bottom: 15px;
        padding: 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .ulasan-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .rating-badge {
        background-color: #15a362;
        color: white;
        padding: 5px 10px;
        border-radius: 12px;
        font-weight: bold;
    }

    .ulasan-text {
        color: #495057;
        font-style: italic;
    }

    .alert {
        margin-top: 10px;
        text-align: center;
    }

    h2, h3 {
        color: #333;
    }
    .book-info {
        background-color: #f8f9fa; /* Latar belakang yang lembut */
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px; /* Jarak antara informasi buku dengan tombol pinjam */
    }

    .book-info p {
        margin: 5px 0; /* Jarak antar paragraf */
        color: #333; /* Warna teks */
    }

    .book-code {
        display: flex;
        align-items: center; /* Vertikal center */
        justify-content: space-between; /* Memisahkan label dan input */
    }

    .book-code .form-label {
        margin-bottom: 0; /* Menghapus margin bawah label */
    }
    
    </style>

</head> 

<body class="app">   	
    <header class="app-header fixed-top">	   	            
        <div class="app-header-inner">  
	        <div class="container-fluid py-2">
		        <div class="app-header-content"> 
		            <div class="row justify-content-between align-items-center">
			        
				    <div class="col-auto">
					    <a id="sidepanel-toggler" class="sidepanel-toggler d-inline-block d-xl-none" href="#">
						    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" role="img"><title>Menu</title><path stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2" d="M4 7h22M4 15h22M4 23h22"></path></svg>
					    </a>
				    </div><!--//col-->
		            <div class="search-mobile-trigger d-sm-none col">
			            <svg class="svg-inline--fa fa-magnifying-glass search-mobile-trigger-icon" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="magnifying-glass" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M500.3 443.7l-119.7-119.7c27.22-40.41 40.65-90.9 33.46-144.7C401.8 87.79 326.8 13.32 235.2 1.723C99.01-15.51-15.51 99.01 1.724 235.2c11.6 91.64 86.08 166.7 177.6 178.9c53.8 7.189 104.3-6.236 144.7-33.46l119.7 119.7c15.62 15.62 40.95 15.62 56.57 0C515.9 484.7 515.9 459.3 500.3 443.7zM79.1 208c0-70.58 57.42-128 128-128s128 57.42 128 128c0 70.58-57.42 128-128 128S79.1 278.6 79.1 208z"></path></svg><!-- <i class="search-mobile-trigger-icon fas fa-search"></i> Font Awesome fontawesome.com -->
			        </div><!--//col-->
		            
		            
		            <!--//app-utilities-->
		        </div><!--//row-->
	            </div><!--//app-header-content-->
	        </div><!--//container-fluid-->
        </div><!--//app-header-inner-->
        <div id="app-sidepanel" class="app-sidepanel sidepanel-hidden"> 
	        <div id="sidepanel-drop" class="sidepanel-drop"></div>
	        <div class="sidepanel-inner d-flex flex-column">
		        <a href="#" id="sidepanel-close" class="sidepanel-close d-xl-none">×</a>
		        <div class="app-branding">
		            <a class="app-logo" href="index.php"><img class="logo-icon me-2" src="img/buk.svg" alt="logo"><span class="logo-text">Perpustakaan</span></a>
	
		        </div><!--//app-branding-->  
		        
			    <nav id="app-nav-main" class="app-nav app-nav-main flex-grow-1">
				    <ul class="app-menu list-unstyled accordion" id="menu-accordion">
					<li class="nav-item">
					        <!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
					        <a class="nav-link" href="index.php">
						        <span class="nav-icon">
						        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-house-door" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
		  <path fill-rule="evenodd" d="M7.646 1.146a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 .146.354v7a.5.5 0 0 1-.5.5H9.5a.5.5 0 0 1-.5-.5v-4H7v4a.5.5 0 0 1-.5.5H2a.5.5 0 0 1-.5-.5v-7a.5.5 0 0 1 .146-.354l6-6zM2.5 7.707V14H6v-4a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5v4h3.5V7.707L8 2.207l-5.5 5.5z"></path>
		  <path fill-rule="evenodd" d="M13 2.5V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z"></path>
		</svg>
						         </span>
		                         <span class="nav-link-text">Beranda</span>
					        </a><!--//nav-link-->
					    </li><!--//nav-item-->
					    <li class="nav-item">
					        <!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
					        <a class="nav-link" href="buku.php">
						        <span class="nav-icon">
						        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-card-list" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M14.5 3h-13a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"></path>
                                <path fill-rule="evenodd" d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8zm0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5z"></path>
                                <circle cx="3.5" cy="5.5" r=".5"></circle>
                                <circle cx="3.5" cy="8" r=".5"></circle>
                                <circle cx="3.5" cy="10.5" r=".5"></circle>
                                </svg>
						        </span>
		                        <span class="nav-link-text">Daftar Buku</span>
					        </a><!--//nav-link-->
					    </li><!--//nav-item-->
						<li class="nav-item">
					        <!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
					        <a class="nav-link" href="ulasan.php">
						        <span class="nav-icon">
						        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
  <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001m-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708z"/>
</svg>
						         </span>
		                         <span class="nav-link-text">Ulasan</span>
					        </a><!--//nav-link-->
					    </li>
                        <li class="nav-item">
					        <!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
					        <a class="nav-link" href="koleksi.php">
						        <span class="nav-icon">
						        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-columns-gap" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
	  <path fill-rule="evenodd" d="M6 1H1v3h5V1zM1 0a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1H1zm14 12h-5v3h5v-3zm-5-1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-5zM6 8H1v7h5V8zM1 7a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1H1zm14-6h-5v7h5V1zm-5-1a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1h-5z"></path>
	</svg>
						         </span>
		                         <span class="nav-link-text">Koleksi</span>
					        </a><!--//nav-link-->
					    </li>
					    
						    </li><!--//nav-item-->
					    </ul><!--//footer-menu-->
				    </nav>
			    </div><!--//app-sidepanel-footer-->
		       
	        </div><!--//sidepanel-inner-->
	    </div><!--//app-sidepanel-->
    </header><!--//app-header-->
    
    <div class="app-wrapper">
	    
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">
            <div class="row g-4 settings-section">
                        <div class="app-card-body">
                        <form action="" method="POST" class="settings-form">
    <div class="mb-4 text-center">
        <?php
        $id_buku = $_GET['id_buku'];
        $result = mysqli_query($koneksi, "SELECT * FROM buku WHERE id_buku = '$id_buku'");
        $row = mysqli_fetch_assoc($result);

        // Pastikan $row tidak null
        if (!$row) {
            echo "<div class='alert alert-danger'>Buku tidak ditemukan.</div>";
            exit;
        }
        ?>
        
        <h2 class="mb-3" style="font-weight: bold; color: #333;"><?= $row['judul'] ?></h2>
        <img src="./img/<?= $row['img'] ?>" alt="Gambar Buku" class="img-fluid rounded mb-3" style="max-width: 200px; height: auto;">
    </div>

    <div class="book-info">
        <p><strong>Penulis:</strong> <?= $row['penulis'] ?></p>
        <p><strong>Penerbit:</strong> <?= $row['penerbit'] ?></p>
        <p><strong>Tahun Terbit:</strong> <?= $row['tahun_terbit'] ?></p>

        <div class="book-code mb-3">
            <label for="setting-input-1" class="form-label"><strong>Kode Buku:</strong></label>
            <input type="text" class="form-control" id="setting-input-1" required name="id_buku" value="<?= $row['id_buku'] ?>" readonly>
        </div>

        <!-- Tanggal Pinjam - disembunyikan dari tampilan -->
        <input type="hidden" name="tgl_pinjam" value="<?= date('Y-m-d') ?>">

        <!-- Tanggal Kembali - disembunyikan dari tampilan -->
        <input type="hidden" name="tgl_kembali" value="<?= date('Y-m-d', strtotime('+3 days')) ?>">

        <button type="submit" style="color:white;" name="pinjam" value="submit" class="btn btn-success w-100">Pinjam Buku</button>
    </div>
</form>

<!-- Bagian Ulasan Buku -->
<div class="mt-4">
    <h3 class="mb-3" style="font-weight: 600;">Ulasan Buku</h3>
    <?php
    // Query untuk mendapatkan ulasan berdasarkan id_buku
    $query_ulasan = "SELECT ulasan, rating, username FROM ulasan_buku 
                     JOIN user ON ulasan_buku.id_user = user.id_user 
                     WHERE id_buku = '$id_buku'";
    $result_ulasan = mysqli_query($koneksi, $query_ulasan);

    if (mysqli_num_rows($result_ulasan) > 0) {
        while ($ulasan = mysqli_fetch_assoc($result_ulasan)) {
            echo "<div class='card mb-3' style='border: none; border-radius: 12px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);'>";
            echo "<div class='card-body' style='background-color: #f8f9fa; padding: 20px;'>";
            echo "<div style='display: flex; justify-content: space-between; align-items: center;'>";
            echo "<h5 style='color: #15a362; font-weight: bold;'>{$ulasan['username']}</h5>";
            echo "<span style='background-color: #15a362; color: white; padding: 5px 10px; border-radius: 5px;'>Rating: <strong>{$ulasan['rating']} / 5</strong></span>";
            echo "</div>";
            echo "<p style='color: #495057; font-style: italic; margin-top: 10px;'>{$ulasan['ulasan']}</p>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<p style='color: #6c757d; text-align: center;'>Tidak ada ulasan untuk buku ini.</p>";
    }
    ?>
</div>


<?php
if (isset($_POST['pinjam'])) {
    // Ambil id_buku dan tanggal dari form
    $id_buku = $_POST['id_buku'];
    $tgl_pinjam = $_POST['tgl_pinjam'];
    $tgl_kembali = $_POST['tgl_kembali'];

    // Ambil id_user dari session
    $id_user = $_SESSION['id_user'];

    // Cek apakah pengguna sudah meminjam buku ini
    $checkPinjamSql = "SELECT * FROM peminjaman WHERE id_user = '$id_user' AND id_buku = '$id_buku' AND tgl_kembali IS NULL";
    $checkPinjamQuery = mysqli_query($koneksi, $checkPinjamSql);

    if (mysqli_num_rows($checkPinjamQuery) > 0) {
        echo "<script>alert('Anda sudah meminjam buku ini!');</script>";
    } else {
        // Memasukkan data peminjaman ke database
        $sql = "INSERT INTO peminjaman (id_user, id_buku, tgl_pinjam, tgl_kembali) VALUES ('$id_user', '$id_buku', '$tgl_pinjam', '$tgl_kembali')";
        $query = mysqli_query($koneksi, $sql);

        if ($query) {
            // Jika peminjaman berhasil, perbarui status buku menjadi 'kosong'
            $sql1 = "UPDATE buku SET status = 'kosong' WHERE id_buku = '$id_buku'";
            $query1 = mysqli_query($koneksi, $sql1);

            if ($query1) {
                // Masukkan data ke tabel koleksi_pribadi
                $insertKoleksiSql = "INSERT INTO koleksi_pribadi (id_user, id_buku) VALUES ('$id_user', '$id_buku')";
                $insertKoleksiQuery = mysqli_query($koneksi, $insertKoleksiSql);

                if ($insertKoleksiQuery) {
                    // Menampilkan alert dan mengarahkan ke halaman buku.php setelah 2 detik
                    echo "<script>
                        alert('Peminjaman berhasil dan buku ditambahkan ke koleksi pribadi!');
                        setTimeout(function() {
                            window.location.href = 'buku.php';
                        }, 2000);
                    </script>";
                } else {
                    echo "<script>alert('Gagal menambahkan buku ke koleksi pribadi!');</script>";
                }
            } else {
                echo "<script>alert('Gagal memperbarui status buku!');</script>";
            }
        } else {
            echo "<script>alert('Peminjaman gagal!');</script>";
        }
    }
}
?>


                        </div><!--//app-card-body-->

            </div><!--//row-->
        </div><!--//container-fluid-->
    </div><!--//app-content-->
	    
	    <footer class="app-footer">
		    <div class="container text-center py-3">
		         <!--/* This template is free as long as you keep the footer attribution link. If you'd like to use the template without the attribution link, you can buy the commercial license via our website: themes.3rdwavemedia.com Thank you for your support. :) */-->
            <small class="copyright">Designed with <span class="sr-only">love</span><svg class="svg-inline--fa fa-heart" style="color: #fb866a;" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="heart" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M0 190.9V185.1C0 115.2 50.52 55.58 119.4 44.1C164.1 36.51 211.4 51.37 244 84.02L256 96L267.1 84.02C300.6 51.37 347 36.51 392.6 44.1C461.5 55.58 512 115.2 512 185.1V190.9C512 232.4 494.8 272.1 464.4 300.4L283.7 469.1C276.2 476.1 266.3 480 256 480C245.7 480 235.8 476.1 228.3 469.1L47.59 300.4C17.23 272.1 .0003 232.4 .0003 190.9L0 190.9z"></path></svg><!-- <i class="fas fa-heart" style="color: #fb866a;"></i> Font Awesome fontawesome.com --> by <a class="app-link" href="http://themes.3rdwavemedia.com" target="_blank">Xiaoying Riley</a> for developers</small>
		       
		    </div>
	    </footer><!--//app-footer-->
	    
    </div>
 
    <!-- Javascript -->          
    <script src="assets/plugins/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>  

    <!-- Charts JS -->
    <script src="assets/plugins/chart.js/chart.min.js"></script> 
    <script src="assets/js/index-charts.js"></script> 
    
    <!-- Page Specific JS -->
    <script src="assets/js/app.js"></script> 


 

</body>
</html> 

