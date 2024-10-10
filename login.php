<?php
require('koneksi.php'); // Koneksi ke database
session_start(); // Memulai session

// Cek apakah pengguna sudah login
if (isset($_SESSION['id_user'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin/index.php");
    } else {
        header("Location: user/index.php");
    }
    exit();
}

// Jika form disubmit, proses login
if (isset($_POST['submit'])) {
    // Mengambil dan mengamankan input
    $username = stripslashes($_POST['username']);
    $username = mysqli_real_escape_string($koneksi, $username);
    
    $password = stripslashes($_POST['password']);
    $password = mysqli_real_escape_string($koneksi, $password);
    
    // Mencari user berdasarkan username
    $query = "SELECT * FROM user WHERE username = ?";
    $stmt = $koneksi->prepare($query);
    
    if ($stmt === false) {
        die('Error prepare statement: ' . htmlspecialchars($koneksi->error));
    }
    
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Cek apakah ada user dengan username tersebut
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Memverifikasi password
        if (md5($password) === $user['password']) {
            // Simpan data pengguna ke session
            $_SESSION['id_user'] = $user['id_user']; // Menyimpan ID pengguna
            $_SESSION['username'] = $user['username']; // Menyimpan username
            $_SESSION['role'] = $user['role']; // Menyimpan role pengguna

            // Cek apakah ada halaman redirect yang tersimpan di session
            if (isset($_SESSION['redirect_url'])) {
                $redirect_url = $_SESSION['redirect_url'];
                unset($_SESSION['redirect_url']); // Hapus session setelah digunakan
                header("Location: $redirect_url");
            } else {
                // Redirect berdasarkan role pengguna
                if ($user['role'] == 'admin') {
                    header("Location: admin/index.php"); // Redirect ke dashboard admin
                } else {
                    header("Location: user/index.php"); // Redirect ke dashboard user
                }
            }
            exit(); // Menghentikan eksekusi skrip setelah redirect
        } else {
            // Jika password salah
            echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            var customAlert = document.getElementById('customAlert');
            customAlert.style.display = 'block';
            setTimeout(function() {
                window.location.href = 'login.php';
            }, 3000);
        });
        </script>";
        }
    } else {
        // Jika user tidak ditemukan
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            var customAlert2 = document.getElementById('customAlert2');
            customAlert2.style.display = 'block';
            setTimeout(function() {
                window.location.href = 'login.php';
            }, 3000);
        });
        </script>";
    }
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
    <link rel="shortcut icon" href="img/buk.svg"> 
    
    <!-- FontAwesome JS-->
    <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>
    
    <!-- App CSS -->  
    <link id="theme-style" rel="stylesheet" href="assets/css/portal.css">

</head> 

<body class="app app-login p-0">    	
    <div class="row g-0 app-auth-wrapper">
	    <div class="col-12 col-md-7 col-lg-6 auth-main-col text-center p-5">
		    <div class="d-flex flex-column align-content-end">
			    <div class="app-auth-body mx-auto">	
				    <div class="app-auth-branding mb-4"><a class="app-logo" ><img class="logo-icon me-2" src="img/buk.svg" alt="logo"></a></div>
					<h2 class="auth-heading text-center mb-5">Log in ke Perpustakaan</h2>
			        <div class="auth-form-container text-start">
						<form class="auth-form login-form" action="" method="POST" name="login">          
    <div class="username mb-3">
        <label class="sr-only" for="signin-username">Username</label>
        <input id="signin-username" name="username" type="username" class="form-control signin-username" placeholder="Username" required="required">
    </div><!--//form-group-->
    <div class="password mb-3">
        <label class="sr-only" for="signin-password">Password</label>
        <input id="signin-password" name="password" type="password" class="form-control signin-password" placeholder="Password" required="required">
        <div class="extra mt-3 row justify-content-between">
            <div class="col-6">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="RememberPassword">
                    <label class="form-check-label" for="RememberPassword">
                    Remember me
                    </label>
                </div>
            </div><!--//col-6-->
            <div class="col-6">
                <div class="forgot-password text-end">
                    <a href="reset-password.html">Forgot password?</a>
                </div>
            </div><!--//col-6-->
        </div><!--//extra-->
    </div><!--//form-group-->
    <div class="text-center">
        <button type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto" value="login" name="submit">Log In</button>
    </div>
</form>




						
						<div class="auth-option text-center pt-5">Tidak punya akun? Daftar <a class="text-link" href="user/signup.php" >di sini</a>.</div>
					</div><!--//auth-form-container-->	

			    </div><!--//auth-body-->
		    
			    <footer class="app-auth-footer">
				    <div class="container text-center py-3">
				         <!--/* This template is free as long as you keep the footer attribution link. If you'd like to use the template without the attribution link, you can buy the commercial license via our website: themes.3rdwavemedia.com Thank you for your support. :) */-->
			        <small class="copyright">Designed with <span class="sr-only">love</span><i class="fas fa-heart" style="color: #fb866a;"></i> by <a class="app-link" href="http://themes.3rdwavemedia.com" target="_blank">Xiaoying Riley</a> for developers</small>
				       
				    </div>
			    </footer><!--//app-auth-footer-->	
		    </div><!--//flex-column-->   
	    </div><!--//auth-main-col-->
	    <div class="col-12 col-md-5 col-lg-6 h-100 auth-background-col">
		    <div class="auth-background-holder">
		    </div>
		    <div class="auth-background-mask"></div>
		    <div class="auth-background-overlay p-3 p-lg-5">
			    <div class="d-flex flex-column align-content-end h-100">
				    <div class="h-100"></div>
				    <div class="overlay-content p-3 p-lg-4 rounded">
					    <h5 class="mb-3 overlay-title">PU</h5>
					    <div>Permis satu: Jika hujan saya tidak sekolah </br>Permis dua: Saya tidak sekolah...?</div>
				    </div>
				</div>
		    </div><!--//auth-background-overlay-->
	    </div><!--//auth-background-col-->
    
    </div><!--//row-->

	<div id="customAlert" class="custom-alert">
        <span class="alert-text">Perpustakaan: Username atau password salah.</span>
    </div>

    <div id="customAlert2" class="custom-alert">
        <span class="alert-text">Perpustakaan: Username tidak ditemukan.</span>
    </div>

<style>
    .custom-alert {
        display: none; /* Sembunyikan alert secara default */
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        background-color: red; /* Warna hijau untuk notifikasi berhasil */
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



</body>
</html> 

