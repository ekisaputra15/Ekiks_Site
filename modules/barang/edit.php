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

$query = "SELECT * FROM barang WHERE barang_id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$_GET['id']]);
$barang = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$barang) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $query = "UPDATE barang SET kategori_id = ?, nama_barang = ?, satuan = ?, stok = ? WHERE barang_id = ?";
    $stmt = $db->prepare($query);
    
    try {
        $stmt->execute([
            $_POST['kategori_id'],
            $_POST['nama_barang'],
            $_POST['satuan'],
            $_POST['stok'],
            $_GET['id']
        ]);
        header('Location: index.php?success=1');
        exit;
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Fetch categories for the dropdown
$query = "SELECT * FROM kategori_barang ORDER BY nama_kategori ASC";
$stmt = $db->prepare($query);
$stmt->execute();
$kategori_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
$title = "Edit Barang";
include '../../includes/header.php';
?>
    <div class="container mt-4">
        <h1>Edit Barang</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="kategori_id" class="form-label">Kategori</label>
                <select name="kategori_id" id="kategori_id" class="form-select" required>
                    <option value="">Pilih Kategori</option>
                    <?php foreach ($kategori_list as $kategori): ?>
                        <option value="<?php echo $kategori['kategori_id']; ?>" <?php if ($kategori['kategori_id'] == $barang['kategori_id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($kategori['nama_kategori']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="nama_barang" class="form-label">Nama Barang</label>
                <input type="text" name="nama_barang" id="nama_barang" class="form-control" value="<?php echo htmlspecialchars($barang['nama_barang']); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="satuan" class="form-label">Satuan</label>
                <input type="text" name="satuan" id="satuan" class="form-control" value="<?php echo htmlspecialchars($barang['satuan']); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="stok" class="form-label">Stok</label>
                <input type="number" name="stok" id="stok" class="form-control" value="<?php echo htmlspecialchars($barang['stok']); ?>" required>
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

    <?php include_once '../../includes/footer.php'; ?>
</body>
</html>
<?php
// Flush output buffering
ob_end_flush();
?>
