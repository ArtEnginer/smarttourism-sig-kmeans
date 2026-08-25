<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/database.php';
requireAdmin();

$pdo = getDatabaseConnection();
$message = '';
$messageType = 'success';

// Handle POST actions (Create & Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $nama = trim($_POST['nama'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');
    $daya = (int)($_POST['daya_tarik'] ?? 0);
    $akses = (int)($_POST['aksesibilitas'] ?? 0);
    $fasilitas = (int)($_POST['fasilitas'] ?? 0);
    $sarana = trim($_POST['sarana'] ?? '');
    $ulasan = trim($_POST['ulasan'] ?? '');
    $jumlah = (int)($_POST['jumlah_pengunjung'] ?? 0);
    $rating = (float)($_POST['rating'] ?? 0);
    $lat = (float)($_POST['latitude'] ?? 0);
    $lng = (float)($_POST['longitude'] ?? 0);

    if ($nama === '') {
        $message = 'Nama destinasi wajib diisi.';
        $messageType = 'error';
    } else {
        if ($action === 'create') {
            $stmt = $pdo->prepare('INSERT INTO tb_destinasi (nama_destinasi, kategori, daya_tarik, aksesibilitas, fasilitas, sarana, ulasan, jumlah_pengunjung, rating, latitude, longitude, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$nama, $kategori, $daya, $akses, $fasilitas, $sarana, $ulasan, $jumlah, $rating, $lat, $lng, 'aktif']);
            $message = 'Destinasi "' . htmlspecialchars($nama) . '" berhasil ditambahkan.';
        } elseif ($action === 'update') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                $stmt = $pdo->prepare('UPDATE tb_destinasi SET nama_destinasi = ?, kategori = ?, daya_tarik = ?, aksesibilitas = ?, fasilitas = ?, sarana = ?, ulasan = ?, jumlah_pengunjung = ?, rating = ?, latitude = ?, longitude = ? WHERE id = ?');
                $stmt->execute([$nama, $kategori, $daya, $akses, $fasilitas, $sarana, $ulasan, $jumlah, $rating, $lat, $lng, $id]);
                $message = 'Destinasi "' . htmlspecialchars($nama) . '" berhasil diperbarui.';
            }
        }
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($id > 0) {
        $stmt = $pdo->prepare('DELETE FROM tb_destinasi WHERE id = ?');
        $stmt->execute([$id]);
        $message = 'Destinasi berhasil dihapus.';
    }
}

$stmt = $pdo->query('SELECT * FROM tb_destinasi ORDER BY id DESC');
$all = $stmt->fetchAll();
?>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>

