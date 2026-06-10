<?php
session_start();
include("../../frontend/koneksi.php");
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Anda harus login dulu!');window.location='../../frontend/login.php';</script>";
    exit;
}

// ── Filter ────────────────────────────────────────────────────────────────
$f_search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, trim($_GET['search'])) : '';

$where = $f_search
    ? "WHERE nama_supplier LIKE '%$f_search%' OR id_supplier LIKE '%$f_search%' OR email_supplier LIKE '%$f_search%'"
    : '';

$result     = mysqli_query($koneksi, "SELECT * FROM tb_supplier $where ORDER BY nama_supplier ASC");
$total_rows = mysqli_num_rows($result);
$total_all  = mysqli_fetch_row(mysqli_query($koneksi, "SELECT COUNT(*) FROM tb_supplier"))[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Laporan Data Supplier</title>
  <link rel="stylesheet" href="../../assets/spica/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../../assets/spica/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../../assets/spica/css/style.css">
  <link rel="shortcut icon" href="../../assets/spica/images/favicon.png"/>
  <style>
    .main-panel { padding-top: 63px; }
    .card { box-shadow: 0 2px 10px rgba(0,0,0,.08); border: none; }
    .table th { font-size: .82rem; white-space: nowrap; }
    .table td { font-size: .875rem; vertical-align: middle; }
    @media print {
      .sidebar, nav.navbar, .no-print, footer, .page-header { display: none !important; }
      .main-panel { padding-top: 0 !important; }
      .page-body-wrapper { padding: 0 !important; margin: 0 !important; }
      .card { box-shadow: none !important; border: 1px solid #dee2e6 !important; }
      .print-header { display: block !important; }
    }
    .print-header { display: none; text-align: center; margin-bottom: 16px; }
    .print-header h5 { margin: 0; font-size: 1rem; }
    .print-header p  { margin: 0; font-size: .85rem; color: #555; }
  </style>
</head>
<body>
<div class="container-scroller d-flex">

  <!-- ===== SIDEBAR ===== -->
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
        <a class="nav-link" data-bs-toggle="collapse" href="#auth2" aria-expanded="true" aria-controls="auth2">
          <i class="mdi mdi-view-headline menu-icon"></i>
          <span class="menu-title">Kelola Laporan</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse show" id="auth2">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"><a class="nav-link" href="laporan_pembelian.php">Laporan Transaksi Pembelian</a></li>
            <li class="nav-item"><a class="nav-link" href="laporan_penjualan.php">Laporan Transaksi Penjualan</a></li>
            <li class="nav-item"><a class="nav-link" href="laporan_customer.php">Laporan Data Customer</a></li>
            <li class="nav-item"><a class="nav-link active" href="laporan_supplier.php">Laporan Data Supplier</a></li>
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
        <h4 class="font-weight-bold mb-0 d-none d-md-block mt-1">
          Welcome <?= htmlspecialchars($_SESSION['username']) ?>
        </h4>
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
              <img src="../../assets/spica/images/faces/face5.jpg" alt="profile"/>
              <span class="nav-profile-name"><?= htmlspecialchars($_SESSION['tipe_user']) ?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown">
              <a class="dropdown-item" href="../../frontend/logout.php">
                <i class="mdi mdi-logout text-primary"></i> Logout
              </a>
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </nav>

    <!-- MAIN PANEL -->
    <div class="main-panel">
      <div class="content-wrapper">

        <!-- Print Header -->
        <div class="print-header">
          <h5>LAPORAN DATA SUPPLIER</h5>
          <p>Inventory System &nbsp;|&nbsp; Dicetak: <?= date('d/m/Y H:i') ?></p>
        </div>

        <!-- Page Header -->
        <div class="page-header mb-4 no-print">
          <h3 class="page-title">Laporan Data Supplier</h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="index_admin.php">Dashboard</a></li>
              <li class="breadcrumb-item active">Laporan Supplier</li>
            </ol>
          </nav>
        </div>

        <!-- Filter -->
        <div class="card mb-4 no-print">
          <div class="card-body py-3">
            <form method="GET" class="row g-2 align-items-end">
              <div class="col-auto">
                <label class="form-label mb-1 small fw-semibold">Cari Supplier</label>
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Nama / ID / Email..."
                       value="<?= htmlspecialchars($f_search) ?>" style="min-width:220px;">
              </div>
              <div class="col-auto d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm">
                  <i class="mdi mdi-magnify me-1"></i> Cari
                </button>
                <a href="laporan_supplier.php" class="btn btn-outline-secondary btn-sm">Reset</a>
              </div>
              <div class="col ms-auto text-end">
                <button type="button" class="btn btn-secondary btn-sm" onclick="window.print()">
                  <i class="mdi mdi-printer me-1"></i> Cetak
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Summary Card -->
        <div class="row g-3 mb-4 no-print">
          <div class="col-sm-4">
            <div class="card bg-warning text-white text-center py-3">
              <h3 class="mb-0 fw-bold"><?= $total_all ?></h3>
              <p class="mb-0 small">Total Supplier Terdaftar</p>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="card bg-success text-white text-center py-3">
              <h3 class="mb-0 fw-bold"><?= $total_rows ?></h3>
              <p class="mb-0 small">Ditampilkan</p>
            </div>
          </div>
        </div>

        <!-- Tabel -->
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3 no-print">
              <h4 class="card-title mb-0">Data Supplier</h4>
              <span class="text-muted small">Menampilkan <strong><?= $total_rows ?></strong> supplier</span>
            </div>

            <div class="table-responsive">
              <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                  <tr>
                    <th class="text-center">No</th>
                    <th>ID Supplier</th>
                    <th>Nama Supplier</th>
                    <th>Alamat</th>
                    <th>Telepon</th>
                    <th>Email</th>
                    <th class="text-center no-print">Jumlah Transaksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($total_rows === 0): ?>
                  <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                      <i class="mdi mdi-store-off" style="font-size:2.5rem;display:block;margin-bottom:8px;"></i>
                      Tidak ada data supplier.
                    </td>
                  </tr>
                  <?php else: $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                  <?php
                    $id_s    = mysqli_real_escape_string($koneksi, $row['id_supplier']);
                    $jml_trx = mysqli_fetch_row(mysqli_query($koneksi,
                        "SELECT COUNT(*) FROM tb_pembelian WHERE id_supplier='$id_s'"))[0];
                  ?>
                  <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><small class="text-muted"><?= htmlspecialchars($row['id_supplier']) ?></small></td>
                    <td class="fw-semibold"><?= htmlspecialchars($row['nama_supplier']) ?></td>
                    <td><?= htmlspecialchars($row['alamat_supplier'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($row['telepon_supplier'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($row['email_supplier'] ?? '-') ?></td>
                    <td class="text-center no-print">
                      <?php if ($jml_trx > 0): ?>
                        <span class="badge" style="background:#28a745;color:#fff;padding:3px 8px;border-radius:4px;">
                          <?= $jml_trx ?> transaksi
                        </span>
                      <?php else: ?>
                        <span class="text-muted small">Belum ada</span>
                      <?php endif; ?>
                    </td>
                  </tr>
                  <?php endwhile; endif; ?>
                </tbody>
                <?php if ($total_rows > 0): ?>
                <tfoot class="table-secondary fw-bold">
                  <tr>
                    <td colspan="2" class="text-end">TOTAL</td>
                    <td colspan="5"><?= $total_rows ?> supplier ditampilkan</td>
                  </tr>
                </tfoot>
                <?php endif; ?>
              </table>
            </div>
          </div>
        </div>

      </div><!-- /.content-wrapper -->
      <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
          <span class="text-muted text-center d-block d-sm-inline-block">
            Copyright &copy; <?= date('Y') ?> Inventory System
          </span>
        </div>
      </footer>
    </div><!-- /.main-panel -->
  </div><!-- /.page-body-wrapper -->
</div>

<script src="../../assets/spica/vendors/js/vendor.bundle.base.js"></script>
<script src="../../assets/spica/js/off-canvas.js"></script>
<script src="../../assets/spica/js/hoverable-collapse.js"></script>
<script src="../../assets/spica/js/template.js"></script>
</body>
</html>
