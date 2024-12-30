<?php
ob_start(); // Start output buffering
require_once '../../includes/middleware.php';
require_once '../../config/database.php';
require_once 'paket_model.php';
require_once '../barang/barang_model.php';  // Pastikan Anda memiliki model untuk barang

$database = new Database();
$db = $database->getConnection();

$paket_model = new PaketSembako($db);
$barang_model = new Barang($db);  // Sesuaikan dengan model barang Anda

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

    $result = $paket_model->createPaket($nama_paket, $deskripsi, $barang_dalam_paket);

    if ($result) {
        header("Location: index.php?success=1");
        exit();
    } else {
        $error = "Gagal membuat paket sembako";
    }
}
$title = "Tambah Paket Sembako Baru";
include '../../includes/header.php';
?>

    <div class="container">
        <h1>Tambah Paket Sembako Baru</h1>

        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="form-group">
                <label for="nama_paket">Nama Paket</label>
                <input type="text" name="nama_paket" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea name="deskripsi" class="form-control"></textarea>
            </div>

            <h3>Barang dalam Paket</h3>
            <div id="barang-container">
                <div class="barang-item">
                    <select name="barang[]" class="form-control">
                        <option value="">Pilih Barang</option>
                        <?php while($barang = $barang_list->fetch(PDO::FETCH_ASSOC)): ?>
                            <option value="<?php echo $barang['barang_id']; ?>">
                                <?php echo htmlspecialchars($barang['nama_barang']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <input type="number" name="jumlah[]" placeholder="Jumlah" class="form-control" min="1">
                </div>
            </div>

            <button type="button" id="tambah-barang" class="btn btn-secondary">
                <i class="fas fa-plus"></i> Tambah Barang
            </button>

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Paket
                </button>
                <a href="index.php" class="btn btn-secondary">
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

    <?php include_once '../../includes/footer.php'; 
    ob_end_flush(); // End output buffering
    ?>