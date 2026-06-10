<?php
session_start();
include 'koneksi.php';
global $koneksi;

if(isset($_POST['login'])){
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = md5($_POST['password']);
    
    $query = "SELECT * FROM user WHERE username='$username' AND password='$password'";
    $result = mysqli_query($koneksi, $query);
    
    if(mysqli_num_rows($result) == 1){
        $row = mysqli_fetch_assoc($result);
        
        // Menyimpan data ke dalam session
        $_SESSION['user_id'] = $row['id_user'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['tipe_user'] = $row['tipe_user'];
        
        // Pengecekan tipe_user untuk mengarahkan ke halaman yang sesuai
        if($row['tipe_user'] == 'administrator'){
            // Sesuaikan path file-nya jika ada di dalam folder, contoh: header("Location: ../backend/admin/index_admin.php");
            header("Location: ../backend/admin/index_admin.php");
            exit();
        } else if($row['tipe_user'] == 'customer'){
            header("Location: dashboard.php");
            exit();
        } else if($row['tipe_user'] == 'suplier'){
            header("Location: dashboard.php");
            exit();
        } else {
            // Halaman default jika tipe_user tidak dikenali (opsional)
            header("Location: index.php");
            exit();
        }
        
    } else {
        echo "<script>alert('Username atau password salah!'); window.location='login.php';</script>";
    }
}
if(isset($_POST['register'])){
    
    // Tangkap data dari form
    // Menggunakan mysqli_real_escape_string untuk mencegah SQL Injection
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $tipe_user = mysqli_real_escape_string($koneksi, $_POST['tipe_user']);
    
    // Enkripsi password menggunakan MD5 agar cocok dengan sistem login Anda
    $password = md5($_POST['password']);
    
    // Query untuk memasukkan data ke tabel user
    $query = "INSERT INTO user (username, password, tipe_user) VALUES ('$username', '$password', '$tipe_user')";
    
    // Eksekusi query
    $insert = mysqli_query($koneksi, $query);
    
    // Cek apakah proses simpan berhasil
    if($insert){
        // Jika berhasil, tampilkan pesan sukses dan arahkan ke login.php
        echo "<script>
                alert('Registrasi berhasil! Silakan login.'); 
                window.location='login.php';
              </script>";
        exit();
    } else {
        // Jika gagal, tampilkan pesan error dan kembalikan ke halaman register
        echo "<script>
                alert('Registrasi gagal! Terjadi kesalahan pada database.'); 
                window.history.back();
              </script>";
    }
} else {
    // Jika file ini diakses langsung tanpa lewat form, tendang kembali ke form
    header("Location: register.php"); // Sesuaikan nama file form register Anda
    exit();
}
?>