<?php
require_once '../../includes/middleware.php';
require_once '../../config/database.php';
require_once 'paket_model.php';

$database = new Database();
$db = $database->getConnection();

$paket_model = new PaketSembako($db);

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
$title = "Detail Paket Sembako - " . htmlspecialchars($paket_info['nama_paket']);
?>
<?php include '../../includes/header.php'; ?>

    <div class="container mt-4">
        <h1>Detail Paket Sembako</h1>

        <div class="card">
            <div class="card-header">
                <h2><?php echo htmlspecialchars($paket_info['nama_paket']); ?></h2>
            </div>
            <div class="card-body">
                <p><strong>Deskripsi:</strong> <?php echo htmlspecialchars($paket_info['deskripsi']); ?></p>
                <p><strong>Tanggal Dibuat:</strong> <?php echo $paket_info['created_at']; ?></p>

                <h3>Barang dalam Paket</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($paket_details as $barang): ?>
                            <?php if (!empty($barang['nama_barang'])): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($barang['nama_barang']); ?></td>
                                    <td><?php echo $barang['jumlah_barang']; ?></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <a href="edit.php?id=<?php echo $paket_id; ?>" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit Paket
                </a>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Paket
                </a>
            </div>
        </div>
    </div>

    <?php include_once '../../includes/footer.php'; ?>
