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

// Fetch incoming goods details
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $select_query = "SELECT * FROM barang_masuk WHERE barang_masuk_id = :id";
    $select_stmt = $db->prepare($select_query);
    $select_stmt->bindParam(':id', $id);
    $select_stmt->execute();
    $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
} else {
    header("Location: index.php");
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tanggal_masuk = $_POST['tanggal_masuk'];
    $supplier_id = $_POST['supplier_id'];
    $barang_id = $_POST['barang_id'];
    $jumlah = $_POST['jumlah'];
    $keterangan = $_POST['keterangan'];

    $update_query = "UPDATE barang_masuk 
                     SET tanggal_masuk = :tanggal_masuk, 
                         supplier_id = :supplier_id,
                         barang_id = :barang_id, 
                         jumlah = :jumlah,
                         keterangan = :keterangan
                     WHERE barang_masuk_id = :id";
    $update_stmt = $db->prepare($update_query);
    $update_stmt->bindParam(':tanggal_masuk', $tanggal_masuk);
    $update_stmt->bindParam(':supplier_id', $supplier_id);
    $update_stmt->bindParam(':barang_id', $barang_id);
    $update_stmt->bindParam(':jumlah', $jumlah);
    $update_stmt->bindParam(':keterangan', $keterangan);
    $update_stmt->bindParam(':id', $id);

    if ($update_stmt->execute()) {
        echo '<div class="alert alert-success" role="alert">Barang Masuk berhasil diperbarui!</div>';
        header("Refresh: 2; url=index.php");
        exit;
    } else {
        echo '<div class="alert alert-danger" role="alert">Error updating incoming goods.</div>';
    }
}
$title = "Edit Barang Masuk";
include '../../includes/header.php';
?>

<div class="container mt-4">
    <h1 class="text-center mb-4">Edit Barang Masuk</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $row['barang_masuk_id']);?>">
        <div class="mb-3">
            <label for="tanggal_masuk" class="form-label">Tanggal Masuk:</label>
            <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" value="<?php echo $row['tanggal_masuk']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="supplier_id" class="form-label">Supplier:</label>
            <select class="form-select" id="supplier_id" name="supplier_id" required>
                <option value="">Pilih Supplier</option>
                <?php while ($sup_row = $supplier_stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <option value="<?php echo $sup_row['supplier_id']; ?>" <?php echo ($sup_row['supplier_id'] == $row['supplier_id']) ? 'selected' : ''; ?>><?php echo $sup_row['nama_supplier']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="barang_id" class="form-label">Barang:</label>
            <select class="form-select" id="barang_id" name="barang_id" required>
                <option value="">Pilih Barang</option>
                <?php while ($item_row = $item_stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <option value="<?php echo $item_row['barang_id']; ?>" <?php echo ($item_row['barang_id'] == $row['barang_id']) ? 'selected' : ''; ?>><?php echo $item_row['nama_barang']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah:</label>
            <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" value="<?php echo $row['jumlah']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan:</label>
            <textarea class="form-control" id="keterangan" name="keterangan"><?php echo $row['keterangan']; ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        <a href="index.php" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
    </form>
</div>

<!-- Include Font Awesome for icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<?php 
include_once '../../includes/footer.php'; 
ob_end_flush(); // Flush the output buffer
?>
