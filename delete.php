<?php
session_start();
require_once "config/Database.php";

$db = Database::getInstance()->getConnection();
$id = $_GET['id'] ?? '';

if (!is_numeric($id)) {
    header("Location: index.php");
    exit;
}
try {
    $db->beginTransaction();
    $query = "DELETE FROM produk WHERE id_produk = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$id]);
    $db->commit();

    $_SESSION['pesan'] = "Produk berhasil dihapus";
    $_SESSION['tipe'] = "sukses";
    
} catch (PDOException $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    $_SESSION['pesan'] = "Produk gagal dihapus";
    $_SESSION['tipe'] = "gagal";
}
header("Location: index.php");
exit;