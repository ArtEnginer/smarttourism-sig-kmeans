<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/database.php';
requireAdmin();

$pdo = getDatabaseConnection();
$message = '';

// Handle create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
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
    } else {
        $stmt = $pdo->prepare('INSERT INTO tb_destinasi (nama_destinasi, kategori, daya_tarik, aksesibilitas, fasilitas, sarana, ulasan, jumlah_pengunjung, rating, latitude, longitude, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$nama, $kategori, $daya, $akses, $fasilitas, $sarana, $ulasan, $jumlah, $rating, $lat, $lng, 'aktif']);
        $message = 'Destinasi berhasil ditambahkan.';
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($id > 0) {
        $stmt = $pdo->prepare('DELETE FROM tb_destinasi WHERE id = ?');
        $stmt->execute([$id]);
        $message = 'Destinasi dihapus.';
    }
}

$stmt = $pdo->query('SELECT * FROM tb_destinasi ORDER BY id DESC');
$all = $stmt->fetchAll();
?>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<div class="page-content">
    <div class="page-hero">
        <div>
            <div class="page-hero-title">Kelola Destinasi</div>
            <div class="page-hero-subtitle">Tambah atau hapus destinasi wisata agar data clustering tetap akurat dan mudah dikelola.</div>
        </div>
        <div class="page-hero-actions">
            <a href="../data_wisata.php" class="btn btn-secondary btn-sm">Kembali ke Data Wisata</a>
        </div>
    </div>

    <div class="panel mb-24">
        <div class="panel-body">
            <?php if ($message): ?>
                <div class="notice notice-success"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <form method="POST" class="form-grid">
                <input name="action" type="hidden" value="create">
                <div class="form-field">
                    <label class="form-label">Nama destinasi</label>
                    <input class="form-control" name="nama" placeholder="Nama destinasi" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Kategori</label>
                    <input class="form-control" name="kategori" placeholder="Kategori (Alam/Budaya/... )" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Daya tarik</label>
                    <input class="form-control" name="daya_tarik" type="number" min="1" max="10" placeholder="Daya Tarik (1-10)" value="5">
                </div>
                <div class="form-field">
                    <label class="form-label">Aksesibilitas</label>
                    <input class="form-control" name="aksesibilitas" type="number" min="1" max="10" placeholder="Aksesibilitas (1-10)" value="5">
                </div>
                <div class="form-field">
                    <label class="form-label">Fasilitas</label>
                    <input class="form-control" name="fasilitas" type="number" min="1" max="10" placeholder="Fasilitas (1-10)" value="5">
                </div>
                <div class="form-field">
                    <label class="form-label">Sarana</label>
                    <input class="form-control" name="sarana" placeholder="Sarana">
                </div>
                <div class="form-field">
                    <label class="form-label">Ulasan</label>
                    <input class="form-control" name="ulasan" placeholder="Ulasan">
                </div>
                <div class="form-field">
                    <label class="form-label">Jumlah pengunjung</label>
                    <input class="form-control" name="jumlah_pengunjung" type="number" placeholder="Jumlah pengunjung" value="0">
                </div>
                <div class="form-field">
                    <label class="form-label">Rating</label>
                    <input class="form-control" name="rating" type="number" step="0.1" placeholder="Rating (0-5)" value="0">
                </div>
                <div class="form-field">
                    <label class="form-label">Latitude</label>
                    <input class="form-control" name="latitude" type="text" placeholder="Latitude">
                </div>
                <div class="form-field">
                    <label class="form-label">Longitude</label>
                    <input class="form-control" name="longitude" type="text" placeholder="Longitude">
                </div>
                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">Tambah Destinasi</button>
                </div>
            </form>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Pengunjung/Thn</th>
                            <th>Rating</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']) ?></td>
                                <td><?= htmlspecialchars($row['nama_destinasi']) ?></td>
                                <td><?= htmlspecialchars($row['kategori']) ?></td>
                                <td><?= number_format($row['jumlah_pengunjung'], 0, ',', '.') ?></td>
                                <td><?= htmlspecialchars($row['rating']) ?></td>
                                <td><a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Hapus destinasi ini?')" class="btn btn-sm">Hapus</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>