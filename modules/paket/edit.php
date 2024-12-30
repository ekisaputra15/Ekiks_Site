<?php
require_once '../../includes/middleware.php';
require_once '../../config/database.php';
require_once 'paket_model.php';
require_once '../barang/barang_model.php';

$database = new Database();
$db = $database->getConnection();

$paket_model = new PaketSembako($db);
$barang_model = new Barang($db);

// Pastikan ada ID paket yang valid
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$paket_id = $_GET['id'];
$paket_details = $paket_model->getPaketDetails($paket_id);

if (empty($paket_details)) {
    header("Location: index.php");
    exit();
}

// Ambil informasi paket (akan sama untuk semua baris)
$paket_info = $paket_details[0];

$barang_list = $barang_model->getAllBarang();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_paket = $_POST['nama_paket'];
    $deskripsi = $_POST['deskripsi'];
    $barang_dalam_paket = [];

    // Proses barang dalam paket
    foreach ($_POST['barang'] as $index => $barang_id) {
        if (!empty($barang_id) && !empty($_POST['jumlah'][$index])) {
            $barang_dalam_paket[] = [
                'barang_id' => $barang_id,
                'jumlah' => $_POST['jumlah'][$index]
            ];
        }
    }

    $result = $paket_model->updatePaket($paket_id, $nama_paket, $deskripsi, $barang_dalam_paket);

    if ($result) {
        header("Location: view.php?id=" . $paket_id . "&success=1");
        exit();
    } else {
        $error = "Gagal mengupdate paket sembako";
    }
}
?>

<?php $title = "Edit Paket Sembako - " . htmlspecialchars($paket_info['nama_paket']); ?>
<?php include '../../includes/header.php'; ?>
<div class="container mt-4">
    <h1>Edit Paket Sembako</h1>

    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <div class="form-group">
            <label for="nama_paket">Nama Paket</label>
            <input type="text" name="nama_paket" class="form-control" 
                   value="<?php echo htmlspecialchars($paket_info['nama_paket']); ?>" required>
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" class="form-control"><?php echo htmlspecialchars($paket_info['deskripsi']); ?></textarea>
        </div>

        <h3>Barang dalam Paket</h3>
        <div id="barang-container">
            <?php 
            $barang_sudah_ada = [];
            foreach ($paket_details as $barang):
                if (!empty($barang['nama_barang'])):
                    $barang_sudah_ada[] = $barang['barang_id'];
            ?>
                <div class="barang-item">
                    <select name="barang[]" class="form-control">
                        <option value="">Pilih Barang</option>
                        <?php 
                        $barang_list = $barang_model->getAllBarang(); 
                        while($b = $barang_list->fetch(PDO::FETCH_ASSOC)): 
                        ?>
                            <option value="<?php echo $b['barang_id']; ?>" 
                                <?php echo ($b['barang_id'] == $barang['barang_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($b['nama_barang']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <input type="number" name="jumlah[]" placeholder="Jumlah" 
                           class="form-control" min="1" 
                           value="<?php echo $barang['jumlah_barang']; ?>">
                </div>
            <?php 
                endif; 
            endforeach; 
            ?>
        </div>

        <button type="button" id="tambah-barang" class="btn btn-secondary">
            <i class="fas fa-plus"></i> Tambah Barang
        </button>

        <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
            <a href="view.php?id=<?php echo $paket_id; ?>" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </form>
</div>

<script>
document.getElementById('tambah-barang').addEventListener('click', function() {
    var container = document.getElementById('barang-container');
    var newItem = container.querySelector('.barang-item').cloneNode(true);
    
    // Reset pilihan dan input
    newItem.querySelector('select').selectedIndex = 0;
    newItem.querySelector('input[type="number"]').value = '';
    
    container.appendChild(newItem);
});
</script>

<?php include_once '../../includes/footer.php'; ?>
