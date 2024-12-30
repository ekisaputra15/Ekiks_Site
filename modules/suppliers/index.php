<?php
require_once '../../includes/middleware.php';
include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM suppliers ORDER BY nama_supplier ASC";
$stmt = $db->prepare($query);
$stmt->execute();
$title = "Daftar Supplier";
include '../../includes/header.php';

?>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h1 class="mb-4 "></h1>
                <h2 class="text-center">Daftar Supplier</h2>
                <div class="d-flex justify-content-start mb-3">
                    <a href="add.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Supplier</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle border rounded">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th>Nama Supplier</th>
                                <th>Alamat</th>
                                <th>No. Telepon</th>
                                <th>Email</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['nama_supplier']); ?></td>
                                    <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                                    <td><?php echo htmlspecialchars($row['no_telepon']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td class="text-center">
                                        <a href="edit.php?id=<?php echo $row['supplier_id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</a>
                                        <a href="delete.php?id=<?php echo $row['supplier_id']; ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Yakin ingin menghapus?')"><i class="fas fa-trash-alt"></i> Hapus</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php include_once '../../includes/footer.php'; ?>
