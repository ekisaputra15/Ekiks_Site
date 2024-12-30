<?php
ob_start(); // Start output buffering
require_once '../../includes/middleware.php';

include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Ambil daftar kategori
$query = "SELECT * FROM kategori_barang ORDER BY nama_kategori ASC";
$stmt = $db->prepare($query);
$stmt->execute();
$kategori_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $query = "INSERT INTO barang (kategori_id, nama_barang, satuan, stok) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    
    try {
        $stmt->execute([
            $_POST['kategori_id'],
            $_POST['nama_barang'],
            $_POST['satuan'],
            $_POST['stok']
        ]);
        header('Location: index.php?success=1');
        exit;
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
$title = "Tambah Barang Baru";
include '../../includes/header.php';

?>
<div class="container mt-4">
    <h1 class="text-center mb-4">Tambah Barang Baru</h1>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="kategori_id" class="form-label">Kategori</label>
            <select name="kategori_id" id="kategori_id" class="form-select" required>
                <option value="">Pilih Kategori</option>
                <?php foreach ($kategori_list as $kategori): ?>
                    <option value="<?php echo $kategori['kategori_id']; ?>">
                        <?php echo htmlspecialchars($kategori['nama_kategori']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="mb-3">
            <label for="nama_barang" class="form-label">Nama Barang</label>
            <input type="text" name="nama_barang" id="nama_barang" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label for="satuan" class="form-label">Satuan</label>
            <input type="text" name="satuan" id="satuan" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label for="stok" class="form-label">Stok Awal</label>
            <input type="number" name="stok" id="stok" class="form-control" value="0" required>
        </div>
        
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            <a href="index.php" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
        </div>
    </form>
</div>

<?php include_once '../../includes/footer.php'; ob_end_flush(); // End output buffering ?>
</body>
</html>
