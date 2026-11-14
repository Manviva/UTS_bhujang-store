
<?php
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Akses tidak diizinkan.");
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$nama = trim($_POST['nama_produk'] ?? '');
$deskripsi = trim($_POST['deskripsi'] ?? '');
$harga = $_POST['harga'] ?? '';
$sku = trim($_POST['sku'] ?? '');
$stok = $_POST['stok'] ?? '';
$category_id = filter_input(
    INPUT_POST,
    'category_id',
    FILTER_VALIDATE_INT
);
$status = $_POST['status'] ?? '';

if (
    !$id || $id < 1 ||
    $nama === '' ||
    $sku === '' ||
    !is_numeric($harga) ||
    !is_numeric($stok) ||
    $harga < 0 ||
    $stok < 0 ||
    !$category_id || $category_id < 1 ||
    !in_array((string)$status, ['0', '1'], true)
) {
    die("Data produk tidak valid.");
}

try {
    $pdo->beginTransaction();

    // Update data produk
    $sql = "UPDATE products SET
            nama_produk = :nama,
            deskripsi = :deskripsi,
            harga = :harga,
            sku = :sku,
            stok = :stok,
            status = :status
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':nama' => $nama,
        ':deskripsi' => $deskripsi,
        ':harga' => $harga,
        ':sku' => $sku,
        ':stok' => $stok,
        ':status' => $status,
        ':id' => $id
    ]);

    // Memperbarui kategori produk
    $stmt = $pdo->prepare(
        "DELETE FROM product_categories
         WHERE product_id = :id"
    );
    $stmt->execute([':id' => $id]);

    $stmt = $pdo->prepare(
        "INSERT INTO product_categories
         (product_id, category_id)
         VALUES (:product_id, :category_id)"
    );

    $stmt->execute([
        ':product_id' => $id,
        ':category_id' => $category_id
    ]);

    $pdo->commit();

    header("Location: list_products.php");
    exit;

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log($e->getMessage());
    die("Gagal memperbarui produk. Periksa SKU dan kategori.");
}
?>
