<?php
class ProfilModel {
    private $conn;
    private $table = "profile"; // nama tabel di database

    // Konstruktor untuk menerima koneksi PDO
    public function __construct($db) {
        $this->conn = $db;
    }

    // 🟩 1. Ambil data profil (hanya 1 baris profil lab)
    public function getProfil() {
        $query = "SELECT * FROM {$this->table} LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 🟨 2. Update profil (visi, misi, email, alamat)
    public function updateProfil($id_profile, $visi, $misi, $kontak_email, $alamat_lab) {
        $query = "UPDATE {$this->table} 
                  SET visi = :visi, 
                      misi = :misi, 
                      kontak_email = :kontak_email, 
                      alamat_lab = :alamat_lab
                  WHERE id_profile = :id_profile";

        $stmt = $this->conn->prepare($query);

        // Bind parameter
        $stmt->bindParam(':visi', $visi);
        $stmt->bindParam(':misi', $misi);
        $stmt->bindParam(':kontak_email', $kontak_email);
        $stmt->bindParam(':alamat_lab', $alamat_lab);
        $stmt->bindParam(':id_profile', $id_profile, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // 🟦 3. Update logo laboratorium
    public function updateLogo($id_profile, $logo_lab) {
        $query = "UPDATE {$this->table} SET logo_lab = :logo_lab WHERE id_profile = :id_profile";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':logo_lab', $logo_lab);
        $stmt->bindParam(':id_profile', $id_profile, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // 🟧 4. Tambah data profil baru (jika kosong)
    public function createProfil($visi, $misi, $logo_lab, $kontak_email, $alamat_lab) {
        $query = "INSERT INTO {$this->table} (visi, misi, logo_lab, kontak_email, alamat_lab) 
                  VALUES (:visi, :misi, :logo_lab, :kontak_email, :alamat_lab)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':visi', $visi);
        $stmt->bindParam(':misi', $misi);
        $stmt->bindParam(':logo_lab', $logo_lab);
        $stmt->bindParam(':kontak_email', $kontak_email);
        $stmt->bindParam(':alamat_lab', $alamat_lab);
        return $stmt->execute();
    }
}
?>