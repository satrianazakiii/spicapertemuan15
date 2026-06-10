<?php
global $koneksi;
session_start();
include("../../frontend/koneksi.php");
if(!isset($_SESSION['username'])){
    echo "<script>alert('Anda harus login dulu!');window.location='../../login.php';</script>";
    exit;
}

// Generate No Faktur otomatis untuk Penjualan (SELL)
$no_penjualan = "SELL - " . date("Ymd") . rand(100, 999);

// Ambil data customer (pembeli)
$customer = mysqli_query($koneksi, "SELECT * FROM tb_customer");

// PROSES SUBMIT
if(isset($_POST['submit'])){
    $no_penjualan  = $_POST['no_penjualan'];
    $tgl           = $_POST['tanggal_penjualan'];
    $id_customer   = $_POST['id_customer'];

    // Cek apakah no_penjualan sudah ada
    $cek = mysqli_query($koneksi, "SELECT * FROM tb_penjualan WHERE no_penjualan='$no_penjualan'");
    if(mysqli_num_rows($cek) > 0){
        echo "<script>alert('Nomor faktur sudah ada!');</script>";
    } else {
        $insert = mysqli_query($koneksi, "INSERT INTO tb_penjualan (no_penjualan, tanggal_penjualan, id_customer, total_barangall, total_hargaall) 
                                       VALUES ('$no_penjualan', '$tgl', '$id_customer', 0, 0)");
        if($insert){
            echo "<script>alert('Transaksi penjualan berhasil dibuat!');
                  window.location='penjualan_barang.php?no_penjualan=".urlencode($no_penjualan)."&action=pilih_barang';</script>";
        } else {
            echo "<script>alert('Gagal menyimpan!');</script>";
        }
    }
}

// PROSES HAPUS
if(isset($_GET['hapus'])){
    $no = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM detail_penjualan WHERE no_penjualan='$no'");
    mysqli_query($koneksi, "DELETE FROM tb_penjualan WHERE no_penjualan='$no'");
    echo "<script>alert('Data berhasil dihapus!');window.location='transaksi_penjualan.php';</script>";
}

// Ambil semua data transaksi penjualan beserta nama customernya
$data = mysqli_query($koneksi, "SELECT tp.*, tc.nama_customer 
                              FROM tb_penjualan tp 
                              LEFT JOIN tb_customer tc ON tp.id_customer = tc.id_customer 
                              ORDER BY tp.no_penjualan DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Transaksi Penjualan</title>
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
        <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false">
          <i class="mdi mdi-view-headline menu-icon"></i>
          <span class="menu-title">Kelola Data</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="ui-basic">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"><a class="nav-link" href="data_barang.php">Data Barang</a></li>
            <li class="nav-item"><a class="nav-link" href="data_customer.php">Data Customer</a></li>
          </ul>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="true">
          <i class="mdi mdi-view-headline menu-icon"></i>
          <span class="menu-title">Kelola Transaksi</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse show" id="auth">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"><a class="nav-link" href="transaksi_pembelian.php">Transaksi Pembelian</a></li>
            <li class="nav-item"><a class="nav-link active" href="transaksi_penjualan.php">Transaksi Penjualan</a></li>
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
        <ul class="navbar-nav navbar-nav-right"></ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
      <div class="navbar-menu-wrapper navbar-search-wrapper d-none d-lg-flex align-items-center">
        <ul class="navbar-nav mr-lg-2">
          <li class="nav-item nav-search d-none d-lg-block">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Search Here...">
            </div>
          </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
              <img src="../../assets/spica/images/faces/face5.jpg" alt="profile"/>
              <span class="nav-profile-name"><?php echo $_SESSION['username']; ?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item" href="../../login.php">
                <i class="mdi mdi-logout text-primary"></i> Logout
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
                <h4 class="card-title">Form Transaksi Penjualan (Barang Keluar)</h4>
                <form method="POST">
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Nomor Faktur Penjualan</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" name="no_penjualan" value="<?php echo $no_penjualan; ?>" readonly>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Tanggal Penjualan</label>
                    <div class="col-sm-8">
                      <input type="date" class="form-control" name="tanggal_penjualan" required>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Customer</label>
                    <div class="col-sm-8">
                      <select class="form-control" name="id_customer" required>
                        <option value="">Pilih Customer</option>
                        <?php while($c = mysqli_fetch_assoc($customer)): ?>
                          <option value="<?php echo $c['id_customer']; ?>"><?php echo $c['nama_customer']; ?></option>
                        <?php endwhile; ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-sm-8 offset-sm-4">
                      <button type="submit" name="submit" class="btn btn-success me-2">Submit</button>
                      <button type="reset" class="btn btn-warning me-2">Reset</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Data Transaksi Penjualan (Barang Keluar)</h4>
                <div class="table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>No Faktur Penjualan</th>
                        <th>Tanggal Penjualan</th>
                        <th>ID Customer</th>
                        <th>Nama Customer</th>
                        <th>Total Barang</th>
                        <th>Total Harga</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $no = 1;
                      while($row = mysqli_fetch_assoc($data)):
                      ?>
                      <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $row['no_penjualan']; ?></td>
                        <td><?php echo $row['tanggal_penjualan']; ?></td>
                        <td><?php echo $row['id_customer']; ?></td>
                        <td><?php echo $row['nama_customer']; ?></td>
                        <td><?php echo $row['total_barangall']; ?></td>
                        <td>Rp <?php echo number_format($row['total_hargaall'], 0, ',', '.'); ?>,00</td>
                        <td>
                        <a href="penjualan_barang.php?no_penjualan=<?php echo urlencode($row['no_penjualan']); ?>&action=pilih_barang"
                             class="btn btn-info btn-sm">Detail Transaksi</a>
                          <a href="?hapus=<?php echo urlencode($row['no_penjualan']); ?>" 
                             class="btn btn-danger btn-sm"
                             onclick="return confirm('Yakin hapus transaksi penjualan ini?')">Hapus</a>
                        </td>
                      </tr>
                      <?php endwhile; ?>
                    </tbody>
                  </table>
                </div>
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