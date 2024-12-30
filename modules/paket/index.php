<?php
require_once '../../includes/middleware.php';
require_once '../../config/database.php';
require_once 'paket_model.php';

$database = new Database();
$db = $database->getConnection();

$paket_model = new PaketSembako($db);
$stmt = $paket_model->getAllPaket();
?>

<?php $title = "Daftar Paket Sembako"; ?>
<?php include '../../includes/header.php'; ?>

<div class="container mt-4">
    <h2 class="text-center">Daftar Paket Sembako</h2>
    
    <a href="add.php" class="btn btn-primary mb-4 mt-4">
        <i class="fas fa-plus"></i> Tambah Paket Baru
    </a>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Nama Paket</th>
                <th>Deskripsi</th>
                <th>Tanggal Dibuat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1; // Inisialisasi nomor urut
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): 
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($row['nama_paket']); ?></td>
                    <td><?php echo htmlspecialchars($row['deskripsi']); ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td>
                        <a href="view.php?id=<?php echo $row['paket_id']; ?>" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Lihat
                        </a>
                        <a href="edit.php?id=<?php echo $row['paket_id']; ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="delete.php?id=<?php echo $row['paket_id']; ?>" class="btn btn-danger btn-sm" 
                           onclick="return confirm('Yakin ingin menghapus paket ini?')">
                            <i class="fas fa-trash"></i> Hapus
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
    <?php
    if (isset($_GET['message'])) {
        ?>
        alert("<?= htmlentities($_GET['message']) ?>");
        <?php
    }
    ?>
</script>

<?php include_once '../../includes/footer.php'; ?>
