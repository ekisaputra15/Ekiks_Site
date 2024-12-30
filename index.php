<?php
require_once 'includes/middleware.php';
require_once 'config/database.php';

$database = new Database();
$conn = $database->getConnection();

// Ambil data jumlah penerima, barang, dan distribusi
$stmt = $conn->prepare("
    SELECT 
        (SELECT COUNT(*) FROM penerima) AS total_penerima,
        (SELECT COUNT(*) FROM barang) AS total_barang,
        (SELECT COUNT(*) FROM distribusi_sembako) AS total_distribusi
");
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

// Ambil 5 distribusi terakhir
$stmt = $conn->prepare("
    SELECT 
        d.distribusi_id,
        p.nama AS nama_penerima,
        ps.nama_paket,
        d.tanggal_distribusi,
        d.status_pengambilan
    FROM distribusi_sembako d
    JOIN penerima p ON d.penerima_id = p.penerima_id
    JOIN paket_sembako ps ON d.paket_id = ps.paket_id
    ORDER BY d.tanggal_distribusi DESC
    LIMIT 5
");
$stmt->execute();
$last_distributions = $stmt->fetchAll(PDO::FETCH_ASSOC);
$title = "Sembako Sistem";
?>
<?php include 'includes/header.php'; ?>

<!-- Add custom styles for mobile responsiveness and table scrolling -->
<style>
    .table-wrapper {
        position: relative;
        overflow-x: auto;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin: 20px 0;
    }
    
    .table-wrapper table {
        margin-bottom: 0;
        white-space: nowrap;
    }
    
    .table-wrapper::-webkit-scrollbar {
        height: 8px;
    }
    
    .table-wrapper::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    .table-wrapper::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }
    
    .table-wrapper::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    
    @media (max-width: 768px) {
        .card {
            margin-bottom: 1rem !important;
        }
        
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            width: 100%;
        }
        
        .action-buttons .btn {
            width: 100%;
            text-align: left;
            padding: 0.75rem 1rem;
        }
        
        .btn i {
            width: 25px;
            text-align: center;
        }
        
        .table-indicator {
            display: block;
            text-align: center;
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
    }
    
    .card {
        transition: transform 0.2s;
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .card:hover {
        transform: translateY(-5px);
    }
    
    .stats-number {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 0;
    }
    
    .card-title {
        font-size: 1.1rem;
        margin-bottom: 1rem;
    }
    
    .badge {
        padding: 0.5em 0.8em;
    }
</style>

<div class="container px-3 py-4">
    <h2 class="mb-4 text-center">Sembako System</h2>
    
    <div class="row g-3">
        <div class="col-12 col-md-4">
            <div class="card text-white bg-info h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-users me-2"></i>Total Penerima</h5>
                    <p class="stats-number"><?= $data['total_penerima'] ?></p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card text-white bg-success h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-box me-2"></i>Total Barang</h5>
                    <p class="stats-number"><?= $data['total_barang'] ?></p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card text-white bg-warning h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-truck me-2"></i>Total Distribusi</h5>
                    <p class="stats-number"><?= $data['total_distribusi'] ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        <h3 class="mb-3">Distribusi Terbaru</h3>
        <div class="table-indicator d-md-none">
            <i class="fas fa-arrows-left-right me-2"></i>Geser tabel ke kiri/kanan
        </div>
        <div class="table-wrapper">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th class="py-3">No</th>
                        <th class="py-3">Penerima</th>
                        <th class="py-3">Paket Sembako</th>
                        <th class="py-3">Tanggal</th>
                        <th class="py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($last_distributions as $row): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama_penerima']) ?></td>
                        <td><?= htmlspecialchars($row['nama_paket']) ?></td>
                        <td><?= date('d/m/Y', strtotime($row['tanggal_distribusi'])) ?></td>
                        <td>
                            <?php if ($row['status_pengambilan'] == 'belum_diambil'): ?>
                                <span class="badge bg-warning">Belum Diambil</span>
                            <?php else: ?>
                                <span class="badge bg-success">Sudah Diambil</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="action-buttons mt-4">
        <a href="modules/distribusi/index.php" class="btn btn-primary">
            <i class="fas fa-list"></i> Lihat Semua Distribusi
        </a>
        <a href="modules/barang/index.php" class="btn btn-success">
            <i class="fas fa-box"></i> Kelola Barang
        </a>
        <a href="modules/penerima/index.php" class="btn btn-warning">
            <i class="fas fa-users"></i> Kelola Penerima
        </a>
        <a href="modules/suppliers/index.php" class="btn btn-info text-white">
            <i class="fas fa-truck"></i> Kelola Supplier
        </a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>