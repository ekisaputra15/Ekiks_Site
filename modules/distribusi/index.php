<?php
require_once '../../includes/middleware.php';
require_once '../../config/database.php';

// Inisialisasi kelas Database
$database = new Database();
$conn = $database->getConnection();

// Query untuk mengambil data distribusi sembako
$query = "
    SELECT 
        d.distribusi_id,
        p.nama AS nama_penerima,
        ps.nama_paket,
        d.tanggal_distribusi,
        d.status_pengambilan,
        d.tanggal_pengambilan,
        d.petugas_penyerah
    FROM distribusi_sembako d
    JOIN penerima p ON d.penerima_id = p.penerima_id
    JOIN paket_sembako ps ON d.paket_id = ps.paket_id
    ORDER BY d.tanggal_distribusi DESC
";

$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$title = "Data Distribusi Sembako";
include '../../includes/header.php';

?>

<div class="container mt-4">
    <h1 class="mb-4"></h1>
    <h2 class="text-center">Data Distribusi Sembako</h2>
    <a href="create.php" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> Tambah Distribusi Baru
    </a>
    
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Penerima</th>
                    <th>Paket Sembako</th>
                    <th>Tanggal Distribusi</th>
                    <th>Status</th>
                    <th>Tanggal Pengambilan</th>
                    <th>Petugas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                foreach ($result as $row): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama_penerima']) ?></td>
                        <td><?= htmlspecialchars($row['nama_paket']) ?></td>
                        <td><?= date('d/m/Y', strtotime($row['tanggal_distribusi'])) ?></td>
                        <td>
                            <?php if ($row['status_pengambilan'] == 'belum_diambil'): ?>
                                <span class="badge bg-warning">Belum Diambil</span>
                            <?php else: ?>
                                <span class="badge bg-success">Sudah Diambil</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $row['tanggal_pengambilan'] ? date('d/m/Y H:i', strtotime($row['tanggal_pengambilan'])) : '-' ?></td>
                        <td><?= htmlspecialchars($row['petugas_penyerah']) ?></td>
                        <td>
                            <?php if ($row['status_pengambilan'] == 'belum_diambil'): ?>
                                <a href="update-status.php?id=<?= $row['distribusi_id'] ?>" 
                                   class="btn btn-success btn-sm"
                                   onclick="return confirm('Konfirmasi pengambilan sembako?')">
                                    Update Status
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
