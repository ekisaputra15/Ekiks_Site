<?php
ob_start(); // Start output buffering
require_once '../../includes/middleware.php';
include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $query = "UPDATE suppliers 
              SET nama_supplier = ?, alamat = ?, no_telepon = ?, email = ? 
              WHERE supplier_id = ?";
    $stmt = $db->prepare($query);
    
    try {
        $stmt->execute([
            $_POST['nama_supplier'],
            $_POST['alamat'],
            $_POST['no_telepon'],
            $_POST['email'],
            $_GET['id']
        ]);
        header('Location: index.php?success=1');
        exit;
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

$query = "SELECT * FROM suppliers WHERE supplier_id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$_GET['id']]);
$supplier = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$supplier) {
    header('Location: index.php');
    exit;
}
$title = "Edit Supplier";
include '../../includes/header.php';
?>

    <div class="container mt-5">
        <h1 class="mb-4 text-center">Edit Supplier</h1>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group mb-3">
                <label for="nama_supplier" class="form-label">Nama Supplier</label>
                <input type="text" id="nama_supplier" name="nama_supplier" class="form-control" 
                       value="<?php echo htmlspecialchars($supplier['nama_supplier']); ?>" required>
            </div>
            
            <div class="form-group mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea id="alamat" name="alamat" class="form-control" required><?php echo htmlspecialchars($supplier['alamat']); ?></textarea>
            </div>
            
            <div class="form-group mb-3">
                <label for="no_telepon" class="form-label">No. Telepon</label>
                <input type="text" id="no_telepon" name="no_telepon" class="form-control" 
                       value="<?php echo htmlspecialchars($supplier['no_telepon']); ?>">
            </div>
            
            <div class="form-group mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" 
                       value="<?php echo htmlspecialchars($supplier['email']); ?>">
            </div>
            
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-pencil-square"></i> Update
                </button>
                <a href="index.php" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
            </div>
        </form>
    </div>

    <?php include_once '../../includes/footer.php'; ob_end_flush(); ?>
