<?php
require_once '../../includes/middleware.php';
include_once '../../config/database.php';
include '../../includes/header.php';

$database = new Database();
$db = $database->getConnection();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $select_query = "SELECT bm.*, s.nama_supplier, b.nama_barang 
                     FROM barang_masuk bm
                     JOIN suppliers s ON bm.supplier_id = s.supplier_id
                     JOIN barang b ON bm.barang_id = b.barang_id
                     WHERE bm.barang_masuk_id = :id";
    $select_stmt = $db->prepare($select_query);
    $select_stmt->bindParam(':id', $id);
    $select_stmt->execute();
    $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
} else {
    header("Location: index.php");
    exit;
}
?>

<h1>Detail Barang Masuk</h1>
<table class="table">
    <tr>
        <th>Tanggal Masuk:</th>
        <td><?php echo htmlspecialchars(date('d M Y', strtotime($row['tanggal_masuk']))); ?></td>
    </tr>
    <tr>
        <th>Supplier:</th>
        <td><?php echo htmlspecialchars($row['nama_supplier']); ?></td>
    </tr>
    <tr>
        <th>Nama Barang:</th>
        <td><?php echo htmlspecialchars($row['nama_barang']); ?></td>
    </tr>
    <tr>
        <th>Jumlah:</th>
        <td><?php echo htmlspecialchars($row['jumlah']); ?></td>
    </tr>
    <tr>
        <th>Keterangan:</th>
        <td><?php echo htmlspecialchars($row['keterangan']); ?></td>
    </tr>
</table>
<a href="index.php" class="btn btn-primary">Kembali ke Daftar Barang Masuk</a>