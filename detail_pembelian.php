<?php
global $koneksi;
session_start();
include("../../frontend/koneksi.php");
if(!isset($_SESSION['username'])){
    echo "<script>alert('Anda harus login dulu!');window.location='../../login.php';</script>";
    exit;
}

$no_pembelian = $_GET['no_pembelian'];

// Ambil data header pembelian
$header = mysqli_fetch_assoc(mysqli_query($koneksi, 
    "SELECT * FROM tb_pembelian WHERE no_pembelian='$no_pembelian'"));

// PROSES TAMBAH DETAIL
if(isset($_POST['submit'])){
    $kd_barang     = $_POST['kd_barang'];
    $kode_jenis    = $_POST['kode_jenis'];
    $jumlah_barang = $_POST['jumlah_barang'];
    $harga_barang  = $_POST['harga_barang'];
    $total_harga   = $jumlah_barang * $harga_barang;

    // Cek no_item terakhir
    $last = mysqli_fetch_assoc(mysqli_query($koneksi, 
        "SELECT MAX(no_item) as last_item FROM detail_pembelian WHERE no_pembelian='$no_pembelian'"));
    $no_item = ($last['last_item'] ?? 0) + 1;

    // Insert detail
    mysqli_query($koneksi, "INSERT INTO detail_pembelian 
        (no_pembelian, kd_barang, kode_jenis, jumlah_barang, harga_barang, total_harga, no_item)
        VALUES ('$no_pembelian', '$kd_barang', '$kode_jenis', $jumlah_barang, $harga_barang, $total_harga, $no_item)");

    // Update stok barang
    mysqli_query($koneksi, "UPDATE tb_barang SET stok = stok + $jumlah_barang WHERE kd_barang='$kd_barang'");

    // Update total di tb_pembelian
    mysqli_query($koneksi, "UPDATE tb_pembelian SET 
        total_barangall = (SELECT SUM(jumlah_barang) FROM detail_pembelian WHERE no_pembelian='$no_pembelian'),
        total_hargaall  = (SELECT SUM(total_harga) FROM detail_pembelian WHERE no_pembelian='$no_pembelian')
        WHERE no_pembelian='$no_pembelian'");

    echo "<script>alert('Barang berhasil ditambahkan!');
          window.location='detail_pembelian.php?no_pembelian=".urlencode($no_pembelian)."&action=detail_pembelian';</script>";
}

// PROSES HAPUS ITEM
if(isset($_GET['hapus_item'])){
    $no_item   = $_GET['hapus_item'];
    $item_data = mysqli_fetch_assoc(mysqli_query($koneksi,
        "SELECT * FROM detail_pembelian WHERE no_pembelian='$no_pembelian' AND no_item=$no_item"));
    
    // Kembalikan stok
    mysqli_query($koneksi, "UPDATE tb_barang SET stok = stok - {$item_data['jumlah_barang']} WHERE kd_barang='{$item_data['kd_barang']}'");
    
    // Hapus detail
    mysqli_query($koneksi, "DELETE FROM detail_pembelian WHERE no_pembelian='$no_pembelian' AND no_item=$no_item");

    // Update total
    mysqli_query($koneksi, "UPDATE tb_pembelian SET 
        total_barangall = COALESCE((SELECT SUM(jumlah_barang) FROM detail_pembelian WHERE no_pembelian='$no_pembelian'), 0),
        total_hargaall  = COALESCE((SELECT SUM(total_harga) FROM detail_pembelian WHERE no_pembelian='$no_pembelian'), 0)
        WHERE no_pembelian='$no_pembelian'");

    echo "<script>alert('Item berhasil dihapus!');
          window.location='detail_pembelian.php?no_pembelian=".urlencode($no_pembelian)."&action=detail_pembelian';</script>";
}

// Ambil data barang untuk cari barang
$barang_dipilih = null;
if(isset($_GET['kd_barang'])){
    $kd = $_GET['kd_barang'];
    $barang_dipilih = mysqli_fetch_assoc(mysqli_query($koneksi,
        "SELECT tb_barang.*, tb_jenis.jenis FROM tb_barang 
         LEFT JOIN tb_jenis ON tb_barang.kode_jenis = tb_jenis.kode_jenis
         WHERE tb_barang.kd_barang='$kd'"));
}

// Ambil semua data detail pembelian
$detail = mysqli_query($koneksi, 
    "SELECT dp.*, tb.nama_barang, tj.jenis FROM detail_pembelian dp
     LEFT JOIN tb_barang tb ON dp.kd_barang = tb.kd_barang
     LEFT JOIN tb_jenis tj ON dp.kode_jenis = tj.kode_jenis
     WHERE dp.no_pembelian='$no_pembelian'
     ORDER BY dp.no_item ASC");

// Ambil semua barang untuk tabel pilih
$semua_barang = mysqli_query($koneksi, 
    "SELECT tb_barang.*, tb_jenis.jenis FROM tb_barang 
     LEFT JOIN tb_jenis ON tb_barang.kode_jenis = tb_jenis.kode_jenis");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Detail Pembelian</title>
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
        <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false">
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
        <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="true">
          <i class="mdi mdi-view-headline menu-icon"></i>
          <span class="menu-title">Kelola Transaksi</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse show" id="auth">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"><a class="nav-link active" href="transaksi_pembelian.php">Transaksi Pembelian</a></li>
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
  <!-- END SIDEBAR -->

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
    <!-- END NAVBAR -->

    <!-- MAIN PANEL -->
    <div class="main-panel">
      <div class="content-wrapper">
        <div class="row">

          <!-- KIRI: TABEL PILIH BARANG -->
          <div class="col-md-7 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h4 class="card-title mb-0">Pilih Data Barang</h4>
                  <a href="transaksi_pembelian.php" class="btn btn-danger btn-sm">Kembali</a>
                </div>
                <div class="table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Kode Jenis</th>
                        <th>Nama Barang</th>
                        <th>Stok</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Gambar</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $no = 1;
                      while($b = mysqli_fetch_assoc($semua_barang)):
                      ?>
                      <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $b['kd_barang']; ?></td>
                        <td><?php echo $b['kode_jenis']; ?></td>
                        <td><?php echo $b['nama_barang']; ?></td>
                        <td><?php echo $b['stok']; ?></td>
                        <td>Rp <?php echo number_format($b['harga_beli'],0,',','.'); ?>,00</td>
                        <td>Rp <?php echo number_format($b['harga_jual'],0,',','.'); ?>,00</td>
                        <td>
                          <?php if($b['gambar_produk']): ?>
                            <img src="../../uploads/<?php echo $b['gambar_produk']; ?>" width="50" height="40" style="object-fit:cover;">
                          <?php endif; ?>
                        </td>
                        <td>
                          <a href="?no_pembelian=<?php echo urlencode($no_pembelian); ?>&action=detail_pembelian&kd_barang=<?php echo $b['kd_barang']; ?>" 
                             class="btn btn-primary btn-sm">Pilih</a>
                        </td>
                      </tr>
                      <?php endwhile; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- KANAN: FORM DETAIL PEMBELIAN -->
          <div class="col-md-5 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Detail Transaksi Pembelian Faktur - <?php echo $no_pembelian; ?></h4>
                <form method="POST">
                  <div class="form-group row">
                    <label class="col-sm-5 col-form-label">Nomor Faktur Pembelian</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" value="<?php echo $no_pembelian; ?>" readonly>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-5 col-form-label">Kode Barang</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" name="kd_barang" 
                             value="<?php echo $barang_dipilih['kd_barang'] ?? '-'; ?>" readonly>
                      <?php if(!$barang_dipilih): ?>
                        <small class="text-muted">Pilih barang dari tabel kiri</small>
                      <?php else: ?>
                        <a href="?no_pembelian=<?php echo urlencode($no_pembelian); ?>&action=detail_pembelian" 
                           class="text-primary">Cari Barang</a>
                      <?php endif; ?>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-5 col-form-label">Nama Barang</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" 
                             value="<?php echo $barang_dipilih['nama_barang'] ?? '-'; ?>" readonly>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-5 col-form-label">Kode Jenis Barang</label>
                    <div class="col-sm-7">
                      <input type="hidden" name="kode_jenis" value="<?php echo $barang_dipilih['kode_jenis'] ?? ''; ?>">
                      <p class="mt-2"><?php echo $barang_dipilih['jenis'] ?? '-'; ?></p>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-5 col-form-label">Jumlah Barang</label>
                    <div class="col-sm-7">
                      <input type="number" class="form-control" name="jumlah_barang" 
                             placeholder="Jumlah Barang" min="1" 
                             <?php if(!$barang_dipilih) echo 'disabled'; ?> required>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-5 col-form-label">Stok Saat ini</label>
                    <div class="col-sm-7">
                      <p class="mt-2"><?php echo $barang_dipilih['stok'] ?? '-'; ?></p>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-5 col-form-label">Harga Barang</label>
                    <div class="col-sm-7">
                      <input type="number" class="form-control" name="harga_barang" 
                             value="<?php echo $barang_dipilih['harga_beli'] ?? ''; ?>" readonly>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-sm-7 offset-sm-5">
                      <?php if($barang_dipilih): ?>
                        <button type="submit" name="submit" class="btn btn-success me-1">Submit</button>
                        <a href="?no_pembelian=<?php echo urlencode($no_pembelian); ?>&action=detail_pembelian" 
                           class="btn btn-warning me-1">Reset</a>
                        <a href="transaksi_pembelian.php" class="btn btn-danger">Cancel</a>
                      <?php else: ?>
                        <button type="button" class="btn btn-success me-1" disabled>Submit</button>
                        <button type="button" class="btn btn-warning me-1" disabled>Reset</button>
                        <a href="transaksi_pembelian.php" class="btn btn-danger">Cancel</a>
                      <?php endif; ?>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- TABEL DATA DETAIL TRANSAKSI -->
        <div class="row">
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Data Detail Transaksi Pembelian</h4>
                <div class="table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>No Faktur Pembelian</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Kode Jenis</th>
                        <th>Jenis</th>
                        <th>Jumlah Barang</th>
                        <th>Harga Barang</th>
                        <th>Total Harga</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $no = 1;
                      while($d = mysqli_fetch_assoc($detail)):
                      ?>
                      <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $d['no_pembelian']; ?></td>
                        <td><?php echo $d['kd_barang']; ?></td>
                        <td><?php echo $d['nama_barang']; ?></td>
                        <td><?php echo $d['kode_jenis']; ?></td>
                        <td><?php echo $d['jenis']; ?></td>
                        <td><?php echo $d['jumlah_barang']; ?></td>
                        <td>Rp <?php echo number_format($d['harga_barang'],0,',','.'); ?>,00</td>
                        <td>Rp <?php echo number_format($d['total_harga'],0,',','.'); ?>,00</td>
                        <td>
                          <a href="?no_pembelian=<?php echo urlencode($no_pembelian); ?>&action=detail_pembelian&hapus_item=<?php echo $d['no_item']; ?>"
                             class="btn btn-danger btn-sm"
                             onclick="return confirm('Hapus item ini?')">Hapus</a>
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
    <!-- END MAIN PANEL -->

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