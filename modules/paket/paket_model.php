<?php
require_once '../../includes/middleware.php';

class PaketSembako {
    private $conn;
    private $table_name = 'paket_sembako';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Mendapatkan semua paket sembako
    public function getAllPaket() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nama_paket ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Mendapatkan detail paket sembako beserta barang di dalamnya
    public function getPaketDetails($paket_id) {
        $query = "SELECT ps.*, dp.barang_id, b.nama_barang, dp.jumlah as jumlah_barang 
                  FROM paket_sembako ps
                  LEFT JOIN detail_paket dp ON ps.paket_id = dp.paket_id
                  LEFT JOIN barang b ON dp.barang_id = b.barang_id
                  WHERE ps.paket_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$paket_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Membuat paket sembako baru
    public function createPaket($nama_paket, $deskripsi, $barang_list) {
        try {
            $this->conn->beginTransaction();

            // Simpan paket sembako
            $query = "INSERT INTO paket_sembako (nama_paket, deskripsi) VALUES (?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$nama_paket, $deskripsi]);
            $paket_id = $this->conn->lastInsertId();

            // Simpan detail paket
            $detail_query = "INSERT INTO detail_paket (paket_id, barang_id, jumlah) VALUES (?, ?, ?)";
            $detail_stmt = $this->conn->prepare($detail_query);

            foreach ($barang_list as $barang) {
                $detail_stmt->execute([
                    $paket_id, 
                    $barang['barang_id'], 
                    $barang['jumlah']
                ]);
            }

            $this->conn->commit();
            return $paket_id;
        } catch(PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Mengupdate paket sembako
    public function updatePaket($paket_id, $nama_paket, $deskripsi, $barang_list) {
        try {
            $this->conn->beginTransaction();

            // Update paket sembako
            $query = "UPDATE paket_sembako SET nama_paket = ?, deskripsi = ? WHERE paket_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$nama_paket, $deskripsi, $paket_id]);

            // Hapus detail paket lama
            $delete_query = "DELETE FROM detail_paket WHERE paket_id = ?";
            $delete_stmt = $this->conn->prepare($delete_query);
            $delete_stmt->execute([$paket_id]);

            // Simpan detail paket baru
            $detail_query = "INSERT INTO detail_paket (paket_id, barang_id, jumlah) VALUES (?, ?, ?)";
            $detail_stmt = $this->conn->prepare($detail_query);

            foreach ($barang_list as $barang) {
                $detail_stmt->execute([
                    $paket_id, 
                    $barang['barang_id'], 
                    $barang['jumlah']
                ]);
            }

            $this->conn->commit();
            return true;
        } catch(PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Menghapus paket sembako
    public function deletePaket($paket_id) {
        try {
            $this->conn->beginTransaction();

            // Hapus detail paket terlebih dahulu
            $detail_query = "DELETE FROM detail_paket WHERE paket_id = ?";
            $detail_stmt = $this->conn->prepare($detail_query);
            $detail_stmt->execute([$paket_id]);

            // Hapus paket sembako
            $query = "DELETE FROM paket_sembako WHERE paket_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$paket_id]);

            $this->conn->commit();
            return true;
        } catch(PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}
?>