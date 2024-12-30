<?php
require_once '../../includes/middleware.php';
require_once '../../config/database.php';

// Query untuk mengambil data penerima
$database = new Database();
$db = $database->getConnection();
$query = "SELECT * FROM penerima ORDER BY created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$title = "Daftar Penerima Sembako";
include '../../includes/header.php';
?>

<div class="container mt-4">
    <div class="mb-4">
        <h2 class="text-center">Daftar Penerima Sembako</h2>
        <a href="add.php" class="btn btn-primary mt-4"><i class="fas fa-plus"></i> Tambah Penerima Baru</a>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['msg_type'] ?> alert-dismissible fade show" role="alert">
            <strong>Notice!</strong> <?= $_SESSION['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php 
        unset($_SESSION['message']);
        unset($_SESSION['msg_type']);
        endif; 
    ?>

    <div class="mb-3">
        <input type="text" class="form-control" id="searchInput" placeholder="Cari penerima...">
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-light">
                <tr>
                    <th>No.</th>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>No. Telepon</th>
                    <th>Status Ekonomi</th>
                    <th>Jumlah Keluarga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="recipientTable">
                <?php 
                $no = 1;
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): 
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nik']) ?></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= htmlspecialchars($row['alamat']) ?></td>
                    <td><?= htmlspecialchars($row['no_telepon']) ?></td>
                    <td><?= htmlspecialchars($row['status_ekonomi']) ?></td>
                    <td><?= htmlspecialchars($row['jumlah_keluarga']) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $row['penerima_id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</a>
                        <a href="delete.php?id=<?= $row['penerima_id'] ?>" class="btn btn-sm btn-danger" 
                           onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"><i class="fas fa-trash"></i> Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // Search functionality
    document.getElementById("searchInput").addEventListener("keyup", function() {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll("#recipientTable tr");
        rows.forEach(row => {
            const cells = row.getElementsByTagName("td");
            let found = false;
            for (let i = 0; i < cells.length - 1; i++) { // Exclude the last cell (Actions)
                if (cells[i].innerText.toLowerCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }
            row.style.display = found ? "" : "none";
        });
    });
</script>

<?php require_once '../../includes/footer.php'; ?>
