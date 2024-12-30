<?php
require_once '../../config/database.php';
require_once '../../includes/middleware.php';

// Inisialisasi kelas Database
$database = new Database();
$conn = $database->getConnection();

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

// Escape ID untuk keamanan
$id = $_GET['id'];

// Cek apakah penerima memiliki riwayat distribusi
$check_distribusi_query = "SELECT * FROM distribusi_sembako WHERE penerima_id = :id";
$stmt = $conn->prepare($check_distribusi_query);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $_SESSION['message'] = "Tidak dapat menghapus data penerima karena memiliki riwayat distribusi sembako!";
    $_SESSION['msg_type'] = "danger";
} else {
    // Hapus data penerima
    $delete_query = "DELETE FROM penerima WHERE penerima_id = :id";
    $stmt_delete = $conn->prepare($delete_query);
    $stmt_delete->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt_delete->execute()) {
        $_SESSION['message'] = "Data penerima berhasil dihapus!";
        $_SESSION['msg_type'] = "success";
    } else {
        $_SESSION['message'] = "Error: " . $stmt_delete->errorInfo()[2];
        $_SESSION['msg_type'] = "danger";
    }
}

// Redirect ke halaman index
header("Location: index.php");
exit();
