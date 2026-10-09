<?php
session_start();
require_once "config/Database.php";
$db = Database::getInstance()->getConnection();

$id = $_GET['id'] ?? '';
if (!is_numeric($id)) {
    header("Location: index.php");
    exit;
}

$query = "SELECT * FROM produk WHERE id_produk = ?";
$stmt = $db->prepare($query);
$stmt->execute([$id]);

$produk = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$produk) {
    header("Location: index.php");
    exit;
}

$kategori = $db->query(
    "SELECT * FROM kategori ORDER BY nama_kategori"
)->fetchALL(PDO::FETCH_ASSOC);
$supplier = $db->query(
    "SELECT * FROM supplier ORDER BY nama_supplier"
)->fetchAll(PDO::FETCH_ASSOC);

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
        header("Location: edit.php?id=" . $id);
        exit;
    }

    try {
        $query = "UPDATE produk
                SET nama_produk = ?,
                    harga = ?,
                    stok = ?,
                    id_kategori = ?,
                    id_supplier = ?
                    WHERE id_produk = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([
            $nama_produk,
            $harga,
            $stok,
            $id_kategori,
            $id_supplier,
            $id
        ]);

        $_SESSION['pesan'] = "Produk berhasil diperbarui";
        $_SESSION['tipe'] = "sukses";
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION['pesan'] = "Produk gagal diperbarui";
        $_SESSION['tipe'] = "gagal";
        header("Location: edit.php?id=" . $id);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h1>Edit Produk</h1>
        <form method="POST">
            <label>Nama Produk</label>
            <input type="text"
                    name="nama_produk"
                    value="<?= htmlspecialchars($produk['nama_produk']) ?>">
            <label>Harga</label>
            <input type="number"
                    name="harga"
                    value="<?= htmlspecialchars($produk['harga']) ?>">
            <label>Stok</label>
            <input type="number"
                    name="stok"
                    value="<?= htmlspecialchars($produk['stok']) ?>">

            <label>Kategori</label>
            <select name="id_kategori">
                <?php foreach ($kategori as $item): ?>
                    <option
                        value="<?= $item['id_kategori'] ?>"
                        <?= $item['id_kategori'] == $produk['id_kategori'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($item['nama_kategori']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <label>Supplier</label>
            <select name="id_supplier">
                <?php foreach ($supplier as $item): ?>
                    <option
                        value="<?= $item['id_supplier'] ?>"
                        <?= $item['id_supplier'] == $produk['id_supplier'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($item['nama_supplier']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn edit">
                Update
            </button>
            <a href="index.php" class="btn">
                Kembali
            </a>
        </form>
    </div>
</body>
</html>

                    


