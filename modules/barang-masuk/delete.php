<?php
ob_start(); // Start output buffering

require_once '../../includes/middleware.php';
include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $delete_query = "DELETE FROM barang_masuk WHERE barang_masuk_id = :id";
    $delete_stmt = $db->prepare($delete_query);
    $delete_stmt->bindParam(':id', $id);

    if ($delete_stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error deleting incoming goods.";
    }
} else {
    header("Location: index.php");
    exit;
}
?>

<?php ob_end_flush(); // End output buffering ?>
