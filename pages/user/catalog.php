<?php ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Katalog Produk - Koperasi Sekolah</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" />
  <link rel="icon" href="../../assets/images/favicon.png" type="image/png" />
  <style>
    body {
      background: linear-gradient(to right, #007bff, #0056b3);
      font-family: 'Segoe UI', sans-serif;
      color: #333;
      padding-top: 40px;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .catalog-container {
      max-width: 1200px;
      margin: auto;
      padding: 20px;
      flex: 1;
    }
    .section-title {
      text-align: center;
      color: white;
      margin-bottom: 30px;
      font-size: 2rem;
      font-weight: bold;
    }
    .product-card {
      background-color: #ffffff;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0,0,0,0.15);
      transition: transform 0.3s ease;
      animation: float 3s ease-in-out infinite;
      display: flex;
      flex-direction: column;
      height: 100%;
    }
    .product-card:hover {
      transform: translateY(-5px);
    }
    .product-card img {
      width: 100%;
      max-height: 150px;
      object-fit: contain;
      background-color: #f8f9fa;
      padding: 10px;
      border-bottom: 1px solid #ddd;
    }
    .product-details {
      padding: 15px;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .product-details h5 {
      font-weight: bold;
      color: #0056b3;
      margin-bottom: 10px;
    }
    .product-details p {
      font-size: 0.9rem;
      min-height: 60px;
      color: #444;
      margin-bottom: 10px;
    }
    .price {
      color: #007bff;
      font-size: 1.2rem;
      font-weight: bold;
      margin-bottom: 5px;
    }
    .stock {
      font-size: 0.9rem;
      color: #666;
      margin-bottom: 10px;
    }
    .btn-cart {
      background-color: #007bff;
      color: white;
      border: none;
      width: 100%;
      border-radius: 10px;
      padding: 10px;
      font-weight: 600;
      transition: background-color 0.3s;
      cursor: pointer;
    }
    .btn-cart:hover {
      background-color: #0056b3;
    }
    .top-bar {
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      align-items: center;
      gap: 10px;
      margin-bottom: 20px;
    }
    .top-bar .btn {
      border-radius: 10px;
      font-weight: 600;
    }
    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-5px); }
    }
    footer {
      background-color: #003f7f;
      color: white;
      padding: 20px 0;
      text-align: center;
    }
    footer a {
      color: white;
      margin: 0 10px;
      transition: color 0.3s;
    }
    footer a:hover {
      color: #ffc107;
    }
    @media (max-width: 767px) {
      .section-title {
        font-size: 1.5rem;
      }
      .product-details p {
        min-height: auto;
      }
      .btn-cart {
        font-size: 0.9rem;
      }
    }
  </style>
</head>
<body>

<div class="catalog-container">
  <div class="top-bar">
    <input type="text" id="searchInput" class="form-control" placeholder="Cari produk..." style="max-width: 300px;" />
    <div class="d-flex gap-2">
      <a href="dashboard.php" class="btn btn-light"><i class="fas fa-home"></i> Beranda</a>
      <a href="cart.php" class="btn btn-warning"><i class="fas fa-shopping-cart"></i> Keranjang</a>
    </div>
  </div>

  <h2 class="section-title"><i class="fas fa-store"></i> Katalog Produk Koperasi</h2>

  <div id="loading" class="text-center text-white mb-3" style="display:none;">
    <div class="spinner-border text-light" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>

  <div id="product-list">
    <div class="row g-4" id="product-wrapper">
      <!-- Produk akan di-load dari search_products.php -->
    </div>
  </div>
</div>

<footer>
  <div class="container">
    <p class="mb-1">© <?= date('Y'); ?> Koperasi Sekolah SMK</p>
    <p>Dibuat dengan 💙 oleh Tim IT SMK Kesehatan Muhammadiyah Bogor</p>
    <div>
      <a href="https://www.facebook.com/share/15Vac2x4sk/" target="_blank"><i class="fab fa-facebook fa-lg"></i></a>
      <a href="https://www.instagram.com/smk.kesehatan.merci" target="_blank"><i class="fab fa-instagram fa-lg"></i></a>
      <a href="https://www.tiktok.com/@_smkmuh.bogor" target="_blank"><i class="fab fa-tiktok fa-lg"></i></a>
    </div>
  </div>
</footer>

<script>
  const searchInput = document.getElementById('searchInput');
  const loading = document.getElementById('loading');

  function loadProducts(keyword = '') {
    loading.style.display = 'block';
    fetch(`search_products.php?keyword=${encodeURIComponent(keyword)}`)
      .then(response => response.text())
      .then(html => {
        loading.style.display = 'none';
        const wrapper = document.getElementById('product-wrapper');
        if (html.trim() === '') {
          wrapper.innerHTML = `<div class="col-12 text-center text-white">
            <h5>Tidak ada produk ditemukan.</h5>
          </div>`;
        } else {
          wrapper.innerHTML = html;
        }

        document.querySelectorAll('.btn-cart').forEach(button => {
          button.addEventListener('click', () => {
            const title = button.dataset.title;
            const price = parseFloat(button.dataset.price);
            const image = button.dataset.image;

            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            cart.push({ name: title, price: price, image: image });
            localStorage.setItem('cart', JSON.stringify(cart));
            alert(`Produk "${title}" berhasil ditambahkan ke keranjang.`);
          });
        });
      });
  }

  document.addEventListener('DOMContentLoaded', () => {
    loadProducts();
  });

  searchInput.addEventListener('input', () => {
    loadProducts(searchInput.value.trim());
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>