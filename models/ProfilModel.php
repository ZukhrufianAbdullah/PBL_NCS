<?php
class ProfileModel {
    private $conn;

    // Konstruktor menerima koneksi database dari config/database.php
    public function __construct($db) {
        $this->conn = $db;
    }

    /* ==============================
       BAGIAN 1: VISI & MISI
       ============================== */

    // Ambil data visi misi
    public function getVisiMisi() {
        try {
            $query = "SELECT * FROM visimisi LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Gagal mengambil visi misi: " . $e->getMessage();
            return null;
        }
    }

    // Update visi dan misi (hanya 1 data)
    public function updateVisiMisi($visi, $misi) {
        try {
            $query = "UPDATE visimisi 
                      SET visi = :visi, misi = :misi 
                      WHERE id_visimisi = 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':visi', $visi);
            $stmt->bindParam(':misi', $misi);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Gagal mengupdate visi misi: " . $e->getMessage();
            return false;
        }
    }

    /* ==============================
       BAGIAN 2: LOGO LAB
       ============================== */

    // Ambil data logo
    public function getLogo() {
        try {
            $query = "SELECT * FROM logoprofile LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Gagal mengambil logo: " . $e->getMessage();
            return null;
        }
    }

    // Update logo lab (hanya 1 logo)
    public function updateLogo($nama_logo, $file_media) {
        try {
            $query = "UPDATE logoprofile 
                      SET nama_logo = :nama_logo, file_media = :file_media 
                      WHERE id_logo = 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nama_logo', $nama_logo);
            $stmt->bindParam(':file_media', $file_media);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Gagal mengupdate logo: " . $e->getMessage();
            return false;
        }
    }

    /* ==============================
       BAGIAN 3: STRUKTUR ORGANISASI
       ============================== */

    // Ambil semua struktur organisasi (beserta anggota)
    public function getStruktur() {
        try {
            $query = "SELECT * FROM struktur_organisasi ORDER BY id_struktur DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Ambil anggota untuk setiap struktur
            foreach ($data as &$struktur) {
                $struktur['anggota'] = $this->getAnggotaByStruktur($struktur['id_struktur']);
            }

            return $data;
        } catch (PDOException $e) {
            echo "Gagal mengambil struktur organisasi: " . $e->getMessage();
            return [];
        }
    }

    // Tambah struktur baru
    public function addStruktur($tanggal_upload, $id_user) {
        try {
            $query = "INSERT INTO struktur_organisasi (tanggal_upload, id_user)
                      VALUES (:tanggal_upload, :id_user)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':tanggal_upload', $tanggal_upload);
            $stmt->bindParam(':id_user', $id_user);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Gagal menambah struktur: " . $e->getMessage();
            return false;
        }
    }

    // Update struktur organisasi
    public function updateStruktur($id_struktur, $tanggal_upload, $id_user) {
        try {
            $query = "UPDATE struktur_organisasi 
                      SET tanggal_upload = :tanggal_upload, id_user = :id_user 
                      WHERE id_struktur = :id_struktur";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':tanggal_upload', $tanggal_upload);
            $stmt->bindParam(':id_user', $id_user);
            $stmt->bindParam(':id_struktur', $id_struktur);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Gagal mengupdate struktur: " . $e->getMessage();
            return false;
        }
    }

    // Hapus struktur organisasi
    public function deleteStruktur($id_struktur) {
        try {
            // Hapus anggota terlebih dahulu
            $this->deleteAnggotaByStruktur($id_struktur);

            // Baru hapus struktur
            $query = "DELETE FROM struktur_organisasi WHERE id_struktur = :id_struktur";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_struktur', $id_struktur);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Gagal menghapus struktur: " . $e->getMessage();
            return false;
        }
    }

    /* ==============================
       BAGIAN 4: ANGGOTA ORGANISASI
       ============================== */

    // Ambil anggota berdasarkan struktur
    public function getAnggotaByStruktur($id_struktur) {
        $query = "SELECT * FROM anggota_organisasi WHERE id_struktur = :id_struktur";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_struktur', $id_struktur);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Hapus semua anggota dari satu struktur
    public function deleteAnggotaByStruktur($id_struktur) {
        $query = "DELETE FROM anggota_organisasi WHERE id_struktur = :id_struktur";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_struktur', $id_struktur);
        $stmt->execute();
    }
}
?>
