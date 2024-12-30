<?php
require_once '../../includes/middleware.php';
include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$query = "DELETE FROM barang WHERE barang_id = ?";
$stmt = $db->prepare($query);

try {
    $stmt->execute([$_GET['id']]);
    header('Location: index.php?success=1');
    exit;
} catch (PDOException $e) {
    $error = "Error: " . $e->getMessage();
    // Optionally, handle the error by redirecting to a different page or showing a message
    header('Location: index.php?error=' . urlencode($error));
    exit;
}
?>
