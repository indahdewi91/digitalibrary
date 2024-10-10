<?php
    include "koneksi.php"; // Koneksi ke database
    session_start(); // Memulai session
    
    // Cek apakah pengguna sudah login
    if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../login.php"); // Arahkan ke halaman login jika tidak memiliki akses
        exit();
    }
    
    // Menampilkan pesan sukses jika ada
    if (isset($_SESSION['success_message'])) {
        echo "<div class='alert alert-success'>" . $_SESSION['success_message'] . "</div>";
        unset($_SESSION['success_message']);
    }

    $result_buku = mysqli_query($koneksi, "
    SELECT buku.*, kategori_buku.nama_kategori 
    FROM buku 
    JOIN kategori_buku ON buku.id_kategori = kategori_buku.id_kategori
");

// Ambil semua kategori untuk filter
$result_kategori = mysqli_query($koneksi, "SELECT * FROM kategori_buku");
$kategori_data = [];
while ($kategori = mysqli_fetch_assoc($result_kategori)) {
    $kategori_data[] = $kategori['nama_kategori'];
}

?>

<?php
$total_query = "SELECT COUNT(*) as total FROM buku";
$total_result = mysqli_query($koneksi, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_data = $total_row['total'];


$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1); 
$offset = ($page - 1) * $limit;


$total_pages = ceil($total_data / $limit);
$query = "SELECT * FROM buku LIMIT $limit OFFSET $offset";
$result = mysqli_query($koneksi, $query);
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
    <link rel="shortcut icon" href="img/buk.svg"> 
    
    <!-- FontAwesome JS-->
    <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>
    
    <!-- App CSS -->  
    <link id="theme-style" rel="stylesheet" href="assets/css/portal.css">

    <style>
    .pagination .page-link {
    color: #5CB377; /* Warna teks */
}

.pagination .page-link:hover {
    background-color: #5CB377; /* Warna background saat hover */
    color: white; /* Warna teks saat hover */
}

.pagination .page-item.active .page-link {
    background-color: #5CB377; /* Warna background halaman aktif */
    border-color: #5CB377; /* Warna border halaman aktif */
    color: white; /* Warna teks halaman aktif */
}

.pagination .page-item.disabled .page-link {
    color: #d3d3d3; /* Warna teks untuk tombol yang disabled */
}

    .btn-outline-primary:hover {
        background-color: #5CB377; 
        color: white; 
    }

    .btn-outline-primary {
    --bs-btn-color: #5cb377;
    --bs-btn-border-color: #5cb377;
    --bs-btn-hover-color: #FFFF;
    --bs-btn-hover-bg: #5cb377;
    --bs-btn-hover-border-color: #5cb377;
    --bs-btn-focus-shadow-rgb: 21, 163, 98;
    --bs-btn-active-color: #FFFF;
    --bs-btn-active-bg: #5cb377;
    --bs-btn-active-border-color: #5cb377;
    --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
    --bs-btn-disabled-color: #5cb377;
    --bs-btn-disabled-bg: transparent;
    --bs-gradient: none;
    }

	.btn-info {
    --bs-btn-color: #ffff;
    --bs-btn-bg: #5cb377;
    --bs-btn-border-color: #5cb377;
    --bs-btn-hover-color: #ffff;
    --bs-btn-hover-bg: #5cb377;
    --bs-btn-hover-border-color: #5cb377;
    --bs-btn-focus-shadow-rgb: 77, 130, 199;
    --bs-btn-active-color: #fff;
    --bs-btn-active-bg: #5cb377;
    --bs-btn-active-border-color: #5cb377;
    --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
    --bs-btn-disabled-color: #ffff;
    --bs-btn-disabled-bg: #5cb377;
    --bs-btn-disabled-border-color: #5cb377;
}

.btn-primary {
    --bs-btn-color: #ffff;
    --bs-btn-bg: #5b99ea ;
    --bs-btn-border-color: #5b99ea ;
    --bs-btn-hover-color: #ffff;
    --bs-btn-hover-bg: #5b99ea;
    --bs-btn-hover-border-color: #5b99ea;
    --bs-btn-focus-shadow-rgb: 18, 139, 83;
    --bs-btn-active-color: #ffff;
    --bs-btn-active-bg: #44b581;
    --bs-btn-active-border-color: #2cac72;
    --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
    --bs-btn-disabled-color: #ffff;
    --bs-btn-disabled-bg: #15a362;
    --bs-btn-disabled-border-color: #15a362;
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
		            <div class="app-search-box col">
		                <form class="app-search-form">   
							<input type="text" placeholder="Search..." name="search" class="form-control search-input">
							<button type="submit" class="btn search-btn btn-primary" value="Search"><svg class="svg-inline--fa fa-magnifying-glass" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="magnifying-glass" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M500.3 443.7l-119.7-119.7c27.22-40.41 40.65-90.9 33.46-144.7C401.8 87.79 326.8 13.32 235.2 1.723C99.01-15.51-15.51 99.01 1.724 235.2c11.6 91.64 86.08 166.7 177.6 178.9c53.8 7.189 104.3-6.236 144.7-33.46l119.7 119.7c15.62 15.62 40.95 15.62 56.57 0C515.9 484.7 515.9 459.3 500.3 443.7zM79.1 208c0-70.58 57.42-128 128-128s128 57.42 128 128c0 70.58-57.42 128-128 128S79.1 278.6 79.1 208z"></path></svg><!-- <i class="fas fa-search"></i> Font Awesome fontawesome.com --></button> 
				        </form>
		            </div><!--//app-search-box-->
		            
		            <div class="app-utilities col-auto">
			            <div class="app-utility-item app-notifications-dropdown dropdown">    
				            <a class="dropdown-toggle no-toggle-arrow" id="notifications-dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" title="Notifications">
					            <!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
					            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-bell icon" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2z"></path>
  <path fill-rule="evenodd" d="M8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"></path>
</svg>
					            <span class="icon-badge">3</span>
					        </a><!--//dropdown-toggle-->
					        
					        <div class="dropdown-menu p-0" aria-labelledby="notifications-dropdown-toggle">
					            <div class="dropdown-menu-header p-3">
						            <h5 class="dropdown-menu-title mb-0">Notifications</h5>
						        </div><!--//dropdown-menu-title-->
						        <div class="dropdown-menu-content">
							       <div class="item p-3">
								        <div class="row gx-2 justify-content-between align-items-center">
									        <div class="col-auto">
										       <img class="profile-image" src="assets/images/profiles/profile-1.png" alt="">
									        </div><!--//col-->
									        <div class="col">
										        <div class="info"> 
											        <div class="desc">Amy shared a file with you. Lorem ipsum dolor sit amet, consectetur adipiscing elit. </div>
											        <div class="meta"> 2 hrs ago</div>
										        </div>
									        </div><!--//col--> 
								        </div><!--//row-->
								        <a class="link-mask" href="#"></a>
							       </div><!--//item-->
							       <div class="item p-3">
								        <div class="row gx-2 justify-content-between align-items-center">
									        <div class="col-auto">
										        <div class="app-icon-holder">
											        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-receipt" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
	  <path fill-rule="evenodd" d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27zm.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0l-.509-.51z"></path>
	  <path fill-rule="evenodd" d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5z"></path>
	</svg>
										        </div>
									        </div><!--//col-->
									        <div class="col">
										        <div class="info"> 
											        <div class="desc">You have a new invoice. Proin venenatis interdum est.</div>
											        <div class="meta"> 1 day ago</div>
										        </div>
									        </div><!--//col-->
								        </div><!--//row-->
								        <a class="link-mask" href="#"></a>
							       </div><!--//item-->
							       <div class="item p-3">
								        <div class="row gx-2 justify-content-between align-items-center">
									        <div class="col-auto">
										        <div class="app-icon-holder icon-holder-mono">
											        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-bar-chart-line" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1V2zm1 12h2V2h-2v12zm-3 0V7H7v7h2zm-5 0v-3H2v3h2z"></path>
</svg>
										        </div>
									        </div><!--//col-->
									        <div class="col">
										        <div class="info"> 
											        <div class="desc">Your report is ready. Proin venenatis interdum est.</div>
											        <div class="meta"> 3 days ago</div>
										        </div>
									        </div><!--//col-->
								        </div><!--//row-->
								        <a class="link-mask" href="#"></a>
							       </div><!--//item-->
							       <div class="item p-3">
								        <div class="row gx-2 justify-content-between align-items-center">
									        <div class="col-auto">
										       <img class="profile-image" src="assets/images/profiles/profile-2.png" alt="">
									        </div><!--//col-->
									        <div class="col">
										        <div class="info"> 
											        <div class="desc">James sent you a new message.</div>
											        <div class="meta"> 7 days ago</div>
										        </div>
									        </div><!--//col--> 
								        </div><!--//row-->
								        <a class="link-mask" href="#"></a>
							       </div><!--//item-->
						        </div><!--//dropdown-menu-content-->
						        
						        <div class="dropdown-menu-footer p-2 text-center">
							        <a href="#">View all</a>
						        </div>
															
							</div><!--//dropdown-menu-->					        
				        </div><!--//app-utility-item-->
			            <div class="app-utility-item">
				            <a href="settings.html" title="Settings">
					            <!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
					            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-gear icon" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M8.837 1.626c-.246-.835-1.428-.835-1.674 0l-.094.319A1.873 1.873 0 0 1 4.377 3.06l-.292-.16c-.764-.415-1.6.42-1.184 1.185l.159.292a1.873 1.873 0 0 1-1.115 2.692l-.319.094c-.835.246-.835 1.428 0 1.674l.319.094a1.873 1.873 0 0 1 1.115 2.693l-.16.291c-.415.764.42 1.6 1.185 1.184l.292-.159a1.873 1.873 0 0 1 2.692 1.116l.094.318c.246.835 1.428.835 1.674 0l.094-.319a1.873 1.873 0 0 1 2.693-1.115l.291.16c.764.415 1.6-.42 1.184-1.185l-.159-.291a1.873 1.873 0 0 1 1.116-2.693l.318-.094c.835-.246.835-1.428 0-1.674l-.319-.094a1.873 1.873 0 0 1-1.115-2.692l.16-.292c.415-.764-.42-1.6-1.185-1.184l-.291.159A1.873 1.873 0 0 1 8.93 1.945l-.094-.319zm-2.633-.283c.527-1.79 3.065-1.79 3.592 0l.094.319a.873.873 0 0 0 1.255.52l.292-.16c1.64-.892 3.434.901 2.54 2.541l-.159.292a.873.873 0 0 0 .52 1.255l.319.094c1.79.527 1.79 3.065 0 3.592l-.319.094a.873.873 0 0 0-.52 1.255l.16.292c.893 1.64-.902 3.434-2.541 2.54l-.292-.159a.873.873 0 0 0-1.255.52l-.094.319c-.527 1.79-3.065 1.79-3.592 0l-.094-.319a.873.873 0 0 0-1.255-.52l-.292.16c-1.64.893-3.433-.902-2.54-2.541l.159-.292a.873.873 0 0 0-.52-1.255l-.319-.094c-1.79-.527-1.79-3.065 0-3.592l.319-.094a.873.873 0 0 0 .52-1.255l-.16-.292c-.892-1.64.902-3.433 2.541-2.54l.292.159a.873.873 0 0 0 1.255-.52l.094-.319z"></path>
  <path fill-rule="evenodd" d="M8 5.754a2.246 2.246 0 1 0 0 4.492 2.246 2.246 0 0 0 0-4.492zM4.754 8a3.246 3.246 0 1 1 6.492 0 3.246 3.246 0 0 1-6.492 0z"></path>
</svg>
					        </a>
					    </div><!--//app-utility-item-->
			            
			            <div class="app-utility-item app-user-dropdown dropdown">
				            <a class="dropdown-toggle" id="user-dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false"><img src="assets/images/user.png" alt="user profile"></a>
				            <ul class="dropdown-menu" aria-labelledby="user-dropdown-toggle">
								<li><a class="dropdown-item" href="account.html">Account</a></li>
								<li><a class="dropdown-item" href="settings.html">Settings</a></li>
								<li><hr class="dropdown-divider"></li>
								<li><a class="dropdown-item" href="login.html">Log Out</a></li>
							</ul>
			            </div><!--//app-user-dropdown--> 
		            </div><!--//app-utilities-->
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
					        <a class="nav-link active" href="daftarbuku.php">
						        <span class="nav-icon">
						        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-card-list" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M14.5 3h-13a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
  <path fill-rule="evenodd" d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8zm0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5z"/>
  <circle cx="3.5" cy="5.5" r=".5"/>
  <circle cx="3.5" cy="8" r=".5"/>
  <circle cx="3.5" cy="10.5" r=".5"/>
</svg>
						         </span>
		                         <span class="nav-link-text">Kelola Buku</span>
					        </a><!--//nav-link-->
					    </li><!--//nav-item-->


                        <li class="nav-item">
					        <!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
					        <a class="nav-link" href="kategori.php">
						        <span class="nav-icon">
						        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-files" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
	  <path fill-rule="evenodd" d="M4 2h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4z"></path>
	  <path d="M6 0h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2v-1a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H6a1 1 0 0 0-1 1H4a2 2 0 0 1 2-2z"></path>
	</svg>
						         </span>
		                         <span class="nav-link-text">Kelola Kategori</span>
					        </a><!--//nav-link-->
					    </li><!--//nav-item-->


						<li class="nav-item">
					        <!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
					        <a class="nav-link" href="ulasan_admin.php">
						        <span class="nav-icon">
						        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
  <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001m-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708z"/>
</svg>
						         </span>
		                         <span class="nav-link-text">Kelola Ulasan</span>
					        </a><!--//nav-link-->
					    </li>

                        
					    
						<li class="nav-item">
					        <!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
					        <a class="nav-link" href="daftaruser.php">
						        <span class="nav-icon">
						        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-person" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M10 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm6 5c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"></path>
</svg>
						         </span>
		                         <span class="nav-link-text">Daftar User</span>
					        </a><!--//nav-link-->
					    </li>
                        <li class="nav-item">
					        <!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
					        <a class="nav-link" href="riwayat_pinjam.php">
						        <span class="nav-icon">
						        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-receipt" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
	  <path fill-rule="evenodd" d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27zm.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0l-.509-.51z"></path>
	  <path fill-rule="evenodd" d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5z"></path>
	</svg>
						         </span>
		                         <span class="nav-link-text">Riwayat Pinjam</span>
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
		    <!--//container-fluid-->
            <div class="container-xl">
			<h1 class="app-page-title">Daftar Buku</h1>
			<div class="app-card shadow-sm mb-4 border-left-decoration">
				    <div class="inner">
					    <div class="app-card-body p-4">
						    <div class="row gx-5 gy-3">
						        <div class="col-12 col-lg-9">
							        
							        <div>Daftar buku yang tersedia.</div>
							    </div><!--//col-->
							    <div class="col-12 col-lg-3">
    <button class="btn app-btn-primary" data-bs-toggle="modal" data-bs-target="#tambahBukuModal">
        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-download me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"></path>
            <path fill-rule="evenodd" d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"></path>
        </svg>Tambah Buku
    </button>
</div>

						    </div><!--//row-->
	
					    </div><!--//app-card-body-->
					    
				    </div><!--//inner-->
			    </div>
  <div class="mb-3">
                <input type="text" id="search" class="form-control" placeholder="Cari buku...">
    </div>
                
				<div class="d-flex justify-content-between mb-3">
   <!-- Filter genre -->
<div class="btn-group" role="group" aria-label="Kategori Buku">
    <?php foreach ($kategori_data as $kategori): ?>
        <button type="button" class="btn btn-outline-primary" onclick="filterByGenre('<?= $kategori; ?>')"><?= $kategori; ?></button>
    <?php endforeach; ?>
    <button type="button" class="btn btn-outline-primary" onclick="resetFilter()">Reset</button>
</div>

    <!-- Dropdown untuk memilih jumlah baris -->
    <div class="d-flex align-items-center">
        <label for="limitSelect" class="me-2">Tampilkan:</label>
        <select id="limitSelect" class="form-select" onchange="changeLimit()">
            <option value="10" <?php echo $limit == 10 ? 'selected' : ''; ?>>10</option>
            <option value="20" <?php echo $limit == 20 ? 'selected' : ''; ?>>20</option>
            <option value="30" <?php echo $limit == 30 ? 'selected' : ''; ?>>30</option>
        </select>
    </div>
</div>

<!-- Daftar buku -->
<div class="tab-content" id="orders-table-tab-content">
    <div class="tab-pane fade show active" id="orders-all" role="tabpanel" aria-labelledby="orders-all-tab">
        <div class="app-card app-card-orders-table shadow-sm mb-5">
            <div class="app-card-body">
                <div class="table-responsive">
                    <table class="table app-table-hover mb-0 text-left">
                        <thead>
                            <tr align="center">
                                <th class="cell">Kode Buku</th>
                                <th class="cell">Gambar URL</th>
                                <th class="cell">Judul</th>
                                <th class="cell">Penulis</th>
                                <th class="cell">Penerbit</th>
                                <th class="cell">Tahun Terbit</th>
                                <th class="cell">Kategori</th>
                                <th class="cell">Status</th>
                                <th class="cell">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="resultsTableBody">
                            <?php
                            $kategori = isset($_GET['id_kategori']) ? $_GET['id_kategori'] : '';
                            $query = "SELECT buku.*, kategori_buku.nama_kategori FROM buku JOIN kategori_buku ON buku.id_kategori = kategori_buku.id_kategori";

                            if ($kategori) {
                                $query .= $search ? " AND buku.id_kategori='" . mysqli_real_escape_string($koneksi, $kategori) . "'" 
                                                  : " WHERE buku.id_kategori='" . mysqli_real_escape_string($koneksi, $kategori) . "'";
                            }
                            
                            $result = mysqli_query($koneksi, $query);
                            
                            while ($row = mysqli_fetch_assoc($result)) {
                                $imageFileName = $row['img'];
                            ?>
                                <tr class="book-item" data-genre="<?php echo $row['nama_kategori']; ?>" align="center">
                                    <td class="cell"><?php echo $row['id_buku']; ?></td>
                                    <td class="cell"><?php echo $imageFileName; ?></td>
                                    <td class="cell"><?php echo $row['judul']; ?></td>
                                    <td class="cell"><?php echo $row['penulis']; ?></td>
                                    <td class="cell"><?php echo $row['penerbit']; ?></td>
                                    <td class="cell"><?php echo $row['tahun_terbit']; ?></td>
                                    <td class="cell"><?php echo $row['nama_kategori']; ?></td>
                                    <td class="cell"><?php echo $row['status']; ?></td>
                                    <td class="cell" align="center">
                                        <a href="#" onclick="showEditModal('<?php echo $row['id_buku']; ?>');">
                                            <button name="edit" class="btn btn-warning" style="color: white">Edit</button>
                                        </a>
                                        <a href="#" onclick="showDeleteModal('<?php echo $row['id_buku']; ?>');">
                                            <button class="btn btn-danger" style="color: white">Hapus</button>
                                        </a>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pagination di bawah tabel -->
<nav aria-label="Page navigation" class="d-flex justify-content-center mt-3">
    <ul class="pagination">
        <li class="page-item <?php if($page <= 1){ echo 'disabled'; } ?>">
            <a class="page-link" href="?page=1&limit=<?php echo $limit; ?>">First</a>
        </li>
        <li class="page-item <?php if($page <= 1){ echo 'disabled'; } ?>">
            <a class="page-link" href="<?php if($page <= 1){ echo '#'; } else { echo "?page=".($page - 1)."&limit=".$limit; } ?>">Previous</a>
        </li>
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?php if($page == $i) echo 'active'; ?>">
                <a class="page-link" href="?page=<?php echo $i; ?>&limit=<?php echo $limit; ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>
        <li class="page-item <?php if($page >= $total_pages){ echo 'disabled'; } ?>">
            <a class="page-link" href="<?php if($page >= $total_pages){ echo '#'; } else { echo "?page=".($page + 1)."&limit=".$limit; } ?>">Next</a>
        </li>
        <li class="page-item <?php if($page >= $total_pages){ echo 'disabled'; } ?>">
            <a class="page-link" href="?page=<?php echo $total_pages; ?>&limit=<?php echo $limit; ?>">Last</a>
        </li>
    </ul>
</nav>



						        </div><!--//table-responsive-->

								<div class="modal fade" id="hapusModal" tabindex="-1" aria-labelledby="hapusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hapusModalLabel">Perpustakaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Anda yakin ingin menghapus buku ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"  style="color: white">Batal</button>
                <a id="confirmDelete" href="#" class="btn btn-danger" style="color: white">Hapus</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Input Buku -->
<div class="modal fade" id="tambahBukuModal" tabindex="-1" aria-labelledby="tambahBukuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahBukuModalLabel">Tambah Buku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
			<form action="" method="POST" class="settings-form">
    <div class="mb-3">
        <label for="setting-input-1" class="form-label">Kode Buku</label>
        <input type="text" class="form-control" id="setting-input-1" placeholder="Id Buku" required="" name="id_buku">
    </div>
    <div class="mb-3">
        <label for="setting-input-2" class="form-label">Judul</label>
        <input type="text" class="form-control" id="setting-input-2" placeholder="Judul Buku" name="judul">
    </div>
    <div class="mb-3">
        <label for="setting-input-3" class="form-label">Penulis</label>
        <input type="text" class="form-control" id="setting-input-3" placeholder="Penulis" name="penulis">
    </div>
    <div class="mb-3">
        <label for="setting-input-3" class="form-label">Penerbit</label>
        <input type="text" class="form-control" id="setting-input-3" placeholder="Penerbit" name="penerbit">
    </div>
    <div class="mb-3">
        <label for="setting-input-3" class="form-label">Tahun Terbit</label>
        <input type="text" class="form-control" id="setting-input-3" placeholder="Tahun Terbit" name="tahun_terbit">
    </div>
    <div class="mb-3">
        <label for="setting-input-3" class="form-label">Kategori</label>
        <select name="kategori" class="form-select col-12">
        <option selected="" value="">Pilih Kategori</option>

<!-- PHP untuk menampilkan pilihan kategori dari database -->
<?php
$sql_kategori = "SELECT id_kategori, nama_kategori FROM kategori_buku";
$result_kategori = mysqli_query($koneksi, $sql_kategori);

while ($row = mysqli_fetch_assoc($result_kategori)) {
    echo "<option value='" . $row['id_kategori'] . "'>" . $row['nama_kategori'] . "</option>";
}
?>
        </select>
    </div>
    <div class="mb-3">
        <label for="img" class="form-label">Gambar</label>
        <input class="form-control" id="img" type="file" accept="image/png, image/gif, image/jpeg" name="img" required>
    </div>
    <div class="mb-3">
        <label for="pdf_file" class="form-label">PDF File</label>
        <input class="form-control" id="pdf_file" type="file" accept="application/pdf" name="pdf_file" required>
    </div>
    <button type="submit" name="submit" value="submit" class="btn app-btn-primary">Simpan</button>
</form>

<?php
if (isset($_POST['submit'])) {
    $id_buku = $_POST['id_buku'];
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $img = $_POST['img'];
    $pdf_file = $_POST['pdf_file'];
    $id_kategori = $_POST['id_kategori'];

    // Menyimpan data ke database
    $sql = "INSERT INTO `buku` (`id_buku`, `judul`, `penulis`, `penerbit`, `tahun_terbit`, `status`, `img`, `pdf_file`,`id_kategori`) 
            VALUES ('$id_buku', '$judul', '$penulis', '$penerbit', '$tahun_terbit', 'tersedia', '$kategori', '$img', '$pdf_file','$id_kategori');";
    
    $query = mysqli_query($koneksi, $sql);

    if ($query) {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            var customAlert = document.getElementById('customAlert');
            customAlert.style.display = 'block';

            setTimeout(function() {
                window.location.href = 'daftarbuku.php';
            }, 2000);
        });
        </script>";
    } else {
        echo "Terjadi kesalahan saat menambahkan buku: " . mysqli_error($koneksi);
    }
}
?>





            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Buku -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST" class="settings-form" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Buku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit-id_buku" class="form-label">Id Buku</label>
                        <input type="text" class="form-control" id="edit-id_buku" name="id_buku" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="edit-judul" class="form-label">Judul</label>
                        <input type="text" class="form-control" id="edit-judul" name="judul">
                    </div>
                    <div class="mb-3">
                        <label for="edit-penulis" class="form-label">Penulis</label>
                        <input type="text" class="form-control" id="edit-penulis" name="penulis">
                    </div>
                    <div class="mb-3">
                        <label for="edit-penerbit" class="form-label">Penerbit</label>
                        <input type="text" class="form-control" id="edit-penerbit" name="penerbit">
                    </div>
                    <div class="mb-3">
                        <label for="edit-tahun_terbit" class="form-label">Tahun Terbit</label>
                        <input type="text" class="form-control" id="edit-tahun_terbit" name="tahun_terbit">
                    </div>
                    <div class="mb-3">
                        <label for="edit-img" class="form-label">Gambar (URL)</label>
                        <input type="text" class="form-control" id="edit-img" name="img">
                    </div>
                    <div class="mb-3">
                        <label for="edit-img_file" class="form-label">Upload Gambar Baru</label>
                        <input type="file" class="form-control" id="edit-img_file" name="img_file">
                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengganti gambar.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" name="submit" value="submit" class="btn btn-primary" style="color: white">Simpan</button>
                </div>
            </form>

			<?php
if (isset($_POST['submit'])) {
    include 'koneksi.php';

    $id_buku = $_POST['id_buku'];
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $img = $_POST['img'];

 
    if (isset($_FILES['img_file']) && $_FILES['img_file']['error'] == 0) {
        $target_dir = "img/"; 
        $target_file = $target_dir . basename($_FILES["img_file"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

     
        if (getimagesize($_FILES["img_file"]["tmp_name"]) === false) {
            echo "File bukan gambar.";
            $uploadOk = 0;
        }

        
        if ($_FILES["img_file"]["size"] > 5000000) { 
            echo "Ukuran file terlalu besar.";
            $uploadOk = 0;
        }

        
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";
            $uploadOk = 0;
        }

        
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["img_file"]["tmp_name"], $target_file)) {
                $img = basename($_FILES["img_file"]["name"]); 
            } else {
                echo "Terjadi kesalahan saat meng-upload gambar.";
            }
        }
    }

    // Update data buku
    $sql = "UPDATE buku SET judul = '$judul', penulis = '$penulis', penerbit = '$penerbit', tahun_terbit = '$tahun_terbit', img = '$img' WHERE id_buku = '$id_buku'";
    $query = mysqli_query($koneksi, $sql);

    if ($query) {
        echo "<script>
		document.addEventListener('DOMContentLoaded', function() {
			var customAlert2 = document.getElementById('customAlert2');
			customAlert2.style.display = 'block';
			setTimeout(function() {
				window.location.href = 'daftarbuku.php';
			},500);
		});
	</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data buku.');</script>";
    }
}
?>
        </div>
    </div>
