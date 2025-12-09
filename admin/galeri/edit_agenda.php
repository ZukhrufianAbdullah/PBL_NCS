<?php 
// File: admin/galeri/edit_agenda.php
session_start();

$page_title = "Kelola Agenda";
$current_page = "edit_agenda";
$adminPageStyles = ['forms', 'tables'];
include '../../config/koneksi.php';
require_once dirname(__DIR__) . '/includes/admin_header.php';

// Ambil data judul
$qJudulAgenda = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'galeri_agenda' AND pc.content_key = 'section_title'
    LIMIT 1");
$judulAgenda = pg_fetch_assoc($qJudulAgenda)['content_value'] ?? '';

// Ambil data deskripsi
$qDeskripsiAgenda = pg_query($conn, "
    SELECT pc.content_value 
    FROM page_content pc
    JOIN pages p ON pc.id_page = p.id_page
    WHERE p.nama = 'galeri_agenda' AND pc.content_key = 'section_description'
    LIMIT 1");
$deskripsiAgenda = pg_fetch_assoc($qDeskripsiAgenda)['content_value'] ?? '';

// Ambil data agenda
$qAgenda = pg_query($conn, "
    SELECT * 
    FROM agenda
    ORDER BY tanggal DESC");
?>


<div class="admin-header">
    <h1><?php echo $page_title; ?> </h1>
    <p>Kelola halaman agenda di sini</p>
</div>
<div class="card">
    <form method="post" action="../proses/proses_agenda.php">
        <input type="hidden" name="edit_page_content" value="1">
        <fieldset>
            <legend>Judul dan Deskripsi Agenda</legend>
            <div class="form-group">
                <label for="judul_agenda">Judul Halaman</label>
                <input type="text"
                       id="judul_agenda"
                       name="judul_agenda"
                       placeholder="Masukkan judul halaman agenda"
                       value="<?php echo htmlspecialchars($judulAgenda);?>"
                       data-autofocus ="true">
            </div>
            <div class="form-group">
                <label for="deskripsi_agenda">Deskripsi Singkat Halaman</label>
                <textarea id="deskripsi_agenda"
                          name="deskripsi_agenda"
                          placeholder="Masukkan deskripsi singkat halaman agenda"
                          rows="4"><?php echo htmlspecialchars($deskripsiAgenda); ?></textarea> 
            </div>
        </fieldset>
        <div class="form-group">
            <button type="submit" name="submit_judul_deskripsi_agenda" class="btn-primary">Simpan Konten Halaman</button>
        </div>
    </form>
</div>

<!-- ============================
     FORM TAMBAH AGENDA
=============================== -->
<div class="card">
    <form method="post" action="../proses/proses_agenda.php">
        <input type="hidden" name="tambah_agenda" value="1">

        <fieldset>
            <legend>Tambah Agenda Baru</legend>

            <div class="form-group">
                <label for="judul_agenda_baru">Judul Agenda</label>
                <input type="text" id="judul_agenda_baru" name="judul" required placeholder="Masukkan judul agenda">
            </div>

            <div class="form-group">
                <label for="deskripsi_baru">Deskripsi</label>
                <textarea id="deskripsi_baru" name="deskripsi" rows="3" placeholder="Masukkan deskripsi agenda"></textarea>
            </div>

            <div class="form-group">
                <label for="tanggal_baru">Tanggal Agenda</label>
                <input type="date" id="tanggal_baru" name="tanggal" required
                       value="<?php echo date('Y-m-d'); ?>">
            </div>

            <div class="form-group">
                <label for="status_baru">Status</label>
                <select id="status_baru" name="status">
                    <option value="1">Aktif</option>
                    <option value="0">Arsip</option>
                </select>
            </div>

        </fieldset>

        <div class="form-group">
            <button type="submit" name="tambah_agenda" class="btn-primary">
                Tambah Agenda
            </button>
        </div>
    </form>
</div>

<br>

<!-- ============================
     TABEL DAFTAR AGENDA
=============================== -->

<div class="card">
    <div class="card-header">
        <h3>Daftar Agenda</h3>
    </div>

    <table class="data-table" id="agendaTable">
        <thead>
            <tr>
                <th>No</th>
                <th>Judul Agenda</th>
                <th>Deskripsi</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th style="width: 300px; text-align:center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
    <?php 
    $no = 1;
    $hasData = false;

    while ($row = pg_fetch_assoc($qAgenda)): 
        $hasData = true;
    ?>
    <tr>
        <td><?php echo $no++; ?></td>
        <td><?php echo htmlspecialchars($row['judul']); ?></td>
        <td><?php echo nl2br(htmlspecialchars($row['deskripsi'])); ?></td>
        <td><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></td>
        <td>
            <?php echo ($row['status'] === 't')
                ? '<span class="badge badge-success">Aktif</span>'
                : '<span class="badge badge-danger">Arsip</span>'; ?>
        </td>
        <td style="text-align:center;">
            <button class="btn-warning" 
                    onclick='openEditModal(<?php echo json_encode($row); ?>)'>
                Edit
            </button>

            <form method="post" action="../proses/proses_agenda.php" 
                  style="display:inline;" 
                  onsubmit="return confirm('Yakin ingin menghapus agenda ini?');">
                <input type="hidden" name="hapus" value="1">
                <input type="hidden" name="id_agenda" value="<?php echo $row['id_agenda']; ?>">
                <button type="submit" class="btn-danger">Hapus</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>

    <?php if (!$hasData): ?>
    <tr>
        <td colspan="6" style="text-align:center; padding:15px; color:#777;">
            <strong>Belum ada agenda yang ditambahkan</strong>
        </td>
    </tr>
    <?php endif; ?>
</tbody>

    </table>
</div>


<!-- ============================
     MODAL EDIT AGENDA
=============================== -->
<div id="editModal" class="modal" style="
    display:none; position:fixed; top:0; left:0; width:100%; height:100%;
    background:rgba(0,0,0,0.5); justify-content:center; align-items:center;
">
    <div class="modal-content" style="background:#fff; padding:20px; width:400px; border-radius:8px;">
        <h3>Kelola Agenda</h3>
        <form method="post" action="../proses/proses_agenda.php">

            <input type="hidden" name="edit_agenda" value="1">
            <input type="hidden" name="id_agenda" id="edit_id">

            <div class="form-group">
                <label>Judul</label>
                <input type="text" name="judul" id="edit_judul" required>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" id="edit_deskripsi" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label>Tanggal</label>
                <input type="date" name="tanggal" id="edit_tanggal" required>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" id="edit_status">
                    <option value="1">Aktif</option>
                    <option value="0">Arsip</option>
                </select>
            </div>

            <div class="form-group" style="margin-top:10px;">
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
                <button type="button" class="btn-danger" onclick="closeModal()">Batal</button>
            </div>
        </form>
    </div>
</div>


<!-- ============================
     SCRIPT JS
=============================== -->

<script>
function openEditModal(row) {
    document.getElementById("edit_id").value = row.id_agenda;
    document.getElementById("edit_judul").value = row.judul;
    document.getElementById("edit_deskripsi").value = row.deskripsi;
    document.getElementById("edit_tanggal").value = row.tanggal;
    document.getElementById("edit_status").value = (row.status === "t" ? "1" : "0");


    document.getElementById("editModal").style.display = "flex";
}
function closeModal() {
    document.getElementById("editModal").style.display = "none";
}
</script>

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>

