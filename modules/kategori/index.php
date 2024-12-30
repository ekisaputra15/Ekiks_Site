<?php

require_once '../../includes/middleware.php';
include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM kategori_barang ORDER BY nama_kategori ASC";
$stmt = $db->prepare($query);
$stmt->execute();
$title = "Daftar Kategori Barang";
include '../../includes/header.php';
?>

<div class="container mt-4">
    <h1 class="mb-4"></h1>
    <h2 class="text-center">Daftar Kategori Barang</h2>
    <div class="mb-3">
        <a href="add.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Kategori</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nama_kategori']); ?></td>
                        <td><?php echo htmlspecialchars($row['deskripsi']); ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $row['kategori_id']; ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="delete.php?id=<?php echo $row['kategori_id']; ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('Yakin ingin menghapus?')">
                                <i class="fas fa-trash"></i> Hapus
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>
