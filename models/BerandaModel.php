<?php
// models/BerandaModel.php
// Model ini digunakan untuk mengambil dan mengupdate data di tabel "beranda"

class BerandaModel {
    private $conn;

    // Konstruktor menerima koneksi database dari config/database.php
    public function __construct($db) {
        $this->conn = $db;
    }

    // 🔹 Fungsi untuk mengambil data beranda (gambar & deskripsi)
    public function getBeranda() {
        try {
            $query = "SELECT * FROM beranda LIMIT 1"; // ambil data pertama saja
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "Terjadi kesalahan saat mengambil data beranda: " . $e->getMessage();
            return null;
        }
    }

    // 🔹 Fungsi untuk mengupdate gambar dan isi beranda
    public function updateBeranda($judul, $isi, $gambar) {
        try {
            $query = "UPDATE beranda 
                      SET judul = :judul, isi = :isi, gambar = :gambar 
                      WHERE id_home = 1";
            $stmt = $this->conn->prepare($query);

            // Binding parameter ke query
            $stmt->bindParam(':judul', $judul);
            $stmt->bindParam(':isi', $isi);
            $stmt->bindParam(':gambar', $gambar);

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Terjadi kesalahan saat mengupdate data beranda: " . $e->getMessage();
            return false;
        }
    }
}
?>
