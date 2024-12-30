<?php
class Barang {
    private $conn;
    private $table_name = 'barang';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Mendapatkan semua barang
    public function getAllBarang() {
        $query = "SELECT b.*, k.nama_kategori 
                  FROM " . $this->table_name . " b
                  LEFT JOIN kategori_barang k ON b.kategori_id = k.kategori_id
                  ORDER BY b.nama_barang ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Mendapatkan detail barang berdasarkan ID
    public function getBarangById($barang_id) {
        $query = "SELECT b.*, k.nama_kategori 
                  FROM " . $this->table_name . " b
                  LEFT JOIN kategori_barang k ON b.kategori_id = k.kategori_id
                  WHERE b.barang_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$barang_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Menambah barang baru
    public function tambahBarang($nama_barang, $kategori_id, $satuan, $stok = 0) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (nama_barang, kategori_id, satuan, stok) 
                  VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        
        try {
            $stmt->execute([
                $nama_barang, 
                $kategori_id, 
                $satuan, 
                $stok
            ]);
            return $this->conn->lastInsertId();
        } catch(PDOException $e) {
            return false;
        }
    }

    // Mengupdate barang
    public function updateBarang($barang_id, $nama_barang, $kategori_id, $satuan, $stok) {
        $query = "UPDATE " . $this->table_name . " 
                  SET nama_barang = ?, 
                      kategori_id = ?, 
                      satuan = ?, 
                      stok = ? 
                  WHERE barang_id = ?";
        $stmt = $this->conn->prepare($query);
        
        try {
            return $stmt->execute([
                $nama_barang, 
                $kategori_id, 
                $satuan, 
                $stok, 
                $barang_id
            ]);
        } catch(PDOException $e) {
            return false;
        }
    }

    // Menghapus barang
    public function deleteBarang($barang_id) {
        try {
            $this->conn->beginTransaction();

            // Cek apakah barang sudah pernah digunakan dalam paket atau transaksi
            $check_paket = "SELECT COUNT(*) FROM detail_paket WHERE barang_id = ?";
            $check_barang_masuk = "SELECT COUNT(*) FROM barang_masuk WHERE barang_id = ?";
            
            $stmt_paket = $this->conn->prepare($check_paket);
            $stmt_barang_masuk = $this->conn->prepare($check_barang_masuk);
            
            $stmt_paket->execute([$barang_id]);
            $stmt_barang_masuk->execute([$barang_id]);
            
            if ($stmt_paket->fetchColumn() > 0 || $stmt_barang_masuk->fetchColumn() > 0) {
                $this->conn->rollBack();
                return false;
            }

            // Hapus barang
            $query = "DELETE FROM " . $this->table_name . " WHERE barang_id = ?";
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute([$barang_id]);

            $this->conn->commit();
            return $result;
        } catch(PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Menambah stok barang
    public function tambahStok($barang_id, $jumlah, $keterangan = '') {
        try {
            $this->conn->beginTransaction();

            // Update stok barang
            $query_stok = "UPDATE " . $this->table_name . " 
                           SET stok = stok + ? 
                           WHERE barang_id = ?";
            $stmt_stok = $this->conn->prepare($query_stok);
            $stmt_stok->execute([$jumlah, $barang_id]);

            // Catat barang masuk
            $query_masuk = "INSERT INTO barang_masuk 
                            (barang_id, jumlah, tanggal_masuk, keterangan) 
                            VALUES (?, ?, CURDATE(), ?)";
            $stmt_masuk = $this->conn->prepare($query_masuk);
            $stmt_masuk->execute([$barang_id, $jumlah, $keterangan]);

            $this->conn->commit();
            return true;
        } catch(PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Kurangi stok barang
    public function kurangiStok($barang_id, $jumlah, $keterangan = '') {
        try {
            $this->conn->beginTransaction();

            // Periksa stok saat ini
            $query_cek = "SELECT stok FROM " . $this->table_name . " WHERE barang_id = ?";
            $stmt_cek = $this->conn->prepare($query_cek);
            $stmt_cek->execute([$barang_id]);
            $stok_saat_ini = $stmt_cek->fetchColumn();

            if ($stok_saat_ini < $jumlah) {
                $this->conn->rollBack();
                return false; // Stok tidak cukup
            }

            // Update stok barang
            $query_stok = "UPDATE " . $this->table_name . " 
                           SET stok = stok - ? 
                           WHERE barang_id = ?";
            $stmt_stok = $this->conn->prepare($query_stok);
            $stmt_stok->execute([$jumlah, $barang_id]);

            $this->conn->commit();
            return true;
        } catch(PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Mendapatkan riwayat stok barang
    public function getRiwayatStok($barang_id) {
        $query = "SELECT 
                    'Masuk' as jenis, 
                    jumlah, 
                    tanggal_masuk as tanggal, 
                    keterangan 
                  FROM barang_masuk 
                  WHERE barang_id = ?
                  UNION ALL
                  SELECT 
                    'Keluar' as jenis, 
                    jumlah, 
                    tanggal_distribusi as tanggal, 
                    'Distribusi Sembako' as keterangan 
                  FROM distribusi_sembako ds
                  JOIN detail_paket dp ON ds.paket_id = dp.paket_id
                  WHERE dp.barang_id = ?
                  ORDER BY tanggal DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$barang_id, $barang_id]);
        return $stmt;
    }

    // Pencarian barang
    public function cariBarang($keyword) {
        $query = "SELECT b.*, k.nama_kategori 
                  FROM " . $this->table_name . " b
                  LEFT JOIN kategori_barang k ON b.kategori_id = k.kategori_id
                  WHERE b.nama_barang LIKE ? OR k.nama_kategori LIKE ?
                  ORDER BY b.nama_barang ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute(["%$keyword%", "%$keyword%"]);
        return $stmt;
    }
}
?>