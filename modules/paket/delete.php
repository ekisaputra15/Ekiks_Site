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

// Cek apakah paket sudah pernah didistribusikan
function isPaketSudahDidistribusi($db, $paket_id) {
    $query = "SELECT COUNT(*) as total FROM distribusi_sembako WHERE paket_id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$paket_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'] > 0;
}

if (isPaketSudahDidistribusi($db, $paket_id)) {
    header("Location: index.php?error=1&message=" . urlencode("Paket tidak dapat dihapus karena sudah pernah didistribusikan"));
    exit();
}

$result = $paket_model->deletePaket($paket_id);

if ($result) {
    header("Location: index.php?success=1&message=" . urlencode("Paket berhasil dihapus"));
} else {
    header("Location: index.php?error=1&message=" . urlencode("Gagal menghapus paket"));
}
exit();
?>