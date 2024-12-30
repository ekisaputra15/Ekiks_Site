<?php
require_once '../../includes/middleware.php';
include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

$query = "SELECT bm.*, s.nama_supplier, b.nama_barang 
          FROM barang_masuk bm
          JOIN suppliers s ON bm.supplier_id = s.supplier_id
          JOIN barang b ON bm.barang_id = b.barang_id
          ORDER BY bm.tanggal_masuk DESC";
$stmt = $db->prepare($query);
$stmt->execute();

$title = "Laporan Barang Masuk";
include '../../includes/header.php';

?>

<h1>Laporan Barang Masuk</h1>
<table class="table">
    <thead>
        <tr>
            <th>Tanggal Masuk</th>
            <th>Supplier</th>
            <th>Nama Barang</th>
            <th>Jumlah</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?php echo htmlspecialchars(date('d M Y', strtotime($row['tanggal_masuk']))); ?></td>
                <td><?php echo htmlspecialchars($row['nama_supplier']); ?></td>
                <td><?php echo htmlspecialchars($row['nama_barang']); ?></td>
                <td><?php echo htmlspecialchars($row['jumlah']); ?></td>
                <td><?php echo htmlspecialchars($row['keterangan']); ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>