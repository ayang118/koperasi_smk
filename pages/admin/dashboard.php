<?php
include('../../config/db.php');
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Dashboard - Koperasi Sekolah</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <style>
    body {
      background-color: #f4f6fa;
      color: #333;
      font-family: 'Segoe UI', sans-serif;
    }
    .navbar {
      background-color: #004085;
    }
    .navbar-brand, .nav-link {
      color: white !important;
    }
    .dashboard-header {
      background: linear-gradient(to right, #007bff, #0056b3);
      color: white;
      padding: 2rem;
      border-radius: 15px;
      margin-bottom: 2rem;
      box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    }
    .card {
      border: none;
      border-radius: 20px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .icon-box {
      background: #ffffff;
      border-radius: 20px;
      padding: 30px;
      text-align: center;
      transition: 0.3s;
      position: relative;
      overflow: hidden;
    }
    .icon-box:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 25px rgba(0,0,0,0.15);
    }
    .icon-box::before {
      content: '';
      position: absolute;
      top: -20%;
      right: -20%;
      width: 140%;
      height: 140%;
      background: radial-gradient(circle at center, #007bff33, transparent 70%);
      transform: rotate(45deg);
      z-index: 0;
    }
    .icon-box i {
      font-size: 2.5rem;
      color: #007bff;
      position: relative;
      z-index: 1;
    }
    .icon-box h5, .icon-box a {
      position: relative;
      z-index: 1;
    }
    .section-title {
      font-weight: bold;
      color: #007bff;
      margin-bottom: 1rem;
    }
    .form-select, .form-control {
      border-radius: 10px;
    }
    .table thead th {
      background-color: #007bff;
      color: white;
    }
    .table tfoot th {
      background-color: #e9ecef;
      font-weight: bold;
    }
    textarea.form-control {
      resize: none;
    }
    @media (max-width: 767px) {
      .icon-box {
        padding: 20px;
      }
      .icon-box i {
        font-size: 2rem;
      }
      .dashboard-header {
        padding: 1.5rem;
      }
      .form-control, .btn {
        font-size: 0.9rem;
      }
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><i class="fas fa-store"></i> Koperasi Admin</a>
    <div class="d-flex">
      <a href="../../logout.php" class="btn btn-outline-light ms-3"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <div class="dashboard-header">
    <h2 class="mb-0">Selamat Datang di Dashboard Admin</h2>
    <p>Kelola produk dan pantau penjualan koperasi sekolah dengan mudah dan profesional.</p>
  </div>

  <div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-lg-4">
      <div class="icon-box">
        <i class="fas fa-box"></i>
        <h5>Kelola Produk</h5>
        <a href="product_list.php" class="btn btn-sm btn-primary mt-2"><i class="fas fa-eye"></i> Lihat Produk</a>
      </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-4">
      <div class="icon-box">
        <i class="fas fa-plus-circle"></i>
        <h5>Tambah Produk</h5>
        <a href="#form-produk" class="btn btn-sm btn-success mt-2"><i class="fas fa-plus"></i> Tambah Baru</a>
      </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-4">
      <div class="icon-box">
        <i class="fas fa-file-invoice"></i>
        <h5>Laporan Penjualan</h5>
        <a href="#laporan" class="btn btn-sm btn-info mt-2"><i class="fas fa-file-alt"></i> Lihat Laporan</a>
      </div>
    </div>
  </div>

  <!-- Tambah Produk -->
  <div class="card p-4 mb-5" id="form-produk">
    <h5 class="section-title"><i class="fas fa-plus-circle"></i> Tambah Produk</h5>
    <form action="tambah_products.php" method="POST" enctype="multipart/form-data">
      <div class="row g-3">
        <div class="col-md-4">
          <input type="text" name="name" class="form-control" placeholder="Nama Produk" required />
        </div>
        <div class="col-md-2">
          <input type="number" step="0.01" name="price" class="form-control" placeholder="Harga" required />
        </div>
        <div class="col-md-2">
          <input type="number" name="stock" class="form-control" placeholder="Stok" required />
        </div>
        <div class="col-md-2">
          <input type="file" name="image" accept="image/jpeg" class="form-control" required />
        </div>
      </div>
      <div class="mt-3">
        <textarea name="description" class="form-control" rows="2" placeholder="Deskripsi Produk"></textarea>
      </div>
      <div class="mt-3 text-end">
        <button type="submit" class="btn btn-success"><i class="fas fa-upload"></i> Simpan</button>
      </div>
    </form>
  </div>

  <!-- Grafik Produk Terlaris -->
  <div class="card p-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="section-title mb-0"><i class="fas fa-chart-bar"></i> Grafik Produk Terlaris</h5>
      <select id="filterHari" class="form-select w-auto">
        <option value="7">7 Hari</option>
        <option value="30" selected>30 Hari</option>
        <option value="90">90 Hari</option>
      </select>
    </div>
    <canvas id="chartTerlaris" height="100"></canvas>
  </div>

  <!-- Laporan Penjualan -->
  <div class="card p-4 mb-5" id="laporan">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="section-title mb-0"><i class="fas fa-file-invoice"></i> Laporan Penjualan</h5>
      <button class="btn btn-danger btn-sm" onclick="cetakPDF()"><i class="fas fa-file-pdf"></i> Cetak PDF</button>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle text-center">
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Nama Pembeli</th>
            <th>Produk</th>
            <th>Jumlah</th>
            <th>Harga Satuan</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $totalSeluruh = 0;
          $query = "
            SELECT 
              o.order_date, 
              o.buyer_name, 
              oi.product_name, 
              1 AS quantity, 
              oi.product_price, 
              oi.product_price AS total
            FROM order_items oi
            JOIN orders o ON oi.order_id = o.id
            ORDER BY o.order_date DESC
          ";
          $result = $conn->query($query);
          if (!$result || $result->num_rows == 0) {
            echo "<tr><td colspan='6'>Belum ada data penjualan</td></tr>";
          }
          while ($row = $result->fetch_assoc()):
            $totalSeluruh += $row['total'];
          ?>
          <tr>
            <td><?php echo htmlspecialchars(date("d-m-Y", strtotime($row['order_date']))); ?></td>
            <td><?php echo htmlspecialchars($row['buyer_name']); ?></td>
            <td><?php echo htmlspecialchars($row['product_name']); ?></td>
            <td>1</td>
            <td>Rp <?php echo number_format($row['product_price'], 2, ',', '.'); ?></td>
            <td>Rp <?php echo number_format($row['total'], 2, ',', '.'); ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
        <tfoot>
          <tr>
            <th colspan="5">Total Penjualan</th>
            <th>Rp <?php echo number_format($totalSeluruh, 2, ',', '.'); ?></th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>

<script>
let chartTerlaris;

function loadChart(days = 30) {
  fetch(`data_chart.php?days=${days}`)
    .then(response => response.json())
    .then(data => {
      const labels = data.map(item => item.product_name);
      const values = data.map(item => item.jumlah_terjual);
      const colors = labels.map((_, i) => `hsl(${(i * 360) / labels.length}, 70%, 60%)`);

      if (chartTerlaris) {
        chartTerlaris.data.labels = labels;
        chartTerlaris.data.datasets[0].data = values;
        chartTerlaris.data.datasets[0].backgroundColor = colors;
        chartTerlaris.update();
      } else {
        const ctx = document.getElementById('chartTerlaris').getContext('2d');
        chartTerlaris = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: labels,
            datasets: [{
              label: 'Jumlah Terjual',
              data: values,
              backgroundColor: colors,
              borderColor: 'rgba(0, 123, 255, 1)',
              borderWidth: 1,
              borderRadius: 10
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: { display: false }
            },
            scales: {
              y: {
                beginAtZero: true,
                ticks: { precision: 0 }
              }
            }
          }
        });
      }
    });
}

document.addEventListener('DOMContentLoaded', function () {
  loadChart(30);

  document.getElementById('filterHari').addEventListener('change', function () {
    const selectedDays = this.value;
    loadChart(selectedDays);
  });
});

function cetakPDF() {
  const laporan = document.getElementById("laporan");
  html2canvas(laporan).then(canvas => {
    const imgData = canvas.toDataURL("image/png");
    const pdf = new jspdf.jsPDF();
    const imgProps = pdf.getImageProperties(imgData);
    const pdfWidth = pdf.internal.pageSize.getWidth();
    const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
    pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
    pdf.save("laporan_penjualan.pdf");
  });
}
</script>

</body>
</html>

