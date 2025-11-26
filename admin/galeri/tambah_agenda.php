<?php 
// File: admin/galeri/tambah_agenda.php
session_start();
$pageTitle = 'Tambah Acara Agenda';
$currentPage = 'tambah_agenda';
$adminPageStyles = ['forms', 'tables'];

$base_Url = '..'; 
//$base_Url = '../admin'; 
$assetUrl = '/PBL_NCS/assets/admin';

include '../../config/koneksi.php';
require_once __DIR__ . '/../../app/helpers/agenda_helper.php';

// Ambil semua agenda
$agendaItems = get_agenda_items($conn, false);

// Ambil page content (section title & description)
$pageId = agenda_ensure_page($conn, 'galeri_agenda');
$pcRes = pg_query_params($conn, "SELECT content_key, content_value FROM page_content WHERE id_page = $1", array($pageId));
$pc = [];
if ($pcRes && pg_num_rows($pcRes) > 0) {
    while ($r = pg_fetch_assoc($pcRes)) $pc[$r['content_key']] = $r['content_value'];
}

require_once dirname(__DIR__) . '/includes/admin_header.php';
?>

<div class="admin-header">
    <h1><?php echo $pageTitle; ?> (Tabel: agenda)</h1>
    <p>Form ini digunakan untuk menambahkan acara atau workshop ke halaman Agenda.</p>
</div>

<!-- Form tambah agenda -->
<div class="card">
    <form method="post" action="<?php echo $adminBasePath; ?>proses/proses_agenda.php">
        <input type="hidden" name="tambah" value="1">
        
            <div class="card-header">
                <h3>Detail Acara</h3>
            </div>

            <div class="form-group">
                <label for="judul_agenda">Judul Acara (Kolom: judul_agenda)</label>
                <input type="text" id="judul_agenda" name="judul_agenda" required>
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi Singkat (Kolom: deskripsi)</label>
                <textarea id="deskripsi" name="deskripsi" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="tanggal_agenda">Tanggal Acara (Kolom: tanggal_agenda)</label>
                <input type="date" id="tanggal_agenda" name="tanggal_agenda" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="form-group">
                <label for="status">Status (Kolom: status)</label>
                <select id="status" name="status">
                    <option value="1">Aktif</option>
                    <option value="0">Arsip</option>
                </select>
            </div>
            
            <div class="form-group" style="margin-top: 25px;">
                <input type="submit" name="submit_tambah" class="btn-primary" value="Tambahkan Acara Agenda">
            </div>
    </form>
</div>

