<?php
session_start();
include("../../frontend/koneksi.php");   // path ke koneksi di folder frontend
if(!isset($_SESSION['username'])){
    echo "<script>alert('Anda harus login dulu!');window.location='../../frontend/login.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Halaman <?php echo $_SESSION['tipe_user']; ?></title>
  <link rel="stylesheet" href="../../assets/spica/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../../assets/spica/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../../assets/spica/css/style.css">
  <link rel="shortcut icon" href="../../assets/spica/images/favicon.png" />
</head>
<body>
  <div class="container-scroller d-flex">

    <!-- ===== SIDEBAR ===== -->
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
      <ul class="nav">
        <li class="nav-item sidebar-category">
          <p>Navigation</p>
          <span></span>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index_admin.php">
            <i class="mdi mdi-view-quilt menu-icon"></i>
            <span class="menu-title">Dashboard</span>
          </a>
        </li>
        <li class="nav-item sidebar-category">
          <p>Components</p>
          <span></span>
        </li>

        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
            <i class="mdi mdi-view-headline menu-icon"></i>
            <span class="menu-title">Kelola Data</span>
            <i class="menu-arrow"></i>
          </a>
          <div class="collapse" id="ui-basic">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item"><a class="nav-link" href="data_barang.php">Data Barang</a></li>
              <li class="nav-item"><a class="nav-link" href="data_customer.php">Data Customer</a></li>
              <li class="nav-item"><a class="nav-link" href="data_supplier.php">Data Supplier</a></li>
            </ul>
          </div>
        </li>

        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
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
          <a class="nav-link" data-bs-toggle="collapse" href="#auth2" aria-expanded="false" aria-controls="auth2">
            <i class="mdi mdi-view-headline menu-icon"></i>
            <span class="menu-title">Kelola Laporan</span>
            <i class="menu-arrow"></i>
          </a>
          <div class="collapse" id="auth2">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item"><a class="nav-link" href="laporan_pembelian.php">Laporan Transaksi Pembelian</a></li>
              <li class="nav-item"><a class="nav-link" href="laporan_penjualan.php">Laporan Transaksi Penjualan</a></li>
              <li class="nav-item"><a class="nav-link" href="laporan_customer.php">Laporan Data Customer</a></li>
              <li class="nav-item"><a class="nav-link" href="laporan_supplier.php">Laporan Data Supplier</a></li>
            </ul>
          </div>
        </li>

      </ul>
    </nav>
    <!-- ===== END SIDEBAR ===== -->

    <!-- ===== PAGE BODY WRAPPER (NAVBAR + MAIN PANEL) ===== -->
    <div class="container-fluid page-body-wrapper">

      <!-- ===== NAVBAR ===== -->
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
          <h4 class="font-weight-bold mb-0 d-none d-md-block mt-1">
            Welcome <?php echo $_SESSION['username']; ?>
          </h4>
          <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item dropdown me-1">
              <a class="nav-link count-indicator dropdown-toggle d-flex justify-content-center align-items-center"
                 id="messageDropdown" href="#" data-bs-toggle="dropdown">
                <i class="mdi mdi-calendar mx-0"></i>
                <span class="count bg-info">0</span>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">
                <p class="mb-0 font-weight-normal float-left dropdown-header">Messages</p>
                <a class="dropdown-item">
                  <div class="preview-item-content">
                    <p class="font-weight-light small-text text-muted mb-0">Tidak ada pesan</p>
                  </div>
                </a>
              </div>
            </li>
            <li class="nav-item dropdown me-2">
              <a class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center"
                 id="notificationDropdown" href="#" data-bs-toggle="dropdown">
                <i class="mdi mdi-bell mx-0"></i>
                <span class="count bg-danger">0</span>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
                <a class="dropdown-item">
                  <div class="preview-item-content">
                    <p class="font-weight-light small-text mb-0 text-muted">Tidak ada notifikasi</p>
                  </div>
                </a>
              </div>
            </li>
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
          </button>
        </div>

        <div class="navbar-menu-wrapper navbar-search-wrapper d-none d-lg-flex align-items-center">
          <ul class="navbar-nav mr-lg-2">
            <li class="nav-item nav-search d-none d-lg-block">
              <div class="input-group">
                <input type="text" class="form-control" placeholder="Search Here..." aria-label="search">
              </div>
            </li>
          </ul>
          <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
              <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
                <img src="../../assets/spica/images/faces/face5.jpg" alt="profile"/>
                <span class="nav-profile-name"><?php echo $_SESSION['tipe_user']; ?></span>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                <!-- Perbaiki link logout ke frontend/logout.php -->
                <a class="dropdown-item" href="../../frontend/logout.php">
                  <i class="mdi mdi-logout text-primary"></i>
                  Logout
                </a>
              </div>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link icon-link"><i class="mdi mdi-plus-circle-outline"></i></a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link icon-link"><i class="mdi mdi-web"></i></a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link icon-link"><i class="mdi mdi-clock-outline"></i></a>
            </li>
          </ul>
        </div>
      </nav>
      <!-- ===== END NAVBAR ===== -->

      <!-- ===== MAIN PANEL ===== -->
      <div class="main-panel">
        <?php include('main_panel.php'); ?>
      </div>
      <!-- ===== END MAIN PANEL ===== -->

    </div>
    <!-- ===== END PAGE BODY WRAPPER ===== -->

  </div>
  <!-- container-scroller -->

  <script src="../../assets/spica/vendors/js/vendor.bundle.base.js"></script>
  <script src="../../assets/spica/vendors/chart.js/Chart.min.js"></script>
  <script src="../../assets/spica/js/jquery.cookie.js" type="text/javascript"></script>
  <script src="../../assets/spica/js/off-canvas.js"></script>
  <script src="../../assets/spica/js/hoverable-collapse.js"></script>
  <script src="../../assets/spica/js/template.js"></script>
  <script src="../../assets/spica/js/dashboard.js"></script>
</body>
</html>