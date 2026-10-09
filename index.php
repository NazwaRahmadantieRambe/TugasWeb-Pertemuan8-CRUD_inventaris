<?php
session_start();
require_once "config/Database.php";

$db = Database::getInstance()->getConnection();
$query = "SELECT produk.*, kategori.nama_kategori
        FROM produk
        JOIN kategori
        ON produk.id_kategori = kategori.id_kategori
        ORDER BY produk.id_produk DESC";
$stmt = $db->prepare($query);
$stmt->execute();

$produk = $stmt->fetchAll(PDO::FETCH_ASSOC);
$pesan = $_SESSION['pesan'] ?? '';
$tipe = $_SESSION['tipe'] ?? '';

unset($_SESSION['pesan']);
unset($_SESSION['tipe']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Inventaris Sepatu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>CRUD Inventaris Sepatu</h1>
        <a href="create.php" class="btn tambah">
            + Tambah produk
        </a>

        <?php if ($pesan): ?>
            <div class="flash <?= htmlspecialchars($tipe) ?>">
                <?=htmlspecialchars($pesan) ?>
            </div>

        <?php endif; ?>
        <table>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
        </thead>

        <tbody>
            <?php $no = 1; ?>
            <?php foreach ($produk as $item): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($item['nama_produk']) ?></td>
                    <td><?= htmlspecialchars($item['nama_kategori']) ?></td>
                    <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                    <td><?= htmlspecialchars($item['stok']) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $item['id_produk'] ?>" class="btn edit">
                            Edit
                        </a>
                        
                        <a href="delete.php?id=<?= $item['id_produk'] ?>"
                            class="btn hapus"
                            onclick="return confirm('Apakah kamu yakin ingin menghapus produk ini')">
                                hapus
                        </a>
                    </td>
                </tr>

             <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>




            
                    