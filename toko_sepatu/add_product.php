
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk Sepatu</title>

    <style>
        body {
            font-family: Arial;
            margin: 30px;
        }

        label {
            display: inline-block;
            width: 130px;
        }

        input, textarea {
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
    <h3>Tambah Produk Sepatu</h3>

    <form action="simpan.php" method="POST">

        <p>
            <label>Nama Sepatu:</label>
            <input type="text" name="nama_produk" required>
        </p>

        <p>
            <label>Deskripsi:</label>
            <textarea name="deskripsi"></textarea>
        </p>

        <p>
            <label>Harga:</label>
            <input type="number" name="harga" min="0" step="0.01" required>
        </p>

        <p>
            <label>SKU / Kode Produk:</label>
            <input type="text" name="sku" required>
        </p>

        <p>
            <label>Stok:</label>
            <input type="number" name="stok" min="0" required>
        </p>

        <br>

        <button type="submit">Simpan Produk</button>

        <a href="list_products.php">Kembali</a>

    </form>

</body>
</html>
