<?php
session_start();
require_once "config/Database.php";
$db = Database::getInstance()->getConnection();

$kategori = $db->query(
    "SELECT * FROM kategori ORDER BY nama_kategori"
)->fetchALL(PDO::FETCH_ASSOC);

$supplier = $db->query(
    "SELECT * FROM supplier ORDER BY nama_supplier"
)->fetchALL(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_produk = trim($_POST['nama_produk']);
    $harga = trim($_POST['harga']);
    $stok = trim($_POST['stok']);
    $id_kategori = $_POST['id_kategori'];
    $id_supplier = $_POST['id_supplier'];

    if (
        $nama_produk === '' ||
        $harga === '' ||
        $stok === '' ||
        $id_kategori === '' ||
        $id_supplier === ''
    ) {
        $_SESSION['pesan'] = "Semua data harus diisi";
        $_SESSION['tipe'] = "gagal";
        header("Location: create.php");
        exit;
    }

    try {
        $query ="INSERT INTO produk
                (nama_produk, harga, stok, id_kategori, id_supplier)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->execute([
            $nama_produk,
            $harga,
            $stok,
            $id_kategori,
            $id_supplier
        ]);

        $_SESSION['pesan'] = "Produk berhasil ditambahkan";
        $_SESSION['tipe'] = "sukses";
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION['pesan'] = "Produk gagal ditambahkan";
        $_SESSION['tipe'] = "gagal";
        header("Location: create.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Tambah Produk</h1>
        <form method="POST">
            <label>Nama Produk</label>
            <input type="text" name="nama_produk">
            <label>Harga</label>
            <input type="number" name="harga">
            <label>Stok</label>
            <input type="number" name="stok">

            <label>Kategori</label>
            <select name="id_kategori">
                <option value="">-- Pilih Kategori --</option>
                <?php foreach ($kategori as $item): ?>
                    <option value="<?= $item['id_kategori'] ?>">
                        <?= htmlspecialchars($item['nama_kategori']) ?>
                    </option>
                 <?php endforeach; ?>

                </select>

                <label>Supplier</label>
                <select name="id_supplier">
                    <option value="">-- Pilih Supplier --</option>
                    <?php foreach ($supplier as $item): ?>
                        <option value="<?= $item['id_supplier'] ?>">
                            <?= htmlspecialchars($item['nama_supplier']) ?>
                        </option>
                    <?php endforeach; ?>
                    </select>

                    <button type="submit" class="btn tambah">
                        Simpan
                    </button>
                    <a href="index.php" class="btn">
                        Kembali
                    </a>
    </form>
</div>
</body>
</html>




