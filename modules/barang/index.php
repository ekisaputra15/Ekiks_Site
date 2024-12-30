<?php
require_once '../../includes/middleware.php';
include_once '../../config/database.php';


$database = new Database();
$db = $database->getConnection();

$query = "SELECT b.*, k.nama_kategori 
          FROM barang b 
          LEFT JOIN kategori_barang k ON b.kategori_id = k.kategori_id 
          ORDER BY b.nama_barang ASC";
$stmt = $db->prepare($query);
$stmt->execute();
$title = "Daftar Barang";
include '../../includes/header.php';
?>
<div class="container mt-4">
    <h1 class="mb-4"></h1>
    <h2 class="text-center">Daftar Barang</h2>

    
    <div class="mb-3">
        <a href="add.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Barang</a>
    </div>

    <table class="table table-striped table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Satuan</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['nama_barang']); ?></td>
                    <td><?php echo htmlspecialchars($row['nama_kategori']); ?></td>
                    <td><?php echo htmlspecialchars($row['satuan']); ?></td>
                    <td><?php echo htmlspecialchars($row['stok']); ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['barang_id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</a>
                        <a href="delete.php?id=<?php echo $row['barang_id']; ?>" 
                           class="btn btn-sm btn-danger" 
                           onclick="return confirm('Yakin ingin menghapus?')"><i class="fas fa-trash-alt"></i> Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include_once '../../includes/footer.php'; ?>
</body>
</html>
