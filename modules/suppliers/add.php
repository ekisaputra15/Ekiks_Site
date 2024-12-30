<?php
ob_start(); // Start output buffering

include_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $db = $database->getConnection();

    $query = "INSERT INTO suppliers (nama_supplier, alamat, no_telepon, email) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    
    try {
        $stmt->execute([
            $_POST['nama_supplier'],
            $_POST['alamat'],
            $_POST['no_telepon'],
            $_POST['email']
        ]);
        header('Location: index.php?success=1');
        exit;
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
$title = "Tambah Supplier Baru";
include '../../includes/header.php';

?>
    <div class="container mt-5">
        <h1 class="mb-4">Tambah Supplier Baru</h1>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Nama Supplier</label>
                <input type="text" name="nama_supplier" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" required></textarea>
            </div>
            
            <div class="mb-3">
                <label class="form-label">No. Telepon</label>
                <input type="text" name="no_telepon" class="form-control">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control">
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

<?php
ob_end_flush(); // End output buffering
?>
