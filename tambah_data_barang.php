<?php
global $koneksi;
session_start();
include("../../frontend/koneksi.php");
if(!isset($_SESSION['username'])){
    echo "<script>alert('Anda harus login dulu!');window.location='../../login.php';</script>";
    exit;
}

$jenis = mysqli_query($koneksi, "SELECT * FROM tb_jenis");

// Generate kode barang otomatis
$last = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT kd_barang FROM tb_barang ORDER BY kd_barang DESC LIMIT 1"));
if($last){
    $num = (int)substr($last['kd_barang'], 3) + 1;
    $kd_barang = "BRG" . str_pad($num, 2, "0", STR_PAD_LEFT);
} else {
    $kd_barang = "BRG01";
}

if(isset($_POST['submit'])){
    $kd_barang   = $_POST['kd_barang'];
    $kode_jenis  = $_POST['kode_jenis'];
    $nama_barang = $_POST['nama_barang'];
    $stok        = $_POST['stok'];
    $harga_beli  = $_POST['harga_beli'];
    $harga_jual  = $_POST['harga_jual'];
    $gambar      = '';

    if(!empty($_FILES['gambar_produk']['name'])){
        $nama_file  = time() . "_" . $_FILES['gambar_produk']['name'];
        $target     = "../../gambar/" . $nama_file;
        move_uploaded_file($_FILES['gambar_produk']['tmp_name'], $target);
        $gambar = $nama_file;
    }

    $cek = mysqli_query($koneksi, "SELECT * FROM tb_barang WHERE kd_barang='$kd_barang'");
    if(mysqli_num_rows($cek) > 0){
        echo "<script>alert('Kode barang sudah ada!');</script>";
    } else {
        mysqli_query($koneksi, "INSERT INTO tb_barang (kd_barang, kode_jenis, nama_barang, stok, harga_beli, harga_jual, gambar_produk)
                              VALUES ('$kd_barang','$kode_jenis','$nama_barang',$stok,$harga_beli,$harga_jual,'$gambar')");
        echo "<script>alert('Data berhasil ditambahkan!');window.location='data_barang.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Tambah Data Barang</title>
  <link rel="stylesheet" href="../../assets/spica/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../../assets/spica/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../../assets/spica/css/style.css">
  <link rel="shortcut icon" href="../../assets/spica/images/favicon.png" />
</head>
<body>
<div class="container-scroller d-flex">

  <!-- SIDEBAR -->
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
            <li class="nav-item"><a class="nav-link active" href="data_barang.php">Data Barang</a></li>
            <li class="nav-item"><a class="nav-link" href="data_customer.php">Data Customer</a></li>
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
    <!-- NAVBAR -->
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
                  <h4 class="card-title mb-0">Tambah Data Barang</h4>
                  <a href="data_barang.php" class="btn btn-danger btn-sm">Kembali</a>
                </div>
                <form method="POST" enctype="multipart/form-data">
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Kode Barang</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control bg-light" name="kd_barang" value="<?php echo $kd_barang; ?>" readonly>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Jenis Barang</label>
                    <div class="col-sm-8">
                      <select class="form-control" name="kode_jenis" required>
                        <option value="">Pilih Jenis</option>
                        <?php while($j = mysqli_fetch_assoc($jenis)): ?>
                          <option value="<?php echo $j['kode_jenis']; ?>"><?php echo $j['jenis']; ?></option>
                        <?php endwhile; ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Nama Barang</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" name="nama_barang" placeholder="Nama Barang" required>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Stok</label>
                    <div class="col-sm-8">
                      <input type="number" class="form-control" name="stok" placeholder="Stok" min="0" required>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Harga Beli</label>
                    <div class="col-sm-8">
                      <input type="number" class="form-control" name="harga_beli" placeholder="Harga Beli" min="0" required>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Harga Jual</label>
                    <div class="col-sm-8">
                      <input type="number" class="form-control" name="harga_jual" placeholder="Harga Jual" min="0" required>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Gambar Produk</label>
                    <div class="col-sm-8">
                      <input type="file" class="form-control" name="gambar_produk" accept="image/*">
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-sm-8 offset-sm-4">
                      <button type="submit" name="submit" class="btn btn-success me-2">Simpan</button>
                      <button type="reset" class="btn btn-warning me-2">Reset</button>
                      <a href="data_barang.php" class="btn btn-danger">Batal</a>
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