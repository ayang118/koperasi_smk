<?php
include('../../config/db.php');  // Koneksi ke database
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Daftar Produk - Admin Koperasi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" />
  <style>
    body {
      background: linear-gradient(to bottom right, #007bff, #0056b3);
      color: white;
      font-family: 'Segoe UI', sans-serif;
    }
    .navbar {
      background-color: #004085;
    }
    .navbar-brand, .nav-link {
      color: white !important;
    }
    .product-img {
      width: 100%;
      height: 180px;
      object-fit: contain;
      border-top-left-radius: 20px;
      border-top-right-radius: 20px;
      background: #f8f9fa;
      padding: 10px;
    }
    .card {
      background: white;
      color: #333;
      border: none;
      border-radius: 20px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php"><i class="fas fa-arrow-left"></i> Kembali</a>
    <span class="navbar-text mx-auto text-white">Daftar Produk</span>
  </div>
</nav>

<!-- Konten -->
<div class="container py-4">
  <div class="card p-4">
    <h5 class="mb-4"><i class="fas fa-boxes-stacked"></i> Produk Tersedia</h5>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
      <?php
      $query = "SELECT * FROM products";
      $result = $conn->query($query);
      while ($row = $result->fetch_assoc()):
      ?>
      <div class="col">
        <div class="card h-100">
          <img src="../../image/<?php echo htmlspecialchars($row['image']); ?>" class="card-img-top product-img" alt="<?php echo htmlspecialchars($row['name']); ?>">
          <div class="card-body">
            <h6 class="card-title text-primary"><?php echo htmlspecialchars($row['name']); ?></h6>
            <p class="mb-1">Harga: <strong>Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></strong></p>
            <p class="mb-1">Stok: <span class="badge bg-success"><?php echo intval($row['stock']); ?></span></p>
            <p class="small text-muted"><?php echo htmlspecialchars($row['description']); ?></p>
          </div>
          <div class="card-footer bg-transparent border-0 d-flex justify-content-between">
            <a href="edit_products.php?id=<?php echo intval($row['id']); ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
            <a href="delete_products.php?id=<?php echo intval($row['id']); ?>" onclick="return confirm('Yakin hapus?')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
  </div>
</div>

</body>
</html>
