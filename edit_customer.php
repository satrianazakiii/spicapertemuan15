<?php
global $koneksi;
session_start();
include("../../frontend/koneksi.php");
if(!isset($_SESSION['username'])){
    echo "<script>alert('Anda harus login dulu!');window.location='../../login.php';</script>";
    exit;
}

if(!isset($_GET['id_customer']) || empty($_GET['id_customer'])){
    echo "<script>alert('ID Customer tidak ditemukan!');window.location='data_customer.php';</script>";
    exit;
}

$id_customer = $_GET['id_customer'];
$row = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_customer WHERE id_customer='$id_customer'"));

if(!$row){
    echo "<script>alert('Data tidak ditemukan!');window.location='data_customer.php';</script>";
    exit;
}

if(isset($_POST['submit'])){
    $nama_customer    = $_POST['nama_customer'];
    $jenis_kelamin    = $_POST['jenis_kelamin'];
    $alamat_customer  = $_POST['alamat_customer'];
    $telepon_customer = $_POST['telepon_customer'];
    $email_customer   = $_POST['email_customer'];

    if(!empty($_POST['pass_customer'])){
        $pass_customer = password_hash($_POST['pass_customer'], PASSWORD_DEFAULT);
        mysqli_query($koneksi, "UPDATE tb_customer SET 
            nama_customer='$nama_customer', jenis_kelamin='$jenis_kelamin',
            alamat_customer='$alamat_customer', telepon_customer='$telepon_customer',
            email_customer='$email_customer', pass_customer='$pass_customer'
            WHERE id_customer='$id_customer'");
    } else {
        mysqli_query($koneksi, "UPDATE tb_customer SET 
            nama_customer='$nama_customer', jenis_kelamin='$jenis_kelamin',
            alamat_customer='$alamat_customer', telepon_customer='$telepon_customer',
            email_customer='$email_customer'
            WHERE id_customer='$id_customer'");
    }

    echo "<script>alert('Data berhasil diupdate!');window.location='data_customer.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Edit Customer</title>
  <link rel="stylesheet" href="../../assets/spica/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../../assets/spica/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../../assets/spica/css/style.css">
  <link rel="shortcut icon" href="../../assets/spica/images/favicon.png" />
</head>
<body>
<div class="container-scroller d-flex">

  <nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
      <li class="nav-item sidebar-category"><p>Navigation</p><span></span></li>
      <li class="nav-item">
        <a class="nav-link" href="index_admin.php">
          <i class="mdi mdi-view-quilt menu-icon"></i>
          <span class="menu-title">Dashboard</span>
        </a>
      </li>
      <li class="nav-item sidebar-category"><p>Components</p><span></span></li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="true">
          <i class="mdi mdi-view-headline menu-icon"></i>
          <span class="menu-title">Kelola Data</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse show" id="ui-basic">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"><a class="nav-link" href="data_barang.php">Data Barang</a></li>
            <li class="nav-item"><a class="nav-link active" href="data_customer.php">Data Customer</a></li>
            <li class="nav-item"><a class="nav-link" href="data_supplier.php">Data Supplier</a></li>
          </ul>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false">
          <i class="mdi mdi-view-headline menu-icon"></i>
          <span class="menu-title">Kelola Transaksi</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="auth">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"><a class="nav-link" href="transaksi_pembelian.php">Transaksi Pembelian</a></li>
            <li class="nav-item"><a class="nav-link" href="transaksi_penjualan.php">Transaksi Penjualan</a></li>
          </ul>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#auth2" aria-expanded="false">
          <i class="mdi mdi-view-headline menu-icon"></i>
          <span class="menu-title">Kelola Laporan</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="auth2">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"><a class="nav-link" href="#">Laporan Transaksi Pembelian</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Laporan Transaksi Penjualan</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Laporan Data Customer</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Laporan Data Supplier</a></li>
          </ul>
        </div>
      </li>
    </ul>
  </nav>

  <div class="container-fluid page-body-wrapper">
    <nav class="navbar col-lg-12 col-12 px-0 py-0 py-lg-4 d-flex flex-row">
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="mdi mdi-menu"></span>
        </button>
        <div class="navbar-brand-wrapper">
          <a class="navbar-brand brand-logo" href="index_admin.php">
            <img src="../../assets/spica/images/logo.svg" alt="logo"/>
          </a>
          <a class="navbar-brand brand-logo-mini" href="index_admin.php">
            <img src="../../assets/spica/images/logo-mini.svg" alt="logo"/>
          </a>
        </div>
        <h4 class="font-weight-bold mb-0 d-none d-md-block mt-1">Welcome <?php echo $_SESSION['username']; ?></h4>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
      <div class="navbar-menu-wrapper navbar-search-wrapper d-none d-lg-flex align-items-center">
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
              <img src="../../assets/spica/images/faces/face5.jpg" alt="profile"/>
              <span class="nav-profile-name"><?php echo $_SESSION['username']; ?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item" href="../../frontend/logout.php">
                  <i class="mdi mdi-logout text-primary"></i>
                  Logout
              </a>
            </div>
          </li>
        </ul>
      </div>
    </nav>

    <div class="main-panel">
      <div class="content-wrapper">
        <div class="row">
          <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h4 class="card-title mb-0">Edit Customer</h4>
                  <a href="data_customer.php" class="btn btn-danger btn-sm">Kembali</a>
                </div>
                <form method="POST">
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label">ID Customer</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control bg-light" value="<?php echo $row['id_customer']; ?>" readonly>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Nama Customer</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" name="nama_customer" value="<?php echo $row['nama_customer']; ?>" required>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Jenis Kelamin</label>
                    <div class="col-sm-8">
                      <select class="form-control" name="jenis_kelamin" required>
                        <option value="Laki-laki" <?php echo ($row['jenis_kelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                        <option value="Perempuan" <?php echo ($row['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Alamat</label>
                    <div class="col-sm-8">
                      <textarea class="form-control" name="alamat_customer" rows="3" required><?php echo $row['alamat_customer']; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Telepon</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" name="telepon_customer" value="<?php echo $row['telepon_customer']; ?>" required>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Email</label>
                    <div class="col-sm-8">
                      <input type="email" class="form-control" name="email_customer" value="<?php echo $row['email_customer']; ?>" required>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Password Baru</label>
                    <div class="col-sm-8">
                      <input type="password" class="form-control" name="pass_customer" placeholder="Kosongkan jika tidak ingin mengubah password">
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-sm-8 offset-sm-4">
                      <button type="submit" name="submit" class="btn btn-success me-2">Update</button>
                      <a href="data_customer.php" class="btn btn-danger">Batal</a>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="../../assets/spica/vendors/js/vendor.bundle.base.js"></script>
<script src="../../assets/spica/vendors/chart.js/Chart.min.js"></script>
<script src="../../assets/spica/js/jquery.cookie.js"></script>
<script src="../../assets/spica/js/off-canvas.js"></script>
<script src="../../assets/spica/js/hoverable-collapse.js"></script>
<script src="../../assets/spica/js/template.js"></script>
</body>
</html>