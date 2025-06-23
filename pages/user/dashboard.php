<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Koperasi Sekolah</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background-color: #f4f6f9;
      color: #333;
      overflow-x: hidden;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    .navbar {
      background-color: #3498db;
      padding: 20px 50px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: white;
      animation: fadeInDown 1s ease forwards;
    }

    .navbar h1 {
      font-size: 26px;
    }

    .navbar ul {
      display: flex;
      list-style: none;
      gap: 30px;
    }

    .navbar ul li a {
      color: white;
      text-decoration: none;
      font-size: 16px;
      transition: 0.3s;
    }

    .navbar ul li a:hover {
      color: #f1c40f;
    }

    /* Animasi hero */
    .hero {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 30px 100px 60px 100px;
      background: linear-gradient(135deg, #74ebd5, #ACB6E5);
      overflow: hidden;
    }

    .hero-text {
      max-width: 600px;
      animation: fadeInUp 1s ease forwards;
      opacity: 0;
      transform: translateY(20px);
      animation-delay: 0.5s;
      animation-fill-mode: forwards;
    }

    .hero-text h2 {
      font-size: 48px;
      color: #2c3e50;
      margin-bottom: 15px;
    }

    .hero-text p {
      font-size: 18px;
      margin-bottom: 20px;
      line-height: 1.6;
    }

    .hero .lottie-player {
      width: 400px;
      height: 400px;
      animation: float 4s ease-in-out infinite;
    }

    .start-btn {
      display: inline-block;
      margin-top: 10px;
      padding: 12px 24px;
      font-size: 18px;
      border: none;
      border-radius: 8px;
      background-color: #2980b9;
      color: white;
      cursor: pointer;
      text-decoration: none;
      transition: background-color 0.3s, transform 0.3s;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
    }

    .start-btn:hover {
      background-color: #1f618d;
      transform: scale(1.1);
      box-shadow: 0 8px 12px rgba(0, 0, 0, 0.3);
    }

    .features {
      padding: 60px 100px;
      background-color: white;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 40px;
      flex-grow: 1;
    }

    .feature-box {
      background-color: #ecf0f1;
      padding: 30px;
      border-radius: 12px;
      text-align: center;
      transition: 0.3s;
      opacity: 0;
      transform: translateY(20px);
      animation: fadeInUp 1s ease forwards;
    }

    /* Tambahkan delay agar muncul bertahap */
    .feature-box:nth-child(1) {
      animation-delay: 0.3s;
    }
    .feature-box:nth-child(2) {
      animation-delay: 0.6s;
    }
    .feature-box:nth-child(3) {
      animation-delay: 0.9s;
    }
    .feature-box:nth-child(4) {
      animation-delay: 1.2s;
    }

    .feature-box:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .feature-box i {
      font-size: 36px;
      color: #3498db;
      margin-bottom: 15px;
    }

    .feature-box h3 {
      font-size: 20px;
      margin-bottom: 10px;
    }

    .catalog-preview {
      padding: 60px 100px;
      background: #f9f9f9;
    }

    .catalog-preview h2 {
      text-align: center;
      font-size: 32px;
      margin-bottom: 40px;
      opacity: 0;
      transform: translateY(20px);
      animation: fadeInUp 1s ease forwards;
      animation-delay: 1.5s;
      animation-fill-mode: forwards;
    }

    .catalog-items {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 30px;
    }

    .item {
      background-color: white;
      border-radius: 12px;
      padding: 20px;
      text-align: center;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      opacity: 0;
      transform: translateY(20px);
      animation: fadeInUp 1s ease forwards;
    }

    /* Delay muncul bertahap produk */
    .item:nth-child(1) {
      animation-delay: 1.8s;
    }
    .item:nth-child(2) {
      animation-delay: 2.0s;
    }
    .item:nth-child(3) {
      animation-delay: 2.2s;
    }
    .item:nth-child(4) {
      animation-delay: 2.4s;
    }

    .item img {
      width: 100px;
      height: 100px;
      object-fit: contain;
      margin-bottom: 15px;
    }

    .item h4 {
      margin-bottom: 10px;
      font-size: 18px;
    }

    .item p {
      font-size: 14px;
      color: #888;
    }

    /* Footer yang diminta */
    footer {
      background-color: #003f7f;
      color: white;
      padding: 15px 15px 30px;
      text-align: center;
      margin-top: 30px;
      border-radius: 0 0 20px 20px;
    }
    footer small {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
    }
    footer small + small {
      margin-bottom: 16px;
      font-weight: 400;
    }
    footer small span {
      color: #1e90ff;
      font-size: 18px;
      margin-right: 6px;
    }
    footer div a {
      color: white;
      margin: 0 12px;
      transition: color 0.3s;
    }
    footer div a:hover {
      color: #1e90ff;
    }

    @media (max-width: 768px) {
      .hero {
        flex-direction: column;
        padding: 40px 20px;
      }

      .hero .lottie-player {
        margin-top: 20px;
      }

      .features,
      .catalog-preview {
        padding: 40px 20px;
      }
    }

    /* Keyframes animasi */
    @keyframes fadeInUp {
      0% {
        opacity: 0;
        transform: translateY(20px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes fadeInDown {
      0% {
        opacity: 0;
        transform: translateY(-20px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes float {
      0%, 100% {
        transform: translateY(0);
      }
      50% {
        transform: translateY(-20px);
      }
    }
  </style>
</head>
<body>
  <div class="navbar">
    <h1>Koperasi Sekolah</h1>
    <ul>
      <li><a href="dashboard.php"><i class="fas fa-home"></i> Beranda</a></li>
      <li><a href="catalog.php"><i class="fas fa-box-open"></i> Katalog</a></li>
      <li><a href="cart.php"><i class="fas fa-shopping-cart"></i> Keranjang</a></li>
      <li><a href="../../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
  </div>

  <section class="hero">
    <div class="hero-text">
      <h2>Selamat Datang di Koperasi Sekolah</h2>
      <p>
        Dapatkan perlengkapan sekolah, alat tulis, seragam, dan kebutuhan harian lainnya dengan mudah dan cepat hanya di koperasi digital kami!
      </p>
      <a href="catalog.php" class="start-btn"><i class="fas fa-shopping-cart"></i> Belanja Sekarang</a>
    </div>
    <lottie-player
      src="https://assets1.lottiefiles.com/packages/lf20_jcikwtux.json"
      background="transparent"
      speed="1"
      loop
      autoplay
    ></lottie-player>
  </section>

 <section class="features">
    <div class="feature-box">
      <i class="fas fa-tags"></i>
      <h3>Harga Terjangkau</h3>
      <p>Produk koperasi dijual dengan harga bersahabat untuk siswa dan guru.</p>
    </div>
    <div class="feature-box">
      <i class="fas fa-truck"></i>
      <h3>Ambil di Sekolah</h3>
      <p>Pesan online, ambil langsung tanpa antre panjang.</p>
    </div>
    <div class="feature-box">
      <i class="fas fa-check-circle"></i>
      <h3>Produk Terpercaya</h3>
      <p>Barang berkualitas dari koperasi resmi sekolah.</p>
    </div>
    <div class="feature-box">
      <i class="fas fa-user-friends"></i>
      <h3>Dukung Sesama</h3>
      <p>Setiap pembelian ikut mendukung operasional sekolah dan siswa lain.</p>
    </div>
  </section>

  <section class="catalog-preview">
  <h2>Produk Unggulan</h2>
  <div class="catalog-items">
    <div class="item">
      <a href="catalog.php" target="_blank" rel="noopener noreferrer" style="text-decoration:none; color:inherit;">
        <img src="depann.jpg" alt="Seragam Sekolah" />
        <h4>Seragam Sekolah</h4>
        <p>Seragam harian berkualitas tinggi.</p>
      </a>
    </div>
    <div class="item">
      <a href="catalog.php" target="_blank" rel="noopener noreferrer" style="text-decoration:none; color:inherit;">
        <img src="atk.jpg" alt="Alat Tulis" />
        <h4>Alat Tulis</h4>
        <p>Pensil, pulpen, penggaris dan lainnya.</p>
      </a>
    </div>
    <div class="item">
      <a href="https://sites.google.com/view/smkmuhammadiyahkotabogor/beranda" target="_blank" rel="noopener noreferrer" style="text-decoration:none; color:inherit;">
        <img src="https://cdn-icons-png.flaticon.com/512/2821/2821054.png" alt="Snack" />
        <h4>Website Sekolah</h4>
        <p>SMK Kesehatan Muhammadiyah Kota Bogor</p>
      </a>
    </div>
  </div>
</section>

<footer>
  <small>© 2025 Koperasi Sekolah SMK</small>
  <small><span></span> Dibuat dengan 💙 oleh Tim IT SMK Kesehatan Muhammadiyah Bogor</small>
  <div>
    <a href="https://www.facebook.com/share/15Vac2x4sk/" target="_blank" rel="noopener noreferrer">
      <i class="fab fa-facebook fa-lg"></i>
    </a>
    <a href="https://www.instagram.com/smk.kesehatan.merci?igsh=Y3FxNW82dHJsam50" target="_blank" rel="noopener noreferrer">
      <i class="fab fa-instagram fa-lg"></i>
    </a>
    <a href="https://www.tiktok.com/@_smkmuh.bogor?is_from_webapp=1&sender_device=pc" target="_blank" rel="noopener noreferrer">
      <i class="fab fa-tiktok fa-lg"></i>
    </a>
  </div>
</footer
