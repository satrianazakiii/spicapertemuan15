<?php
global $koneksi;
?>
<div class="main-panel">
  <div class="content-wrapper">

    <!-- Row 1: Charts -->
    <div class="row">
      <div class="col-12 col-xl-6 grid-margin stretch-card">
        <div class="row w-100 flex-grow">
          <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <p class="card-title">Website Audience Metrics</p>
                <p class="text-muted">Sistem Informasi Inventory Gudang</p>
                <div class="row mb-3">
                  <div class="col-md-7">
                    <div class="d-flex justify-content-between traffic-status">
                      <div class="item">
                        <p class="mb-0">Total Barang</p>
                        <?php
                          $q = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tb_barang");
                          $r = mysqli_fetch_array($q);
                        ?>
                        <h5 class="font-weight-bold mb-0"><?php echo $r['total']; ?></h5>
                        <div class="color-border"></div>
                      </div>
                      <div class="item">
                        <p class="mb-0">Supplier</p>
                        <?php
                          $q2 = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tb_supplier");
                          $r2 = mysqli_fetch_array($q2);
                        ?>
                        <h5 class="font-weight-bold mb-0"><?php echo $r2['total']; ?></h5>
                        <div class="color-border"></div>
                      </div>
                      <div class="item">
                        <p class="mb-0">Customer</p>
                        <?php
                          $q3 = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tb_customer");
                          $r3 = mysqli_fetch_array($q3);
                        ?>
                        <h5 class="font-weight-bold mb-0"><?php echo $r3['total']; ?></h5>
                        <div class="color-border"></div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <ul class="nav nav-pills nav-pills-custom justify-content-md-end" id="pills-tab-custom" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" data-toggle="pill" href="#pills-health" role="tab">Day</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#pills-career" role="tab">Week</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#pills-music" role="tab">Month</a>
                      </li>
                    </ul>
                  </div>
                </div>
                <canvas id="audience-chart"></canvas>
              </div>
            </div>
          </div>
          <div class="col-md-6 stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                  <p class="card-title">Total Transaksi Pembelian</p>
                  <?php
                    $q4 = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tb_pembelian");
                    $r4 = mysqli_fetch_array($q4);
                  ?>
                  <p class="text-success font-weight-medium"><?php echo $r4['total']; ?> Transaksi</p>
                </div>
                <canvas id="balance-chart" height="130"></canvas>
              </div>
            </div>
          </div>
          <div class="col-md-6 stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                  <p class="card-title">Total Transaksi Penjualan</p>
                  <?php
                    $q5 = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tb_penjualan");
                    $r5 = mysqli_fetch_array($q5);
                  ?>
                  <p class="text-success font-weight-medium"><?php echo $r5['total']; ?> Transaksi</p>
                </div>
                <canvas id="task-chart" height="130"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-xl-6 grid-margin stretch-card">
        <div class="row w-100 flex-grow">
          <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <p class="card-title">Regional Load</p>
                <p class="text-muted">Last update: 2 Hours ago</p>
                <div class="regional-chart-legend d-flex align-items-center flex-wrap mb-1" id="regional-chart-legend"></div>
                <canvas height="280" id="regional-chart"></canvas>
              </div>
            </div>
          </div>
          <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
              <div class="card-body pb-0">
                <div class="d-flex align-items-center mb-4">
                  <p class="card-title mb-0 me-1">Today activity</p>
                  <div class="badge badge-info badge-pill">2</div>
                </div>
                <div class="d-flex flex-wrap pt-2">
                  <div class="me-4 mb-lg-2 mb-xl-0">
                    <p>Time On Site</p>
                    <h4 class="font-weight-bold mb-0">77.15 %</h4>
                  </div>
                  <div>
                    <p>Page Views</p>
                    <h4 class="font-weight-bold mb-0">14.15 %</h4>
                  </div>
                </div>
              </div>
              <canvas height="150" id="activity-chart"></canvas>
            </div>
          </div>
          <div class="col-md-12 stretch-card">
            <div class="card">
              <div class="card-body pb-0">
                <p class="card-title">Server Status 247</p>
                <div class="d-flex justify-content-between flex-wrap">
                  <p class="text-muted">Last update: 2 Hours ago</p>
                  <div class="d-flex align-items-center flex-wrap server-status-legend mt-3 mb-3 mb-md-0">
                    <div class="item me-3">
                      <div class="d-flex align-items-center">
                        <div class="color-bullet"></div>
                        <h5 class="font-weight-bold mb-0">128GB</h5>
                      </div>
                      <p class="mb-0">Total Usage</p>
                    </div>
                    <div class="item me-3">
                      <div class="d-flex align-items-center">
                        <div class="color-bullet"></div>
                        <h5 class="font-weight-bold mb-0">92%</h5>
                      </div>
                      <p class="mb-0">Memory Usage</p>
                    </div>
                    <div class="item me-3">
                      <div class="d-flex align-items-center">
                        <div class="color-bullet"></div>
                        <h5 class="font-weight-bold mb-0">16%</h5>
                      </div>
                      <p class="mb-0">Disk Usage</p>
                    </div>
                  </div>
                </div>
              </div>
              <canvas height="170" id="status-chart"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabel Data Barang Terbaru -->
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Data Barang Terbaru</h4>
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Stok</th>
                    <th>Harga Jual</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $qb = mysqli_query($koneksi, "SELECT * FROM tb_barang ORDER BY kd_barang DESC LIMIT 5");
                    while($rb = mysqli_fetch_array($qb)){
                  ?>
                  <tr>
                    <td><?php echo $rb['kd_barang']; ?></td>
                    <td><?php echo $rb['nama_barang']; ?></td>
                    <td><?php echo $rb['stok']; ?></td>
                    <td>Rp <?php echo number_format($rb['harga_jual'], 0, ',', '.'); ?></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
  <!-- content-wrapper ends -->

  <footer class="footer">
    <div class="card">
      <div class="card-body">
        <div class="d-sm-flex justify-content-center justify-content-sm-between py-2">
          <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright &copy; SI Gudang 2024</span>
        </div>
      </div>
    </div>
  </footer>

</div>
<!-- main-panel ends -->