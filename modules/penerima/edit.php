<?php
ob_start();
require_once '../../includes/middleware.php';
require_once '../../config/database.php';

// Inisialisasi kelas Database
$database = new Database();
$conn = $database->getConnection();

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

// Ambil ID yang di-escape untuk menghindari injeksi SQL
$id = $conn->quote($_GET['id']);
$query = "SELECT * FROM penerima WHERE penerima_id = $id";
$stmt = $conn->query($query);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nik = $conn->quote($_POST['nik']);
    $nama = $conn->quote($_POST['nama']);
    $alamat = $conn->quote($_POST['alamat']);
    $no_telepon = $conn->quote($_POST['no_telepon']);
    $status_ekonomi = $conn->quote($_POST['status_ekonomi']);
    $jumlah_keluarga = $conn->quote($_POST['jumlah_keluarga']);

    // Validasi NIK unik (kecuali untuk data yang sedang diedit)
    $check_nik = $conn->query("SELECT nik FROM penerima WHERE nik = $nik AND penerima_id != $id");
    if ($check_nik->rowCount() > 0) {
        $_SESSION['message'] = "NIK sudah terdaftar!";
        $_SESSION['msg_type'] = "danger";
    } else {
        $query = "UPDATE penerima SET 
                  nik = $nik,
                  nama = $nama,
                  alamat = $alamat,
                  no_telepon = $no_telepon,
                  status_ekonomi = $status_ekonomi,
                  jumlah_keluarga = $jumlah_keluarga
                  WHERE penerima_id = $id";

        if ($conn->exec($query)) {
            $_SESSION['message'] = "Data penerima berhasil diperbarui!";
            $_SESSION['msg_type'] = "success";
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['message'] = "Error: " . $conn->errorInfo()[2];
            $_SESSION['msg_type'] = "danger";
        }
    }
}
$title = "Edit Data Penerima";
include '../../includes/header.php';

?>
<div class="container mt-4">
    <h2>Edit Data Penerima</h2>
    
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['msg_type'] ?>">
            <?= $_SESSION['message'] ?>
        </div>
        <?php 
        unset($_SESSION['message']);
        unset($_SESSION['msg_type']);
        endif; 
    ?>

    <form action="edit.php?id=<?= $id ?>" method="POST">
        <div class="mb-3">
            <label for="nik" class="form-label">NIK</label>
            <input type="text" class="form-control" id="nik" name="nik" required maxlength="16" 
                   pattern="[0-9]{16}" value="<?= htmlspecialchars($data['nik']) ?>">
        </div>
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="nama" name="nama" required 
                   value="<?= htmlspecialchars($data['nama']) ?>">
        </div>
        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?= htmlspecialchars($data['alamat']) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="no_telepon" class="form-label">No. Telepon</label>
            <input type="text" class="form-control" id="no_telepon" name="no_telepon" 
                   value="<?= htmlspecialchars($data['no_telepon']) ?>">
        </div>
        <div class="mb-3">
            <label for="status_ekonomi" class="form-label">Status Ekonomi</label>
            <select class="form-control" id="status_ekonomi" name="status_ekonomi" required>
                <option value="">Pilih Status Ekonomi</option>
                <option value="Tidak Mampu" <?= $data['status_ekonomi'] == 'Tidak Mampu' ? 'selected' : '' ?>>Tidak Mampu</option>
                <option value="Kurang Mampu" <?= $data['status_ekonomi'] == 'Kurang Mampu' ? 'selected' : '' ?>>Kurang Mampu</option>
                <option value="Menengah" <?= $data['status_ekonomi'] == 'Menengah' ? 'selected' : '' ?>>Menengah</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="jumlah_keluarga" class="form-label">Jumlah Anggota Keluarga</label>
            <input type="number" class="form-control" id="jumlah_keluarga" name="jumlah_keluarga" 
                   required min="1" value="<?= htmlspecialchars($data['jumlah_keluarga']) ?>">
        </div>
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Simpan Perubahan
            </button>
            <a href="index.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle"></i> Kembali
            </a>
        </div>
    </form>
</div>

<?php require_once '../../includes/footer.php'; ?>
<?php
// Flush output buffering
ob_end_flush();
?>
