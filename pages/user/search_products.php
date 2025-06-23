<?php
$conn = new mysqli('localhost', 'root', '', 'db_koperasismk');

$keyword = isset($_GET['keyword']) ? $conn->real_escape_string($_GET['keyword']) : '';

$sql = "SELECT * FROM products WHERE stock > 0";
if ($keyword !== '') {
  $sql .= " AND (name LIKE '%$keyword%' OR description LIKE '%$keyword%')";
}
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()):
?>
  <div class="col-sm-6 col-md-4 col-lg-3">
    <div class="product-card h-100 d-flex flex-column">
      <img src="../../image/<?= htmlspecialchars($row['image']); ?>" alt="<?= htmlspecialchars($row['name']); ?>">
      <div class="product-details d-flex flex-column justify-content-between flex-grow-1">
        <h5><?= htmlspecialchars($row['name']); ?></h5>
        <p><?= nl2br(htmlspecialchars($row['description'])); ?></p>
        <div class="price">Rp <?= number_format($row['price'], 0, ',', '.'); ?></div>
        <div class="stock"><i class="fas fa-box"></i> Stok: <?= $row['stock']; ?></div>
        <button class="btn-cart mt-2"
                data-title="<?= htmlspecialchars($row['name']); ?>"
                data-price="<?= $row['price']; ?>"
                data-image="<?= htmlspecialchars($row['image']); ?>">
          <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
        </button>
      </div>
    </div>
  </div>
<?php endwhile; ?>
