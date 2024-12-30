<?php
ob_start(); // Start output buffering
require_once '../../includes/middleware.php';
include_once '../../config/database.php';


$database = new Database();
$db = $database->getConnection();

// Fetch available suppliers and items
$supplier_query = "SELECT supplier_id, nama_supplier FROM suppliers";
$supplier_stmt = $db->prepare($supplier_query);
$supplier_stmt->execute();

$item_query = "SELECT barang_id, nama_barang FROM barang";
$item_stmt = $db->prepare($item_query);
$item_stmt->execute();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tanggal_masuk = $_POST['tanggal_masuk'];
    $supplier_id = $_POST['supplier_id'];
    $barang_id = $_POST['barang_id'];
    $jumlah = $_POST['jumlah'];
    $keterangan = $_POST['keterangan'];

    $insert_query = "INSERT INTO barang_masuk (tanggal_masuk, supplier_id, barang_id, jumlah, keterangan) 
                     VALUES (:tanggal_masuk, :supplier_id, :barang_id, :jumlah, :keterangan)";
    $insert_stmt = $db->prepare($insert_query);
    $insert_stmt->bindParam(':tanggal_masuk', $tanggal_masuk);
    $insert_stmt->bindParam(':supplier_id', $supplier_id);
    $insert_stmt->bindParam(':barang_id', $barang_id);
    $insert_stmt->bindParam(':jumlah', $jumlah);
    $insert_stmt->bindParam(':keterangan', $keterangan);

    if ($insert_stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error adding incoming goods.";
    }
}
$title = "Tambah Barang Masuk";
include '../../includes/header.php';
?>
    <div class="container mt-4">
        <h1>Tambah Barang Masuk</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="mb-3">
                <label for="tanggal_masuk" class="form-label">Tanggal Masuk:</label>
                <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" required>
            </div>
            <div class="mb-3">
                <label for="supplier_id" class="form-label">Supplier:</label>
                <select class="form-select" id="supplier_id" name="supplier_id" required>
                    <option value="">Pilih Supplier</option>
                    <?php while ($row = $supplier_stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <option value="<?php echo $row['supplier_id']; ?>"><?php echo $row['nama_supplier']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="barang_id" class="form-label">Barang:</label>
                <select class="form-select" id="barang_id" name="barang_id" required>
                    <option value="">Pilih Barang</option>
                    <?php while ($row = $item_stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <option value="<?php echo $row['barang_id']; ?>"><?php echo $row['nama_barang']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="jumlah" class="form-label">Jumlah:</label>
                <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" required>
            </div>
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan:</label>
                <textarea class="form-control" id="keterangan" name="keterangan"></textarea>
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
</body>
</html>

<?php ob_end_flush(); // End output buffering ?>
