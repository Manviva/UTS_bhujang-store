
<?php
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nama_produk = trim($_POST["nama_produk"]);
    $deskripsi = trim($_POST["deskripsi"]);
    $harga = $_POST["harga"];
    $sku = trim($_POST["sku"]);
    $stok = $_POST["stok"];

    // Validasi data
    if (
        $nama_produk == "" ||
        $sku == "" ||
        !is_numeric($harga) ||
        !is_numeric($stok) ||
        $harga < 0 ||
        $stok < 0
    ) {
        die("Data produk tidak valid!");
    }

    try {
        $sql = "INSERT INTO products
                (nama_produk, deskripsi, harga, sku, stok)
                VALUES
                (:nama, :deskripsi, :harga, :sku, :stok)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ":nama" => $nama_produk,
            ":deskripsi" => $deskripsi,
            ":harga" => $harga,
            ":sku" => $sku,
            ":stok" => $stok
        ]);

        echo "Produk berhasil ditambahkan!";
        echo "<br><a href='add_product.php'>Tambah produk lagi</a>";

    } catch (PDOException $e) {
        error_log($e->getMessage());
        echo "Gagal menyimpan produk. Periksa kembali data yang dimasukkan.";
    }

} else {
    echo "Akses tidak diizinkan!";
}
?>
