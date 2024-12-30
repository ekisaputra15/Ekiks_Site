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
$title = "Daftar Barang Masuk";
include '../../includes/header.php';

?>

<div class="container mt-4">
    <h1 class="mb-4"></h1>
    <h2 class="text-center">Daftar Barang Masuk</h2>
    
    <div class="actions mb-3">
        <a href="add.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Barang Masuk</a>
    </div>

    <table class="table table-striped table-bordered">
        <thead class="table-light">
            <tr>
                <th>Tanggal Masuk</th>
                <th>Supplier</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
                <th>Aksi</th>
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
                    <td>
                        <a href="edit.php?id=<?php echo $row['barang_masuk_id']; ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="delete.php?id=<?php echo $row['barang_masuk_id']; ?>" class="btn btn-danger btn-sm" 
                           onclick="return confirm('Yakin ingin menghapus?')">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include_once '../../includes/footer.php'; ?>
