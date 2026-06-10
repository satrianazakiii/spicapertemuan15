<?php
global $koneksi;
session_start();
include("../../frontend/koneksi.php");
if(!isset($_SESSION['username'])){
    echo "<script>alert('Anda harus login dulu!');window.location='../../login.php';</script>";
    exit;
}

if(!isset($_GET['no_penjualan']) || empty($_GET['no_penjualan'])){
    echo "<script>alert('No penjualan tidak ditemukan!');window.location='transaksi_penjualan.php';</script>";
    exit;
}
$no_penjualan = $_GET['no_penjualan'];

// Ambil data barang yang dipilih
$barang_dipilih = null;
if(isset($_GET['kd_barang'])){
    $kd = $_GET['kd_barang'];
    $barang_dipilih = mysqli_fetch_assoc(mysqli_query($koneksi,
        "SELECT tb_barang.*, tb_jenis.jenis FROM tb_barang 
         LEFT JOIN tb_jenis ON tb_barang.kode_jenis = tb_jenis.kode_jenis
         WHERE tb_barang.kd_barang='$kd'"));
}

// ── PROSES SUBMIT / TAMBAH ITEM ──────────────────────────────────────────────
if(isset($_POST['submit'])){
    $kd_barang     = $_POST['kd_barang'];
    $kode_jenis    = $_POST['kode_jenis'];
    $jumlah_barang = (int)$_POST['jumlah_barang'];
    $harga_barang  = $_POST['harga_barang'];
    $total_harga   = $jumlah_barang * $harga_barang;

    // Cek ketersediaan stok
    $cek_stok = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT stok FROM tb_barang WHERE kd_barang='$kd_barang'"));
    
    if($cek_stok['stok'] < $jumlah_barang){
        echo "<script>alert('Gagal! Stok barang tidak mencukupi (Sisa stok: ".$cek_stok['stok'].")');</script>";
    } else {
        $last = mysqli_fetch_assoc(mysqli_query($koneksi,
            "SELECT MAX(no_item) as last_item FROM detail_penjualan WHERE no_penjualan='$no_penjualan'"));
        $no_item = ($last['last_item'] ?? 0) + 1;

        // Insert ke detail_penjualan
        mysqli_query($koneksi, "INSERT INTO detail_penjualan 
            (no_penjualan, kd_barang, kode_jenis, jumlah_barang, harga_barang, total_harga, no_item)
            VALUES ('$no_penjualan','$kd_barang','$kode_jenis',$jumlah_barang,$harga_barang,$total_harga,$no_item)");

        // Kurangi stok barang karena terjadi PENJUALAN
        mysqli_query($koneksi, "UPDATE tb_barang SET stok = stok - $jumlah_barang WHERE kd_barang='$kd_barang'");

        // Update total di tabel utama
        mysqli_query($koneksi, "UPDATE tb_penjualan SET 
            total_barangall = (SELECT SUM(jumlah_barang) FROM detail_penjualan WHERE no_penjualan='$no_penjualan'),
            total_hargaall  = (SELECT SUM(total_harga)   FROM detail_penjualan WHERE no_penjualan='$no_penjualan')
            WHERE no_penjualan='$no_penjualan'");

        echo "<script>alert('Barang berhasil ditambahkan ke Faktur!');
              window.location='penjualan_barang.php?no_penjualan=".urlencode($no_penjualan)."&action=pilih_barang';</script>";
    }
}

// ── PROSES HAPUS ITEM ────────────────────────────────────────────────────────
if(isset($_GET['hapus_item'])){
    $no_item = $_GET['hapus_item'];
    
    // Cari data item yang dihapus
    $q_del = mysqli_query($koneksi, "SELECT kd_barang, jumlah_barang FROM detail_penjualan WHERE no_penjualan='$no_penjualan' AND no_item='$no_item'");
    $d_del = mysqli_fetch_assoc($q_del);
    
    if($d_del){
        $kd_hapus = $d_del['kd_barang'];
        $jml_kembali = $d_del['jumlah_barang'];

        // Hapus detailnya
        mysqli_query($koneksi, "DELETE FROM detail_penjualan WHERE no_penjualan='$no_penjualan' AND no_item='$no_item'");

        // Kembalikan stok barang (karena batal dijual)
        mysqli_query($koneksi, "UPDATE tb_barang SET stok = stok + $jml_kembali WHERE kd_barang='$kd_hapus'");

        // Update ulang tabel utama
        mysqli_query($koneksi, "UPDATE tb_penjualan SET 
            total_barangall = COALESCE((SELECT SUM(jumlah_barang) FROM detail_penjualan WHERE no_penjualan='$no_penjualan'), 0),
            total_hargaall  = COALESCE((SELECT SUM(total_harga)   FROM detail_penjualan WHERE no_penjualan='$no_penjualan'), 0)
            WHERE no_penjualan='$no_penjualan'");
    }
    
    echo "<script>window.location='penjualan_barang.php?no_penjualan=".urlencode($no_penjualan)."&action=pilih_barang';</script>";
}

// Data daftar barang yang bisa dipilih
$barang = mysqli_query($koneksi, "SELECT tb_barang.*, tb_jenis.jenis FROM tb_barang LEFT JOIN tb_jenis ON tb_barang.kode_jenis = tb_jenis.kode_jenis");

// Data keranjang penjualan (detail penjualan)
$detail = mysqli_query($koneksi, 
    "SELECT dp.*, b.nama_barang, j.jenis 
     FROM detail_penjualan dp 
     LEFT JOIN tb_barang b ON dp.kd_barang = b.kd_barang
     LEFT JOIN tb_jenis j ON dp.kode_jenis = j.kode_jenis
     WHERE dp.no_penjualan='$no_penjualan' ORDER BY dp.no_item ASC");

// Ambil total faktur saat ini
$faktur = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_penjualan WHERE no_penjualan='$no_penjualan'"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Detail Penjualan</title>
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
      <li class="nav-item"><a class="nav-link" href="index_admin.php"><i class="mdi mdi-view-quilt menu-icon"></i><span class="menu-title">Dashboard</span></a></li>
      <li class="nav-item sidebar-category"><p>Components</p><span></span></li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false">
          <i class="mdi mdi-view-headline menu-icon"></i><span class="menu-title">Kelola Data</span><i class="menu-arrow"></i>
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
          <i class="mdi mdi-view-headline menu-icon"></i><span class="menu-title">Kelola Transaksi</span><i class="menu-arrow"></i>
        </a>
        <div class="collapse show" id="auth">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"><a class="nav-link" href="transaksi_pembelian.php">Transaksi Pembelian</a></li>
            <li class="nav-item"><a class="nav-link active" href="transaksi_penjualan.php">Transaksi Penjualan</a></li>
          </ul>
        </div>
      </li>
    </ul>
  </nav>

  <div class="container-fluid page-body-wrapper">

    <nav class="navbar col-lg-12 col-12 px-0 py-0 py-lg-4 d-flex flex-row">
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize"><span class="mdi mdi-menu"></span></button>
        <div class="navbar-brand-wrapper">
          <a class="navbar-brand brand-logo" href="index_admin.php"><img src="../../assets/spica/images/logo.svg" alt="logo"/></a>
          <a class="navbar-brand brand-logo-mini" href="index_admin.php"><img src="../../assets/spica/images/logo-mini.svg" alt="logo"/></a>
        </div>
        <h4 class="font-weight-bold mb-0 d-none d-md-block mt-1">Welcome <?php echo $_SESSION['username']; ?></h4>
      </div>
      <div class="navbar-menu-wrapper navbar-search-wrapper d-none d-lg-flex align-items-center">
        <ul class="navbar-nav mr-lg-2">
          <li class="nav-item nav-search d-none d-lg-block">
            <div class="input-group"><input type="text" class="form-control" placeholder="Search Here..."></div>
          </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
              <img src="../../assets/spica/images/faces/face5.jpg" alt="profile"/>
              <span class="nav-profile-name"><?php echo $_SESSION['username']; ?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item" href="../../login.php"><i class="mdi mdi-logout text-primary"></i> Logout</a>
            </div>
          </li>
        </ul>
      </div>
    </nav>

    <div class="main-panel">
      <div class="content-wrapper">

        <div class="row mb-4">
          <div class="col-md-6">
            <h4 class="card-title text-primary">No Faktur Penjualan: <?php echo htmlspecialchars($no_penjualan); ?></h4>
            <a href="transaksi_penjualan.php" class="btn btn-warning btn-sm">Selesai / Kembali</a>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Daftar Barang</h4>
                <div class="table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Jenis</th>
                        <th>Stok Tersedia</th>
                        <th>Gambar</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php while($b = mysqli_fetch_assoc($barang)): ?>
                      <tr>
                        <td><?php echo $b['kd_barang']; ?></td>
                        <td><?php echo $b['nama_barang']; ?></td>
                        <td><?php echo $b['jenis']; ?></td>
                        <td><span class="badge badge-<?php echo ($b['stok'] > 0) ? 'success' : 'danger'; ?>"><?php echo $b['stok']; ?></span></td>
                        <td>
                          <?php if(!empty($b['gambar_produk'])): ?>
                            <img src="../../assets/upload/<?php echo $b['gambar_produk']; ?>" width="60" height="45" style="object-fit:cover; border-radius:4px;">
                          <?php else: ?>
                            <span class="text-muted">-</span>
                          <?php endif; ?>
                        </td>
                        <td>
                          <a href="?no_penjualan=<?php echo urlencode($no_penjualan); ?>&action=pilih_barang&kd_barang=<?php echo $b['kd_barang']; ?>" class="btn btn-primary btn-sm">Pilih</a>
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

        <?php if($barang_dipilih): ?>
        <div class="row">
          <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title text-success">Barang Terpilih: <?php echo $barang_dipilih['nama_barang']; ?></h4>
                <form method="POST">
                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label>Kode Barang</label>
                        <input type="text" class="form-control" name="kd_barang" value="<?php echo $barang_dipilih['kd_barang']; ?>" readonly>
                        <input type="hidden" name="kode_jenis" value="<?php echo $barang_dipilih['kode_jenis']; ?>">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label>Harga Satuan</label>
                        <input type="text" class="form-control" name="harga_barang" value="<?php echo isset($barang_dipilih['harga_jual']) ? $barang_dipilih['harga_jual'] : (isset($barang_dipilih['harga_barang']) ? $barang_dipilih['harga_barang'] : 0); ?>" readonly>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label>Stok Tersedia</label>
                        <input type="text" class="form-control" value="<?php echo $barang_dipilih['stok']; ?>" readonly>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label>Jumlah Terjual</label>
                        <input type="number" class="form-control" name="jumlah_barang" min="1" max="<?php echo $barang_dipilih['stok']; ?>" required>
                      </div>
                    </div>
                  </div>
                  <button type="submit" name="submit" class="btn btn-success">Tambahkan ke Keranjang Faktur</button>
                </form>
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <div class="row">
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Keranjang Faktur: <?php echo htmlspecialchars($no_penjualan); ?></h4>
                <div class="table-responsive">
                  <table class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Kode Jenis</th>
                        <th>Jenis</th>
                        <th>Jumlah Terjual</th>
                        <th>Harga Satuan</th>
                        <th>Sub Total</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      $no = 1; 
                      while($d = mysqli_fetch_assoc($detail)): 
                      ?>
                      <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $d['kd_barang']; ?></td>
                        <td><?php echo $d['nama_barang']; ?></td>
                        <td><?php echo $d['kode_jenis']; ?></td>
                        <td><?php echo $d['jenis']; ?></td>
                        <td><?php echo $d['jumlah_barang']; ?></td>
                        <td>Rp <?php echo number_format($d['harga_barang'],0,',','.'); ?>,00</td>
                        <td>Rp <?php echo number_format($d['total_harga'],0,',','.'); ?>,00</td>
                        <td>
                          <a href="?no_penjualan=<?php echo urlencode($no_penjualan); ?>&action=pilih_barang&hapus_item=<?php echo $d['no_item']; ?>"
                             class="btn btn-danger btn-sm"
                             onclick="return confirm('Hapus item ini dari faktur? (Stok akan dikembalikan)')">Hapus</a>
                        </td>
                      </tr>
                      <?php endwhile; ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th colspan="5" style="text-align:right;">TOTAL KESELURUHAN:</th>
                        <th><?php echo $faktur['total_barangall'] ?? 0; ?></th>
                        <th></th>
                        <th>Rp <?php echo number_format($faktur['total_hargaall'] ?? 0, 0, ',', '.'); ?>,00</th>
                        <th></th>
                      </tr>
                    </tfoot>
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