</div>

<div class="modal fade" id="hapusModal" tabindex="-1" aria-labelledby="hapusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hapusModalLabel">Perpustakaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Anda yakin ingin menghapus buku ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"  style="color: white">Batal</button>
                <a id="confirmDelete" href="#" class="btn btn-danger" style="color: white">Hapus</a>
            </div>
        </div>
    </div>
</div>






						       
						    </div><!--//app-card-body-->		
						</div><!--//app-card-->
					
						
			        </div><!--//tab-pane-->
			        
			    
				</div><!--//tab-content-->
	    </div><!--//app-content-->
	    
	    <footer class="app-footer">
		    <div class="container text-center py-3">
		         <!--/* This template is free as long as you keep the footer attribution link. If you'd like to use the template without the attribution link, you can buy the commercial license via our website: themes.3rdwavemedia.com Thank you for your support. :) */-->
            <small class="copyright">Designed with <span class="sr-only">love</span><svg class="svg-inline--fa fa-heart" style="color: #fb866a;" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="heart" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M0 190.9V185.1C0 115.2 50.52 55.58 119.4 44.1C164.1 36.51 211.4 51.37 244 84.02L256 96L267.1 84.02C300.6 51.37 347 36.51 392.6 44.1C461.5 55.58 512 115.2 512 185.1V190.9C512 232.4 494.8 272.1 464.4 300.4L283.7 469.1C276.2 476.1 266.3 480 256 480C245.7 480 235.8 476.1 228.3 469.1L47.59 300.4C17.23 272.1 .0003 232.4 .0003 190.9L0 190.9z"></path></svg><!-- <i class="fas fa-heart" style="color: #fb866a;"></i> Font Awesome fontawesome.com --> by <a class="app-link" href="http://themes.3rdwavemedia.com" target="_blank">Xiaoying Riley</a> for developers</small>
		       
		    </div>
	    </footer><!--//app-footer-->
	    
    </div><!--//app-wrapper-->   
	<style>
    .custom-alert {
        display: none; /* Sembunyikan alert secara default */
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #28a745; /* Warna hijau untuk notifikasi berhasil */
        color: white;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        z-index: 9999;
    }

    .alert-text {
        font-size: 16px;
        font-weight: bold;
    }
