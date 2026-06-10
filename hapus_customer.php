<?php
global $koneksi;
session_start();
include("../../frontend/koneksi.php");
if(!isset($_SESSION['username'])){
    echo "<script>alert('Anda harus login dulu!');window.location='../../frontend/login.php';</script>";
    exit;
}

if(!isset($_GET['id_customer']) || empty($_GET['id_customer'])){
    echo "<script>alert('ID Customer tidak ditemukan!');window.location='data_customer.php';</script>";
    exit;
}

$id_customer = $_GET['id_customer'];

$hapus = mysqli_query($koneksi, "DELETE FROM tb_customer WHERE id_customer='$id_customer'");

if($hapus){
    echo "<script>alert('Data berhasil dihapus!');window.location='data_customer.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus data!');window.location='data_customer.php';</script>";
}
?>