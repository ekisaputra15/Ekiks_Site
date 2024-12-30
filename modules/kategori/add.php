<?php
ob_start(); // Start output buffering
require_once '../../includes/middleware.php';
include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $query = "INSERT INTO kategori_barang (nama_kategori, deskripsi) VALUES (?, ?)";
    $stmt = $db->prepare($query);
    
    try {
        $stmt->execute([
            $_POST['nama_kategori'],
            $_POST['deskripsi']
        ]);
        header('Location: index.php?success=1');
        exit;
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
$title = "Tambah Kategori Barang";
include '../../includes/header.php';
?>

    <div class="container mt-4">
        <h1 class="mb-4">Tambah Kategori Barang Baru</h1>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="" class="mt-3">
            <div class="mb-3">
                <label for="nama_kategori" class="form-label">Nama Kategori</label>
                <input type="text" name="nama_kategori" id="nama_kategori" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4"></textarea>
            </div>
            
            <div class="d-flex justify-content-between">
                <!-- Save button with icon -->
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <!-- Cancel button with icon -->
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>

    <?php include_once '../../includes/footer.php'; ?>


<?php ob_end_flush(); // End output buffering ?>
