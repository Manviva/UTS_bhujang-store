
<?php
require_once "config.php";

$id = $_GET['id'] ?? '';

$stmt = $pdo->prepare(
    "SELECT * FROM products WHERE id = :id"
);
$stmt->execute([':id' => $id]);

$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Produk tidak ditemukan!");
}

// Mengambil kategori
$categories = $pdo->query(
    "SELECT * FROM categories"
)->fetchAll(PDO::FETCH_ASSOC);

// Mengambil kategori produk
$stmt = $pdo->prepare(
    "SELECT category_id FROM product_categories
     WHERE product_id = :id LIMIT 1"
);
$stmt->execute([':id' => $id]);

$currentCategory = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk Sepatu</title>

    <style>
        body {
            font-family: Arial;
            margin: 30px;
        }

        label {
            display: inline-block;
            width: 130px;
        }

        input, textarea, select {
            padding: 6px;
            width: 250px;
        }

        button {
            padding: 7px 15px;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <h2>Bhujang Store</h2>
    <h3>Edit Produk Sepatu</h3>

    <form action="update_product.php" method="POST">

        <input type="hidden" name="id"
               value="<?= htmlspecialchars($product['id']); ?>">

        <p>
            <label>Nama Sepatu:</label>
            <input type="text" name="nama_produk"
                value="<?= htmlspecialchars($product['nama_produk']); ?>"
                required>
        </p>

        <p>
            <label>Deskripsi:</label>
            <textarea name="deskripsi"><?= htmlspecialchars($product['deskripsi'] ?? ''); ?></textarea>
        </p>

        <p>
            <label>Harga:</label>
            <input type="number" name="harga" min="0" step="0.01"
                value="<?= htmlspecialchars($product['harga']); ?>"
                required>
        </p>

        <p>
            <label>SKU / Kode Produk:</label>
            <input type="text" name="sku"
                value="<?= htmlspecialchars($product['sku']); ?>"
                required>
        </p>

        <p>
            <label>Stok:</label>
            <input type="number" name="stok" min="0"
                value="<?= htmlspecialchars($product['stok']); ?>"
                required>
        </p>

        <p>
            <label>Kategori:</label>
            <select name="category_id" required>
                <option value="">Pilih Kategori</option>

                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id']; ?>"
                        <?= (string)$currentCategory === (string)$category['id'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($category['nama_kategori']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label>Status:</label>
            <select name="status">
                <option value="1"
                    <?= $product['status'] == 1 ? 'selected' : ''; ?>>
                    Aktif
                </option>

                <option value="0"
                    <?= $product['status'] == 0 ? 'selected' : ''; ?>>
                    Non-Aktif
                </option>
            </select>
        </p>

        <br>

        <button type="submit">Simpan Perubahan</button>

        <a href="list_products.php">Kembali</a>

    </form>

</body>
</html>
