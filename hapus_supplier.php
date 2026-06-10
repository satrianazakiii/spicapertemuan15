<?php
global $koneksi;
session_start();
include("../../frontend/koneksi.php");
if(!isset($_SESSION['username'])){
    echo "<script>alert('Anda harus login dulu!');window.location='../../frontend/login.php';</script>";
    exit;
}

if(!isset($_GET['id_supplier']) || empty($_GET['id_supplier'])){
    echo "<script>alert('ID Supplier tidak ditemukan!');window.location='data_supplier.php';</script>";
    exit;
}

$id_supplier = $_GET['id_supplier'];

$hapus = mysqli_query($koneksi, "DELETE FROM tb_supplier WHERE id_supplier='$id_supplier'");

if($hapus){
    echo "<script>alert('Data berhasil dihapus!');window.location='data_supplier.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus data!');window.location='data_supplier.php';</script>";
}
?>