</style> 					

 
    <!-- Javascript -->          
    <script src="assets/plugins/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>  
    
    <!-- Page Specific JS -->
    <script src="assets/js/app.js"></script> 

	<script>
	function showDeleteModal(id_buku) {
        document.getElementById('confirmDelete').setAttribute('href', 'hapus.php?id_buku=' + id_buku);
        var myModal = new bootstrap.Modal(document.getElementById('hapusModal'));
        myModal.show();
    }
</script>

<div id="customAlert" class="custom-alert">
    <span class="alert-text">Perpustakaan: Buku Berhasil Ditambahkan!</span>
</div>

<div id="customAlert2" class="custom-alert">
    <span class="alert-text">Perpustakaan: Data Berhasil Diperbarui!</span>
</div>

<script>
    document.getElementById('search').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const bookItems = document.querySelectorAll('.book-item');

        bookItems.forEach(item => {
			const id = item.querySelector('td:nth-child(1)').textContent.toLowerCase(); // Judul
			const img = item.querySelector('td:nth-child(2)').textContent.toLowerCase(); // Judul
            const title = item.querySelector('td:nth-child(3)').textContent.toLowerCase(); // Judul
            const author = item.querySelector('td:nth-child(4)').textContent.toLowerCase(); // Penulis
            const publisher = item.querySelector('td:nth-child(5)').textContent.toLowerCase(); // Penerbit
            const year = item.querySelector('td:nth-child(6)').textContent.toLowerCase(); // Tahun Terbit

            // Mencocokkan input dengan judul, penulis, penerbit, tahun, dan kategori
            const matches = id.includes(searchTerm) ||  img.includes(searchTerm) || title.includes(searchTerm) || author.includes(searchTerm) || publisher.includes(searchTerm) || year.includes(searchTerm);

            item.style.display = matches ? 'table-row' : 'none'; // Tampilkan atau sembunyikan baris
        });
    });

    // buat genre

    function filterByGenre(genre) {
    const rows = document.querySelectorAll('#resultsTableBody .book-item');
    rows.forEach(row => {
        if (row.getAttribute('data-genre') === genre) {
            row.style.display = ''; // Tampilkan baris yang sesuai
        } else {
            row.style.display = 'none'; // Sembunyikan baris yang tidak sesuai
        }
    });
}

function resetFilter() {
    const rows = document.querySelectorAll('#resultsTableBody .book-item');
    rows.forEach(row => {
        row.style.display = ''; // Tampilkan semua baris
    });
}
</script>

<script>
function showEditModal(id_buku) {
    fetch(`get_buku.php?id_buku=${id_buku}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit-id_buku').value = data.id_buku;
            document.getElementById('edit-judul').value = data.judul;
            document.getElementById('edit-penulis').value = data.penulis;
            document.getElementById('edit-penerbit').value = data.penerbit;
            document.getElementById('edit-tahun_terbit').value = data.tahun_terbit;
            document.getElementById('edit-img').value = data.img;

            // Tampilkan modal
            var editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
        })
        .catch(error => console.error('Error:', error));
}
</script>



<script>
    function changeLimit() {
        var limit = document.getElementById("limitSelect").value;
        window.location.href = "?page=1&limit=" + limit; // Reset ke halaman 1 ketika limit diubah
    }

    function searchBooks() {
        var input = document.getElementById('search').value.toLowerCase();
        var rows = document.querySelectorAll('#resultsTableBody tr');
        
        rows.forEach(function(row) {
            var title = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            if (title.includes(input)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
 

</body>
</html> 