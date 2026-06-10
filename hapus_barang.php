<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['tipe_user'] != 'admin') header("Location: ../../frontend/login.php");
include '../../frontend/koneksi.php';
global $conn;
$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM barang WHERE id_barang='$id'");
header("Location: data_barang.php");
?>