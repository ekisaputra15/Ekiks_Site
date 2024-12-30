<?php
require_once '../../includes/middleware.php';
require_once '../../config/database.php';

// Inisialisasi kelas Database
$database = new Database();
$conn = $database->getConnection();

// Query untuk mengambil data penerima
$query_penerima = "SELECT penerima_id, nama, nik FROM penerima ORDER BY nama";
$stmt_penerima = $conn->prepare($query_penerima);
$stmt_penerima->execute();
$penerima_result = $stmt_penerima->fetchAll(PDO::FETCH_ASSOC);

// Query untuk mengambil data paket sembako
$query_paket = "SELECT paket_id, nama_paket FROM paket_sembako ORDER BY nama_paket";
$stmt_paket = $conn->prepare($query_paket);
$stmt_paket->execute();
$paket_result = $stmt_paket->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $penerima_id = $_POST['penerima_id'];
    $paket_id = $_POST['paket_id'];
    $tanggal_distribusi = $_POST['tanggal_distribusi'];
    $keterangan = $_POST['keterangan'];
    
    $query = "INSERT INTO distribusi_sembako (penerima_id, paket_id, tanggal_distribusi, keterangan) 
              VALUES (?, ?, ?, ?)";
    
    $stmt = $conn->prepare($query);
    
    if ($stmt->execute([$penerima_id, $paket_id, $tanggal_distribusi, $keterangan])) {
        echo "<script>alert('Data distribusi berhasil ditambahkan!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data distribusi!');</script>";
    }
}
$title = "Tambah Data Distribusi Sembako";
include '../../includes/header.php';

?>

<div class="container mt-4">
    <h2 class="mb-4">Tambah Data Distribusi Sembako</h2>
    
    <form method="POST" class="mt-3">
        <div class="mb-3">
            <label for="penerima_id" class="form-label">Penerima</label>
            <select class="form-select" name="penerima_id" required>
                <option value="">Pilih Penerima</option>
                <?php foreach ($penerima_result as $row) { ?>
                    <option value="<?= $row['penerima_id'] ?>">
                        <?= htmlspecialchars($row['nama']) ?> - <?= htmlspecialchars($row['nik']) ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        
        <div class="mb-3">
            <label for="paket_id" class="form-label">Paket Sembako</label>
            <select class="form-select" name="paket_id" required>
                <option value="">Pilih Paket</option>
                <?php foreach ($paket_result as $row) { ?>
                    <option value="<?= $row['paket_id'] ?>">
                        <?= htmlspecialchars($row['nama_paket']) ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        
        <div class="mb-3">
            <label for="tanggal_distribusi" class="form-label">Tanggal Distribusi</label>
            <input type="date" class="form-control" name="tanggal_distribusi" required>
        </div>
        
        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea class="form-control" name="keterangan" rows="3"></textarea>
        </div>
        
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>
</div>

<?php require_once '../../includes/footer.php'; ?>
