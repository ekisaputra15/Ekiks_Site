<?php
require_once '../../includes/middleware.php';
include_once '../../config/database.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$database = new Database();
$db = $database->getConnection();

try {
    $query = "DELETE FROM kategori_barang WHERE kategori_id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$_GET['id']]);

    header('Location: index.php?success=1');
} catch(PDOException $e) {
    header('Location: index.php?error=1');
}
?>