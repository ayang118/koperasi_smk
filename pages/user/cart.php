<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Keranjang Belanja - Koperasi Sekolah</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" />

  <style>
    body {
      background: linear-gradient(to right, #007bff, #0056b3);
      font-family: 'Segoe UI', sans-serif;
      padding-top: 60px;
      color: #333;
      min-height: 100vh;
    }

    .container {
      max-width: 1000px;
      margin: 120px auto 30px;
      background: white;
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      position: relative;
      z-index: 1;
    }

    .cart-title {
      text-align: center;
      font-size: 2rem;
      font-weight: bold;
      color: #0056b3;
      margin-bottom: 30px;
    }

    .cart-item {
      display: flex;
      align-items: center;
      border-bottom: 1px solid #ddd;
      padding: 15px 0;
      animation: fadeIn 0.8s ease;
    }

    .cart-item img {
      width: 80px;
      height: 80px;
      object-fit: contain;
      margin-right: 20px;
    }

    .cart-item-details {
      flex: 1;
    }

    .cart-item-details h5 {
      margin: 0;
      font-size: 1rem;
      color: #007bff;
    }

    .cart-item-details p {
      font-size: 0.85rem;
      margin: 2px 0;
    }

    .cart-item .remove-btn {
      color: red;
      font-size: 1.2rem;
      cursor: pointer;
      transition: transform 0.3s;
    }

    .cart-item .remove-btn:hover {
      transform: scale(1.2);
    }

    .total {
      text-align: right;
      font-size: 1.2rem;
      font-weight: bold;
      margin-top: 20px;
      color: #0056b3;
    }

    .btn-action {
      background-color: #007bff;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 10px;
      margin-top: 20px;
      transition: background-color 0.3s;
    }

    .btn-action:hover {
      background-color: #0056b3;
    }

    .top-right-buttons {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 1000;
      display: flex;
      gap: 10px;
    }

    .top-right-buttons a {
      white-space: nowrap;
    }

    /* Tombol Kembali Belanja dengan warna #ffc107 */
    .top-right-buttons a.btn-warning {
      background-color: #ffc107 !important;
      color: black !important;
      border-color: #ffc107 !important;
    }

    .top-right-buttons a.btn-warning:hover {
      background-color: #e0a800 !important;
      border-color: #d39e00 !important;
      color: black !important;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

<div class="top-right-buttons">
  <a href="dashboard.php" class="btn btn-light shadow"><i class="fas fa-home"></i> Beranda</a>
  <a href="catalog.php" class="btn btn-warning shadow"><i class="fas fa-arrow-left"></i> Kembali Belanja</a>
</div>

<div class="container">
  <div class="cart-title"><i class="fas fa-shopping-basket"></i> Keranjang Belanja</div>
  <div id="cart-items"></div>
  <div class="total">Total: <span id="total-price">Rp 0</span></div>
  <button class="btn-action" onclick="checkout()"><i class="fas fa-credit-card"></i> Checkout</button>
</div>

<!-- Script untuk ambil dari localStorage -->
<script>
  function formatRupiah(number) {
    return 'Rp ' + number.toLocaleString('id-ID', {minimumFractionDigits: 2});
  }

  function loadCart() {
    const cartContainer = document.getElementById('cart-items');
    const totalPriceEl = document.getElementById('total-price');
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    cartContainer.innerHTML = '';

    let total = 0;

    if (cart.length === 0) {
      cartContainer.innerHTML = '<p class="text-center text-muted">Keranjang masih kosong.</p>';
    } else {
      cart.forEach((item, index) => {
        total += parseFloat(item.price);

        const div = document.createElement('div');
        div.className = 'cart-item';

        div.innerHTML = `
          <img src="../../image/${item.image}" alt="${item.name}">
          <div class="cart-item-details">
            <h5>${item.name}</h5>
            <p>Harga: ${formatRupiah(parseFloat(item.price))}</p>
          </div>
          <i class="fas fa-trash remove-btn" onclick="removeItem(${index})"></i>
        `;
        cartContainer.appendChild(div);
      });
    }

    totalPriceEl.textContent = formatRupiah(total);
  }

  function removeItem(index) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
    loadCart();
  }

  function checkout() {
    window.location.href = 'checkout.php';
  }

  window.onload = loadCart;
</script>

<footer style="background-color: #003f7f; color: white; padding: 15px 15px 30px; text-align: center; margin-top: 30px; border-radius: 0 0 20px 20px;">
  <small style="display: block; margin-bottom: 8px; font-weight: 500;">
    © 2025 Koperasi Sekolah SMK
  </small>
  <small style="display: block; margin-bottom: 16px; font-weight: 400;">
    <span style="color: #1e90ff; font-size: 18px;"></span> Dibuat dengan 💙 oleh Tim IT SMK Kesehatan Muhammadiyah Bogor
  </small>
  <div>
    <a href="https://www.facebook.com/share/15Vac2x4sk/" target="_blank" rel="noopener noreferrer" style="color: white; margin: 0 12px;">
      <i class="fab fa-facebook fa-lg"></i>
    </a>
    <a href="https://www.instagram.com/smk.kesehatan.merci?igsh=Y3FxNW82dHJsam50" target="_blank" rel="noopener noreferrer" style="color: white; margin: 0 12px;">
      <i class="fab fa-instagram fa-lg"></i>
    </a>
    <a href="https://www.tiktok.com/@_smkmuh.bogor?is_from_webapp=1&sender_device=pc" target="_blank" rel="noopener noreferrer" style="color: white; margin: 0 12px;">
      <i class="fab fa-tiktok fa-lg"></i>
    </a>
  </div>
</footer>

</body>
</html>
