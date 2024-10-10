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
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    
    <!-- FontAwesome JS-->
    <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>
	<style>
		/* Atur ukuran canvas untuk grafik */
#genreChart {
    width: 300px; /* Lebar grafik */
    height: 200px; /* Tinggi grafik */
    margin: 0 auto; /* Pusatkan grafik jika diperlukan */
}

	</style>
    
    <!-- App CSS -->  
    <link id="theme-style" rel="stylesheet" href="assets/css/portal.css">

	<style>

    .promo-text {
        text-align: center;
        padding: 10px 0;
        flex-grow: 1; /* Membuat teks promosi mengisi ruang kosong */
    }

    .promo-text span {
        font-size: 1.2rem; /* Ukuran font lebih besar untuk menarik perhatian */
    }

    .search-mobile-trigger {
        margin-left: auto; /* Menyusun ikon pencarian di kanan */
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
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" role="img">
                            <title>Menu</title>
                            <path stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2" d="M4 7h22M4 15h22M4 23h22"></path>
                        </svg>
                    </a>
                </div><!--//col-->
                
                <div class="col search-mobile-trigger d-sm-none">
                    <svg class="svg-inline--fa fa-magnifying-glass search-mobile-trigger-icon" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="magnifying-glass" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                        <path fill="currentColor" d="M500.3 443.7l-119.7-119.7c27.22-40.41 40.65-90.9 33.46-144.7C401.8 87.79 326.8 13.32 235.2 1.723C99.01-15.51-15.51 99.01 1.724 235.2c11.6 91.64 86.08 166.7 177.6 178.9c53.8 7.189 104.3-6.236 144.7-33.46l119.7 119.7c15.62 15.62 40.95 15.62 56.57 0C515.9 484.7 515.9 459.3 500.3 443.7zM79.1 208c0-70.58 57.42-128 128-128s128 57.42 128 128c0 70.58-57.42 128-128 128S79.1 278.6 79.1 208z"></path>
                    </svg>
                </div><!--//col-->

                <div class="col">
                    <div class="promo-text">
                        <marquee behavior="scroll" direction="left">
                            <span>📚 Ayo, tingkatkan pengetahuanmu! Baca buku, temukan dunia baru! 📖</span>
                        </marquee>
                    </div>
                </div><!--//col-->

                <!-- Tampilkan Nama Pengguna yang Login -->
                <div class="col-auto">
                    <div class="user-info">
                        <?php if(isset($_SESSION['username'])): ?>
                            <span>Halo, <?php echo $_SESSION['username']; ?>!</span>
                        <?php else: ?>
                            <span>Halo, Pengunjung!</span>
                        <?php endif; ?>
                    </div>
                </div><!--//col-->
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
					        <a class="nav-link active" href="index.php">
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
					    				    
				    </ul><!--//app-menu-->
			    </nav><!--//app-nav-->
			    <div class="app-sidepanel-footer">
				    <nav class="app-nav app-nav-footer">
					    <ul class="app-menu footer-menu list-unstyled">
						    
						    <li class="nav-item">
						        <!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
						        <a class="nav-link" href="../logout.php">
							        <span class="nav-icon">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z"/>
  <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z"/>
</svg>
							        </span>
			                        <span class="nav-link-text">Log out</span>
						        </a><!--//nav-link-->
						    </li><!--//nav-item-->
					    </ul><!--//footer-menu-->
				    </nav>
			    </div><!--//app-sidepanel-footer-->
		       
	        </div><!--//sidepanel-inner-->
	    </div><!--//app-sidepanel-->
    </header><!--//app-header-->
    
    <div class="app-wrapper">
	    
	<div class="app-content pt-3 p-md-3 p-lg-4">
    <div class="app-card alert alert-dismissible shadow-sm mb-4 border-left-decoration" role="alert">
        <div class="inner">
            <div class="app-card-body p-3 p-lg-4">
                <h3 class="mb-3">Selamat datang di Perpustakaan Digital!</h3>
                   <div class="mb-2">
                    <p>Temukan buku favoritmu dan tingkatkan pengetahuanmu dengan berbagai koleksi yang kami sediakan.</p>
                </div>
                <div class="col-12 col-lg-6">
                    <a class="btn app-btn-primary" href="buku.php">Mulai Pinjam</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Rekomendasi Buku -->
        <div class="col-md-6">
            <div class="app-card shadow-sm h-100">
                <div class="app-card-body p-3 p-lg-4">
                    <h4 class="stats-type mb-3">Rekomendasi Buku</h4>
                    <ul id="rekomendasiBuku">
                        <?php
                        $result = mysqli_query($koneksi, "SELECT * FROM buku ORDER BY RAND() LIMIT 5");
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<li><a href='pinjam.php?id_buku=" . $row['id_buku'] . "'>" . $row['judul'] . "</a> oleh " . $row['penulis'] . "</li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Tips Membaca -->
        <div class="col-md-6">
            <div class="app-card shadow-sm h-100">
                <div class="app-card-body p-3 p-lg-4">
                    <h4 class="stats-type mb-3">Tips Membaca</h4>
                    <ul>
                        <li>Atur waktu khusus untuk membaca setiap hari.</li>
                        <li>Temukan tempat yang nyaman dan tenang.</li>
                        <li>Gunakan catatan untuk mencatat ide-ide penting.</li>
                        <li>Jangan ragu untuk membaca ulang buku yang kamu suka.</li>
                        <li>Diskusikan buku dengan teman untuk memperdalam pemahaman.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>




	    
	    <footer class="app-footer">
		    <div class="container text-center py-3">
		         <!--/* This template is free as long as you keep the footer attribution link. If you'd like to use the template without the attribution link, you can buy the commercial license via our website: themes.3rdwavemedia.com Thank you for your support. :) */-->
            <small class="copyright">Designed with <span class="sr-only">love</span><svg class="svg-inline--fa fa-heart" style="color: #fb866a;" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="heart" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M0 190.9V185.1C0 115.2 50.52 55.58 119.4 44.1C164.1 36.51 211.4 51.37 244 84.02L256 96L267.1 84.02C300.6 51.37 347 36.51 392.6 44.1C461.5 55.58 512 115.2 512 185.1V190.9C512 232.4 494.8 272.1 464.4 300.4L283.7 469.1C276.2 476.1 266.3 480 256 480C245.7 480 235.8 476.1 228.3 469.1L47.59 300.4C17.23 272.1 .0003 232.4 .0003 190.9L0 190.9z"></path></svg><!-- <i class="fas fa-heart" style="color: #fb866a;"></i> Font Awesome fontawesome.com --> by <a class="app-link" href="http://themes.3rdwavemedia.com" target="_blank">Xiaoying Riley</a> for developers</small>
		       
		    </div>
	    </footer><!--//app-footer-->
	    
    </div><!--//app-wrapper-->    					

 
    <!-- Javascript -->          
    <script src="assets/plugins/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>  

    <!-- Charts JS -->
    <script src="assets/plugins/chart.js/chart.min.js"></script> 
    <script src="assets/js/index-charts.js"></script> 
    
    <!-- Page Specific JS -->
    <script src="assets/js/app.js"></script> 
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



	<!-- unutk jumlah -->

	<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data from PHP
        const genreData = <?php echo json_encode($genre_data); ?>;

        // Extract labels and data
        const labels = genreData.map(item => item.kategori);
        const data = genreData.map(item => item.jumlah);

        // Create chart
        const ctx = document.getElementById('genreChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: ['#ff6384', '#36a2eb', '#cc65fe', '#ffce56'],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('jumlahPengguna').textContent = <?php echo json_encode($jumlah_pengguna); ?>;
        document.getElementById('jumlahBuku').textContent = <?php echo json_encode($jumlah_buku); ?>;
    });
</script>


 


 


 

</body>
</html> 

