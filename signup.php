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

<body class="app app-signup p-0">    	
    <div class="row g-0 app-auth-wrapper">
	    <div class="col-12 col-md-7 col-lg-6 auth-main-col text-center p-5">
		    <div class="d-flex flex-column align-content-end">
			    <div class="app-auth-body mx-auto">	
				    <div class="app-auth-branding mb-4"><a class="app-logo"><img class="logo-icon me-2" src="img/buk.svg" alt="logo"></a></div>
					<h2 class="auth-heading text-center mb-4">Register ke Perpustakaan</h2>					
	
					<div class="auth-form-container text-start mx-auto">
					<form action="" method="POST" class="auth-form auth-signup-form">         
    <div class="name mb-3">
        <label class="sr-only" for="signup-name">Nama Lengkap</label>
        <input id="signup-name" name="nama_lengkap" type="text" class="form-control signup-name" placeholder="Nama Lengkap" required="required" >
    </div>
    <div class="alamat mb-3">
        <label class="sr-only" for="signup-alamat">Alamat</label>
        <input id="signup-alamat" name="alamat" type="text" class="form-control signup-alamat" placeholder="Alamat" required="required" >
    </div>
    <div class="username mb-3">
        <label class="sr-only" for="signup-username">Username</label>
        <input id="signup-username" name="username" type="username" class="form-control signup-username" placeholder="Username" required="required" >
    </div>
    <div class="email mb-3">
        <label class="sr-only" for="signup-email">Email</label>
        <input id="signup-email" name="email" type="email" class="form-control signup-email" placeholder="Email" required="required" >
    </div>
    <div class="password mb-3">
        <label class="sr-only" for="signup-password">Password</label>
        <input id="signup-password" name="password" type="password" class="form-control signup-password" placeholder="Buat password" required="required" >
    </div>
    <div class="extra mb-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="RememberPassword">
            <label class="form-check-label" for="RememberPassword">
            I agree to <a href="#" class="app-link">Terms of Service</a> and <a href="#" class="app-link">Privacy Policy</a>.
            </label>
        </div>
    </div><!--//extra-->
    
    <div class="text-center">
        <button type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto" value="submit" name="submit">Daftar</button>
    </div>
</form><!--//auth-form-->

<?php
require('koneksi.php');

if (isset($_REQUEST['nama_lengkap'])) {
    $nama_lengkap = stripslashes($_REQUEST['nama_lengkap']);
    $nama_lengkap = mysqli_real_escape_string($koneksi, $nama_lengkap);
    $alamat = stripslashes($_REQUEST['alamat']);
    $alamat = mysqli_real_escape_string($koneksi, $alamat);
    $email = stripslashes($_REQUEST['email']);
    $email = mysqli_real_escape_string($koneksi, $email);
    $username = stripslashes($_REQUEST['username']);
    $username = mysqli_real_escape_string($koneksi, $username);
    $password = stripslashes($_REQUEST['password']);
    $password = mysqli_real_escape_string($koneksi, $password);

    
    $sql = "SELECT id_user FROM user WHERE email = ? OR username = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "User sudah terdaftar. Silakan gunakan email atau username lain.";
    } else {
        
        $query = "INSERT INTO `user` (nama_lengkap, alamat, email, username, password, role) 
                  VALUES ('$nama_lengkap', '$alamat', '$email', '$username', '".md5($password)."', 'user')";
        $result = mysqli_query($koneksi, $query);
        if ($result) {
            echo "<script>
		document.addEventListener('DOMContentLoaded', function() {
			var customAlert = document.getElementById('customAlert2');
			customAlert.style.display = 'block';
			setTimeout(function() {
				window.location.href = 'daftarbuku.php';
			},3000);
		});
	</script>";
        }
    }
}
?>


                        <div id="myModal" class="modal">
                            <div class="modal-content">
                                <span class="close">&times;</span>
                                <p id="modalMessage">User sudah terdaftar. Silakan gunakan email atau username lain.</p>
                            </div>
                        </div>

                        
						
						<div class="auth-option text-center pt-5">Sudah punya akun? <a class="text-link" href="login.php" >Masuk</a></div>
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
				</div>
		    </div><!--//auth-background-overlay-->
	    </div><!--//auth-background-col-->
    
    </div><!--//row-->
	<div id="customAlert" class="custom-alert">
    <span class="alert-text">Perpustakaan: Anda Berhasil Sign up</span>
</div>

<script src="assets/plugins/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>  
    
    <!-- Page Specific JS -->
    <script src="assets/js/app.js"></script> 

</body>

</html> 

