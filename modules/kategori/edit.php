<?php
// Start output buffering
ob_start();
require_once '../../includes/middleware.php';
include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $query = "UPDATE kategori_barang 
               SET nama_kategori = ?, deskripsi = ? 
               WHERE kategori_id = ?";
    $stmt = $db->prepare($query);

    try {
        $stmt->execute([
            $_POST['nama_kategori'],
            $_POST['deskripsi'],
            $_GET['id']
        ]);
        header('Location: index.php?success=1');
        exit;
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

$query = "SELECT * FROM kategori_barang WHERE kategori_id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$_GET['id']]);
$kategori_barang = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$kategori_barang) {
    header('Location: index.php');
    exit;
}
$title = "Edit Kategori Barang";
include '../../includes/header.php';
?>

<h1>Edit Kategori Barang</h1>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST" action="">
    <div class="form-group">
        <label>Nama Kategori</label>
        <input type="text" name="nama_kategori" value="<?php echo htmlspecialchars($kategori_barang['nama_kategori']); ?>" required>
    </div>

    <div class="form-group">
        <label>Deskripsi</label>
        <textarea name="deskripsi"><?php echo htmlspecialchars($kategori_barang['deskripsi']); ?></textarea>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </div>
</form>

<?php include_once '../../includes/footer.php'; ?>

<?php
// Flush output buffering
ob_end_flush();
?>
