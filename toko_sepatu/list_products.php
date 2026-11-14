
<?php
require_once "config.php";

// Mengambil kategori
$categories = $pdo->query(
    "SELECT * FROM categories"
)->fetchAll(PDO::FETCH_ASSOC);

// Filter kategori
$category_id = $_GET['category'] ?? '';

$sql = "SELECT p.*, c.nama_kategori
        FROM products p
        LEFT JOIN product_categories pc
        ON p.id = pc.product_id
        LEFT JOIN categories c
        ON pc.category_id = c.id";

if ($category_id != '') {
    $sql .= " WHERE c.id = :category";
}

$sql .= " ORDER BY p.id DESC";

$stmt = $pdo->prepare($sql);

if ($category_id != '') {
    $stmt->execute([':category' => $category_id]);
} else {
    $stmt->execute();
}

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Produk Sepatu</title>

    <style>
        body {
            font-family: Arial;
            margin: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        th {
            background: #eee;
        }

        a {
            text-decoration: none;
        }

        button {
            padding: 6px 12px;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <h2>Bhujang Store</h2>
    <h3>Daftar Produk Sepatu</h3>

    <a href="add_product.php">+ Tambah Produk</a>

    <br><br>

    <form method="GET">
        <label>Filter Kategori:</label>

        <select name="category">
            <option value="">Semua Kategori</option>

            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['id']; ?>"
                    <?= (string)$category_id === (string)$category['id'] ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($category['nama_kategori']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Cari</button>
        <a href="list_products.php">Reset</a>
    </form>

    <table>
        <tr>
            <th>No</th>
            <th>Nama Produk</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>SKU</th>
            <th>Stok</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>

        <?php if (count($products) > 0): ?>

            <?php $no = 1; ?>

            <?php foreach ($products as $product): ?>

                <tr>
                    <td><?= $no++; ?></td>

                    <td>
                        <?= htmlspecialchars($product['nama_produk']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($product['nama_kategori'] ?? '-'); ?>
                    </td>

                    <td>
                        Rp <?= number_format($product['harga'], 0, ',', '.'); ?>
                    </td>

                    <td><?= htmlspecialchars($product['sku']); ?></td>

                    <td><?= htmlspecialchars($product['stok']); ?></td>

                    <td>
                        <?= $product['status'] == 1 ? 'Aktif' : 'Non-Aktif'; ?>
                    </td>

                    <td>
                        <a href="edit_product.php?id=<?= $product['id']; ?>">
                            Edit
                        </a>
                    </td>
                </tr>

            <?php endforeach; ?>

        <?php else: ?>

            <tr>
                <td colspan="8">Data tidak ditemukan</td>
            </tr>

        <?php endif; ?>

    </table>

</body>
</html>
            