<div class="page-content">

    <!-- Visual Hero Banner Destinasi -->
    <div class="card mb-24" style="background:linear-gradient(135deg, #ffffff 0%, #ecfdf5 100%);border:1px solid var(--emerald-border);overflow:hidden;position:relative;">
        <div class="card-body" style="padding:24px 28px;">
            <div style="display:grid;grid-template-columns:1fr 240px;gap:20px;align-items:center;">
                <div>
                    <div style="display:inline-flex;align-items:center;gap:6px;background:var(--emerald-soft);border:1px solid var(--emerald-border);padding:4px 12px;border-radius:99px;font-size:11px;font-weight:700;color:var(--emerald-dark);text-transform:uppercase;margin-bottom:10px;">
                        <span style="width:7px;height:7px;border-radius:50%;background:var(--emerald);display:inline-block;"></span> Manajemen Data &bull; Destinasi Wisata
                    </div>
                    <h1 style="font-size:22px;font-weight:800;color:var(--slate);margin-bottom:6px;">
                        Kelola Destinasi Wisata Magelang
                    </h1>
                    <p style="font-size:12.5px;color:var(--slate-light);margin-bottom:16px;line-height:1.5;">
                        Tambah, perbarui, atau hapus data lokasi, jumlah pengunjung, rating, dan kriteria daya tarik wisata secara terpusat.
                    </p>
                    <div style="display:flex;gap:10px;flex-wrap:wrap;">
                        <button class="btn btn-primary btn-sm" onclick="openDestinasiModal()" style="box-shadow:0 4px 14px rgba(5,150,105,0.25);">
                            <i data-lucide="plus-circle" style="width:15px;height:15px;"></i> Tambah Destinasi Baru
                        </button>
                        <a href="<?= appUrl('pages/data_wisata.php') ?>" class="btn btn-secondary btn-sm">
                            <i data-lucide="table" style="width:15px;height:15px;"></i> Pratinjau Tabel Publik
                        </a>
                    </div>
                </div>
                <div style="border-radius:14px;overflow:hidden;border:1px solid var(--emerald-border);box-shadow:0 4px 14px rgba(5,150,105,0.12);height:140px;background:#fff;">
                    <img src="<?= appUrl('assets/hero_map.png') ?>" alt="Map Visual Graphic" style="width:100%;height:100%;object-fit:cover;">
                </div>
            </div>
        </div>
    </div>

    <?php if ($message): ?>
        <div class="notice <?= $messageType === 'error' ? 'badge-red' : 'notice-success' ?> mb-20" style="padding:14px 18px;display:flex;align-items:center;gap:10px;">
            <i data-lucide="<?= $messageType === 'error' ? 'alert-circle' : 'check-circle-2' ?>" style="width:18px;height:18px;"></i>
            <span><?= $message ?></span>
        </div>
    <?php endif; ?>

    <!-- Destinasi Table Card -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i data-lucide="landmark" style="width:18px;height:18px;color:var(--emerald);"></i>
                Daftar Destinasi (<?= count($all) ?>)
            </div>
            <div style="max-width:280px;width:100%;">
                <input type="text" id="destinasiSearch" class="form-control" style="min-height:36px;padding:6px 12px;font-size:12.5px;" placeholder="Cari destinasi atau kategori..." onkeyup="filterDestinasiTable()">
            </div>
        </div>
        <div class="table-wrap">
            <table id="destinasiTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Destinasi</th>
                        <th>Kategori</th>
                        <th>Daya Tarik</th>
                        <th>Akses</th>
                        <th>Fasilitas</th>
                        <th>Pengunjung/Thn</th>
                        <th>Rating</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($all)): ?>
                        <?php foreach ($all as $i => $row): ?>
                            <tr>
                                <td style="font-weight:700;color:var(--slate-light);"><?= $row['id'] ?></td>
                                <td style="font-weight:700;color:var(--slate);"><?= htmlspecialchars($row['nama_destinasi']) ?></td>
                                <td>
                                    <span class="badge badge-green"><?= htmlspecialchars($row['kategori'] ?: 'Umum') ?></span>
                                </td>
                                <td><strong style="color:var(--emerald-dark);"><?= $row['daya_tarik'] ?></strong> / 10</td>
                                <td><strong style="color:var(--blue);"><?= $row['aksesibilitas'] ?></strong> / 10</td>
                                <td><strong style="color:var(--gold);"><?= $row['fasilitas'] ?></strong> / 10</td>
                                <td class="mono" style="font-size:12px;"><?= number_format($row['jumlah_pengunjung'], 0, ',', '.') ?></td>
                                <td style="color:var(--gold);font-weight:700;">
                                    <i data-lucide="star" style="width:12px;height:12px;vertical-align:-1px;"></i> <?= $row['rating'] ?>
                                </td>
                                <td style="text-align:right;white-space:nowrap;">
                                    <button class="btn btn-secondary btn-sm" onclick='editDestinasi(<?= json_encode($row) ?>)'>
                                        <i data-lucide="edit-3" style="width:13px;height:13px;"></i> Edit
                                    </button>
                                    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Hapus destinasi <?= htmlspecialchars($row['nama_destinasi']) ?>?')" class="btn btn-sm" style="background:#fee2e2;color:var(--red);border-color:#fca5a5;">
                                        <i data-lucide="trash-2" style="width:13px;height:13px;"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" style="text-align:center;padding:32px;color:var(--slate-light);">Belum ada destinasi wisata terdaftar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- MODAL FORM DESTINASI -->
