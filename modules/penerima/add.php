<?php 
ob_start(); // Start output buffering
require_once '../../includes/middleware.php';
require_once '../../config/database.php';

// Inisialisasi kelas Database
$database = new Database();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Data yang diambil dari input form
    $nik = $_POST['nik'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_telepon = $_POST['no_telepon'];
    $status_ekonomi = $_POST['status_ekonomi'];
    $jumlah_keluarga = $_POST['jumlah_keluarga'];

    // Validasi NIK unik
    $check_nik_query = "SELECT nik FROM penerima WHERE nik = :nik";
    $stmt_check_nik = $conn->prepare($check_nik_query);
    $stmt_check_nik->bindParam(':nik', $nik);
    $stmt_check_nik->execute();

    if ($stmt_check_nik->rowCount() > 0) {
        $_SESSION['message'] = "NIK sudah terdaftar!";
        $_SESSION['msg_type'] = "danger";
    } else {
        // Query untuk menambahkan data penerima baru
        $insert_query = "INSERT INTO penerima (nik, nama, alamat, no_telepon, status_ekonomi, jumlah_keluarga) 
                         VALUES (:nik, :nama, :alamat, :no_telepon, :status_ekonomi, :jumlah_keluarga)";
        $stmt_insert = $conn->prepare($insert_query);
        $stmt_insert->bindParam(':nik', $nik);
        $stmt_insert->bindParam(':nama', $nama);
        $stmt_insert->bindParam(':alamat', $alamat);
        $stmt_insert->bindParam(':no_telepon', $no_telepon);
        $stmt_insert->bindParam(':status_ekonomi', $status_ekonomi);
        $stmt_insert->bindParam(':jumlah_keluarga', $jumlah_keluarga);

        if ($stmt_insert->execute()) {
            $_SESSION['message'] = "Data penerima berhasil ditambahkan!";
            $_SESSION['msg_type'] = "success";
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['message'] = "Error: " . $stmt_insert->errorInfo()[2];
            $_SESSION['msg_type'] = "danger";
        }
    }
}
$title = "Tambah Penerima Baru";
include '../../includes/header.php';

?>

<div class="container mt-4">
    <h2 class="mb-4">Tambah Penerima Baru</h2>
    
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['msg_type'] ?> alert-dismissible fade show" role="alert">
            <?= $_SESSION['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php 
        unset($_SESSION['message']);
        unset($_SESSION['msg_type']);
        endif; 
    ?>

    <form action="add.php" method="POST">
        <div class="mb-3">
            <label for="nik" class="form-label">NIK</label>
            <input type="text" class="form-control" id="nik" name="nik" required maxlength="16" pattern="[0-9]{16}" title="NIK harus 16 digit angka">
        </div>
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="nama" name="nama" required>
        </div>
        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="no_telepon" class="form-label">No. Telepon</label>
            <input type="text" class="form-control" id="no_telepon" name="no_telepon" placeholder="Opsional">
        </div>
        <div class="mb-3">
            <label for="status_ekonomi" class="form-label">Status Ekonomi</label>
            <select class="form-select" id="status_ekonomi" name="status_ekonomi" required>
                <option value="">Pilih Status Ekonomi</option>
                <option value="Tidak Mampu">Tidak Mampu</option>
                <option value="Kurang Mampu">Kurang Mampu</option>
                <option value="Menengah">Menengah</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="jumlah_keluarga" class="form-label">Jumlah Anggota Keluarga</label>
            <input type="number" class="form-control" id="jumlah_keluarga" name="jumlah_keluarga" required min="1">
        </div>
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Simpan
            </button>
            <a href="index.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle"></i> Kembali
            </a>
        </div>
    </form>
</div>

<?php require_once '../../includes/footer.php'; ?>
<?php ob_end_flush(); // Flush output buffer ?>