<div class="card">
        <div class="card-header">
                <h3>Konten Halaman Agenda</h3>
        </div>

        <form method="post" action="<?php echo $adminBasePath; ?>proses/proses_agenda.php">
            <input type="hidden" name="edit_page" value="1">
            <div class="form-group">
                <label for="judul_page">Section Title</label>
                <input type="text" id="judul_page" name="judul_page" value="<?php echo htmlspecialchars($pc['section_title'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="deskripsi_page">Section Description</label>
                <textarea id="deskripsi_page" name="deskripsi_page" rows="3"><?php echo htmlspecialchars($pc['section_description'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <input type="submit" name="submit_page" class="btn-primary" value="Simpan Konten Halaman">
            </div>
        </form>
</div>

<div class="card">
    
    <div class="card-header">
        <h3>Daftar Agenda</h3>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($agendaItems)): ?>
                <tr><td colspan="5" class="text-muted">Belum ada agenda.</td></tr>
            <?php else: ?>
                <?php foreach ($agendaItems as $it): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($it['tanggal_agenda']); ?></td>
                        <td><?php echo htmlspecialchars($it['judul_agenda']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($it['deskripsi'] ?? '')); ?></td>
                        <td><?php echo $it['status'] ? 'Aktif' : 'Arsip'; ?></td>
                        <td class="agenda-actions">
                            <!-- Edit form (buka small edit modal or redirect to edit page) -->
                            <form method="post" action="<?php echo $adminBasePath; ?>proses/proses_agenda.php" style="display:inline-block;">
                                <input type="hidden" name="edit" value="1">
                                <input type="hidden" name="id_agenda" value="<?php echo $it['id_agenda']; ?>">
                                <input type="hidden" name="judul_agenda" value="<?php echo htmlspecialchars($it['judul_agenda']); ?>">
                                <input type="hidden" name="tanggal_agenda" value="<?php echo htmlspecialchars($it['tanggal_agenda']); ?>">
                                <input type="hidden" name="deskripsi" value="<?php echo htmlspecialchars($it['deskripsi'] ?? ''); ?>">
                                <input type="hidden" name="status" value="<?php echo $it['status'] ? '1' : '0'; ?>">
                                <button type="button" class="btn-primary" style="background:orange" onclick="openEditAgenda(<?php echo $it['id_agenda']; ?>)">Edit</button>
                            </form>

                            <form method="post" action="<?php echo $adminBasePath; ?>proses/proses_agenda.php" onsubmit="return confirm('Hapus agenda ini?');">
                                <input type="hidden" name="hapus" value="1">
                                <input type="hidden" name="id_agenda" value="<?php echo $it['id_agenda']; ?>">
                                <button type="submit" class="btn-primary" style="background:#e74c3c">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function openEditAgenda(id) {
    // find the hidden inputs with id_agenda matching
    const rowInputs = document.querySelectorAll('input[name="id_agenda"]');
    for (const inp of rowInputs) {
        if (parseInt(inp.value) === parseInt(id)) {
            // find parent tr
            const tr = inp.closest('tr');
            // extract values from that row's hidden inputs in the edit form
            const parentForm = tr.querySelector('form');
            // fallback: ask prompts (simple)
            const currentTitle = tr.querySelector('input[name="judul_agenda"]')?.value || tr.children[1].textContent.trim();
            const currentDate = tr.querySelector('input[name="tanggal_agenda"]')?.value || tr.children[0].textContent.trim();
            const currentDesc = tr.querySelector('input[name="deskripsi"]')?.value || tr.children[2].textContent.trim();
            const currentStatus = tr.querySelector('input[name="status"]')?.value || (tr.children[3].textContent.trim() === 'Aktif' ? '1' : '0');

            const newTitle = prompt('Ubah judul:', currentTitle);
            if (newTitle === null) return;
            const newDate = prompt('Ubah tanggal (YYYY-MM-DD):', currentDate);
            if (newDate === null) return;
            const newDesc = prompt('Ubah deskripsi:', currentDesc);
            if (newDesc === null) return;
            const newStatus = prompt('Status (1=Aktif, 0=Arsip):', currentStatus);
            if (newStatus === null) return;

            // create form to submit
    const f = document.createElement('form');
    f.method = 'post';
    f.action = '<?php echo $adminBasePath; ?>proses/proses_agenda.php';

            const hEdit = document.createElement('input'); hEdit.type='hidden'; hEdit.name='edit'; hEdit.value='1'; f.appendChild(hEdit);
            const hId = document.createElement('input'); hId.type='hidden'; hId.name='id_agenda'; hId.value = id; f.appendChild(hId);
            const hTitle = document.createElement('input'); hTitle.type='hidden'; hTitle.name='judul_agenda'; hTitle.value = newTitle; f.appendChild(hTitle);
            const hDate = document.createElement('input'); hDate.type='hidden'; hDate.name='tanggal_agenda'; hDate.value = newDate; f.appendChild(hDate);
            const hDesc = document.createElement('input'); hDesc.type='hidden'; hDesc.name='deskripsi'; hDesc.value = newDesc; f.appendChild(hDesc);
            const hStatus = document.createElement('input'); hStatus.type='hidden'; hStatus.name='status'; hStatus.value = newStatus; f.appendChild(hStatus);

            document.body.appendChild(f);
            f.submit();
            break;
        }
    }
}
</script>
<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>