<div class="modal-backdrop" id="destinasiModal" onclick="if(event.target===this) closeDestinasiModal()">
    <div class="modal-dialog">
        <div class="modal-header">
            <div class="modal-title" id="destinasiModalTitle">
                <i data-lucide="plus-circle" style="color:var(--emerald);"></i> Tambah Destinasi Wisata
            </div>
            <button class="modal-close" onclick="closeDestinasiModal()" aria-label="Tutup"><i data-lucide="x" style="width:18px;height:18px;"></i></button>
        </div>
        <form method="POST" id="destinasiForm">
            <div class="modal-body">
                <input type="hidden" name="action" id="destinasiAction" value="create">
                <input type="hidden" name="id" id="destinasiId" value="">

                <div class="form-field mb-16">
                    <label class="form-label">Nama Destinasi Wisata *</label>
                    <input class="form-control" name="nama" id="inputNama" placeholder="Contoh: Candi Borobudur" required>
                </div>

                <div class="form-grid">
                    <div class="form-field">
                        <label class="form-label">Kategori / Jenis *</label>
                        <select class="form-control" name="kategori" id="inputKategori">
                            <option value="Budaya">Budaya</option>
                            <option value="Alam">Alam</option>
                            <option value="Desa Wisata">Desa Wisata</option>
                            <option value="Religi">Religi</option>
                            <option value="Taman">Taman</option>
                            <option value="Buatan">Buatan</option>
                        </select>
                    </div>
                    <div class="form-field">
                        <label class="form-label">Rating (0.0 – 5.0)</label>
                        <input class="form-control" name="rating" id="inputRating" type="number" step="0.1" min="0" max="5" placeholder="4.5" value="4.5">
                    </div>
                </div>

                <div style="font-size:11px;font-weight:800;color:var(--emerald-dark);text-transform:uppercase;letter-spacing:1px;margin:16px 0 10px;">Skala Penilaian K-Means (1 – 10)</div>
                <div style="display:grid;grid-template-columns:repeat(3, 1fr);gap:12px;" class="mb-16">
                    <div class="form-field">
                        <label class="form-label">Daya Tarik</label>
                        <input class="form-control" name="daya_tarik" id="inputDaya" type="number" min="1" max="10" value="7">
                    </div>
                    <div class="form-field">
                        <label class="form-label">Aksesibilitas</label>
                        <input class="form-control" name="aksesibilitas" id="inputAkses" type="number" min="1" max="10" value="7">
                    </div>
                    <div class="form-field">
                        <label class="form-label">Fasilitas</label>
                        <input class="form-control" name="fasilitas" id="inputFasilitas" type="number" min="1" max="10" value="7">
                    </div>
                </div>

                <div class="form-grid mb-16">
                    <div class="form-field">
                        <label class="form-label">Jumlah Pengunjung / Tahun</label>
                        <input class="form-control" name="jumlah_pengunjung" id="inputJumlah" type="number" min="0" placeholder="50000" value="10000">
                    </div>
                    <div class="form-field">
                        <label class="form-label">Sarana & Prasarana</label>
                        <input class="form-control" name="sarana" id="inputSarana" placeholder="Toilet, Parkir, Gazebo">
                    </div>
                </div>

                <div class="form-field mb-16">
                    <label class="form-label">Ringkasan Ulasan Pengunjung</label>
                    <input class="form-control" name="ulasan" id="inputUlasan" placeholder="Sangat menarik, pemandangan indah">
                </div>

                <div style="font-size:11px;font-weight:800;color:var(--slate-light);text-transform:uppercase;letter-spacing:1px;margin:16px 0 10px;">Koordinat Peta (GIS)</div>
                <div class="form-grid">
                    <div class="form-field">
                        <label class="form-label">Latitude</label>
                        <input class="form-control" name="latitude" id="inputLat" type="text" placeholder="-7.6079">
                    </div>
                    <div class="form-field">
                        <label class="form-label">Longitude</label>
                        <input class="form-control" name="longitude" id="inputLng" type="text" placeholder="110.2038">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeDestinasiModal()">Batal</button>
                <button type="submit" class="btn btn-primary" id="btnSubmitForm">Simpan Destinasi</button>
            </div>
        </form>
    </div>
</div>

<script>
function openDestinasiModal() {
    document.getElementById('destinasiForm').reset();
    document.getElementById('destinasiAction').value = 'create';
    document.getElementById('destinasiId').value = '';
    document.getElementById('destinasiModalTitle').innerHTML = '<i data-lucide="plus-circle" style="color:var(--emerald);"></i> Tambah Destinasi Wisata';
    document.getElementById('btnSubmitForm').innerText = 'Simpan Destinasi';
    document.getElementById('destinasiModal').classList.add('open');
    if (window.lucide) lucide.createIcons();
}

function editDestinasi(row) {
    document.getElementById('destinasiAction').value = 'update';
    document.getElementById('destinasiId').value = row.id;
    document.getElementById('inputNama').value = row.nama_destinasi || '';
    document.getElementById('inputKategori').value = row.kategori || 'Budaya';
    document.getElementById('inputDaya').value = row.daya_tarik || 5;
    document.getElementById('inputAkses').value = row.aksesibilitas || 5;
    document.getElementById('inputFasilitas').value = row.fasilitas || 5;
    document.getElementById('inputSarana').value = row.sarana || '';
    document.getElementById('inputUlasan').value = row.ulasan || '';
    document.getElementById('inputJumlah').value = row.jumlah_pengunjung || 0;
    document.getElementById('inputRating').value = row.rating || 0;
    document.getElementById('inputLat').value = row.latitude || '';
    document.getElementById('inputLng').value = row.longitude || '';

    document.getElementById('destinasiModalTitle').innerHTML = '<i data-lucide="edit-3" style="color:var(--emerald);"></i> Edit Destinasi: ' + row.nama_destinasi;
    document.getElementById('btnSubmitForm').innerText = 'Perbarui Destinasi';
    document.getElementById('destinasiModal').classList.add('open');
    if (window.lucide) lucide.createIcons();
}

function closeDestinasiModal() {
    document.getElementById('destinasiModal').classList.remove('open');
}

function filterDestinasiTable() {
    const q = document.getElementById('destinasiSearch').value.toLowerCase();
    const rows = document.querySelectorAll('#destinasiTable tbody tr');
    rows.forEach(r => {
        const text = r.innerText.toLowerCase();
        r.style.display = text.includes(q) ? '' : 'none';
    });
}
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>