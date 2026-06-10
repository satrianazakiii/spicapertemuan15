<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['tipe_user'] != 'customer'){
    header("Location: login.php");
    exit;
}
include 'koneksi.php';
global $koneksi;
?>
<!DOCTYPE html>
<html>
<head><title>Customer Dashboard</title></head>
<body>
    <?php include 'navbar.php'; ?>
    <h2>Selamat datang, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
    <h3>Daftar Barang</h3>
    <table border="1">
        <tr><th>ID</th><th>Nama Barang</th><th>Jenis</th><th>Harga</th><th>Stok</th></tr>
        <?php
        // 1. SESUAI SQL: Memakai tb_barang dan digabung (JOIN) ke tb_jenis melalui kode_jenis
        $query = "SELECT b.*, j.jenis FROM tb_barang b LEFT JOIN tb_jenis j ON b.kode_jenis = j.kode_jenis";
        
        $result = mysqli_query($koneksi, $query);
        while($row = mysqli_fetch_assoc($result)){
            echo "<tr>";
            // 2. SESUAI SQL: Kolom primary key di tb_barang adalah kd_barang
            echo "<td>" . $row['kd_barang'] . "</td>"; 
            
            echo "<td>" . htmlspecialchars($row['nama_barang']) . "</td>";
            
            // 3. SESUAI SQL: Kolom nama jenis di tabel tb_jenis bernama 'jenis'
            echo "<td>" . htmlspecialchars($row['jenis']) . "</td>";
            
            // 4. SESUAI SQL: Kolom harga jual di tb_barang bernama harga_jual
            echo "<td>Rp " . number_format($row['harga_jual'],0,',','.') . "</td>"; 
            
            echo "<td>" . $row['stok'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>