<?php
require_once '../../includes/middleware.php';
require_once '../../config/database.php';

// Inisialisasi kelas Database
$database = new Database();
$conn = $database->getConnection();

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$distribusi_id = $_GET['id'];
$petugas = isset($_POST['petugas']) ? $_POST['petugas'] : '';
$tanggal_pengambilan = date('Y-m-d H:i:s');

try {
    // Mulai transaksi
    $conn->beginTransaction();

    // Update status pengambilan
    $query = "UPDATE distribusi_sembako 
              SET status_pengambilan = 'sudah_diambil',
                  tanggal_pengambilan = :tanggal_pengambilan,
                  petugas_penyerah = :petugas
              WHERE distribusi_id = :distribusi_id";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':tanggal_pengambilan', $tanggal_pengambilan);
    $stmt->bindParam(':petugas', $petugas);
    $stmt->bindParam(':distribusi_id', $distribusi_id);
    $stmt->execute();

    // Update stok barang
    $query_detail = "
        SELECT dp.barang_id, dp.jumlah
        FROM distribusi_sembako ds
        JOIN paket_sembako ps ON ds.paket_id = ps.paket_id
        JOIN detail_paket dp ON ps.paket_id = dp.paket_id
        WHERE ds.distribusi_id = :distribusi_id
    ";
    
    $stmt_detail = $conn->prepare($query_detail);
    $stmt_detail->bindParam(':distribusi_id', $distribusi_id);
    $stmt_detail->execute();

    while ($row = $stmt_detail->fetch(PDO::FETCH_ASSOC)) {
        $update_stok = "
            UPDATE barang 
            SET stok = stok - :jumlah
            WHERE barang_id = :barang_id
        ";
        $stmt_update = $conn->prepare($update_stok);
        $stmt_update->bindParam(':jumlah', $row['jumlah'], PDO::PARAM_INT);
        $stmt_update->bindParam(':barang_id', $row['barang_id'], PDO::PARAM_INT);
        $stmt_update->execute();
    }

    // Commit transaksi jika semua berhasil
    $conn->commit();
    echo "<script>alert('Status pengambilan berhasil diupdate!'); window.location='index.php';</script>";
} catch (PDOException $e) {
    // Rollback transaksi jika terjadi kesalahan
    $conn->rollBack();
    echo "<script>alert('Gagal mengupdate status pengambilan! Error: " . $e->getMessage() . "'); window.location='index.php';</script>";
}
?>
