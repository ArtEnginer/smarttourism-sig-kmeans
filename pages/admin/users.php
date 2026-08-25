<?php
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();
require_once __DIR__ . '/../../includes/database.php';

$pdo = getDatabaseConnection();
$message = '';
$messageType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $username = trim($_POST['username'] ?? '');
        $nama = trim($_POST['nama_lengkap'] ?? '');
        $role = ($_POST['role'] ?? 'user') === 'admin' ? 'admin' : 'user';
        $status = ($_POST['status'] ?? 'aktif') === 'nonaktif' ? 'nonaktif' : 'aktif';
        $password = (string)($_POST['password'] ?? '');

        if ($username === '' || $nama === '' || $password === '') {
            $message = 'Username, nama lengkap, dan password wajib diisi.';
            $messageType = 'error';
        } else {
            // Check username duplicate
            $check = $pdo->prepare('SELECT COUNT(*) FROM tb_users WHERE username = ?');
            $check->execute([$username]);
            if ($check->fetchColumn() > 0) {
                $message = 'Username "' . htmlspecialchars($username) . '" sudah digunakan.';
                $messageType = 'error';
            } else {
                $stmt = $pdo->prepare('INSERT INTO tb_users (username, password_hash, nama_lengkap, role, status) VALUES (?, ?, ?, ?, ?)');
                $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $nama, $role, $status]);
                $message = 'Pengguna "' . htmlspecialchars($username) . '" berhasil ditambahkan.';
            }
        }
    }

    if ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        $username = trim($_POST['username'] ?? '');
        $nama = trim($_POST['nama_lengkap'] ?? '');
        $role = ($_POST['role'] ?? 'user') === 'admin' ? 'admin' : 'user';
        $status = ($_POST['status'] ?? 'aktif') === 'nonaktif' ? 'nonaktif' : 'aktif';
        $password = (string)($_POST['password'] ?? '');

        if ($id > 0 && $username !== '' && $nama !== '') {
            if ($password !== '') {
                $stmt = $pdo->prepare('UPDATE tb_users SET username = ?, nama_lengkap = ?, password_hash = ?, role = ?, status = ? WHERE id = ?');
                $stmt->execute([$username, $nama, password_hash($password, PASSWORD_DEFAULT), $role, $status, $id]);
            } else {
                $stmt = $pdo->prepare('UPDATE tb_users SET username = ?, nama_lengkap = ?, role = ?, status = ? WHERE id = ?');
                $stmt->execute([$username, $nama, $role, $status, $id]);
            }
            $message = 'Data pengguna "' . htmlspecialchars($username) . '" berhasil diperbarui.';
        }
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    $u = getCurrentUser();
    // Don't allow deleting self
    if ($delId > 0) {
        $stmt = $pdo->prepare('SELECT username FROM tb_users WHERE id = ?');
        $stmt->execute([$delId]);
        $targetUser = $stmt->fetchColumn();
        if ($targetUser === $u['username']) {
            $message = 'Anda tidak dapat menghapus akun Anda sendiri yang sedang digunakan.';
            $messageType = 'error';
        } else {
            $stmt = $pdo->prepare('DELETE FROM tb_users WHERE id = ?');
            $stmt->execute([$delId]);
            $message = 'Pengguna berhasil dihapus.';
        }
    }
}

$users = $pdo->query('SELECT id, username, nama_lengkap, role, status, created_at FROM tb_users ORDER BY id DESC')->fetchAll();
?>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>

<div class="page-content">

    <!-- Visual Hero Banner Users -->
    <div class="card mb-24" style="background:linear-gradient(135deg, #ffffff 0%, #ecfdf5 100%);border:1px solid var(--emerald-border);overflow:hidden;position:relative;">
        <div class="card-body" style="padding:24px 28px;">
            <div style="display:grid;grid-template-columns:1fr 240px;gap:20px;align-items:center;">
                <div>
                    <div style="display:inline-flex;align-items:center;gap:6px;background:var(--emerald-soft);border:1px solid var(--emerald-border);padding:4px 12px;border-radius:99px;font-size:11px;font-weight:700;color:var(--emerald-dark);text-transform:uppercase;margin-bottom:10px;">
                        <span style="width:7px;height:7px;border-radius:50%;background:var(--emerald);display:inline-block;"></span> Manajemen Akses &bull; Pengguna Sistem
                    </div>
                    <h1 style="font-size:22px;font-weight:800;color:var(--slate);margin-bottom:6px;">
                        Kelola Akun Pengguna SmarTourism
                    </h1>
                    <p style="font-size:12.5px;color:var(--slate-light);margin-bottom:16px;line-height:1.5;">
                        Kelola daftar pengguna terdaftar, atur peran hak akses (Admin / User), status keaktifan akun, dan perbarui kata sandi.
                    </p>
                    <div style="display:flex;gap:10px;flex-wrap:wrap;">
                        <button class="btn btn-primary btn-sm" onclick="openUserModal()" style="box-shadow:0 4px 14px rgba(5,150,105,0.25);">
                            <i data-lucide="user-plus" style="width:15px;height:15px;"></i> Tambah Pengguna Baru
                        </button>
                        <a href="<?= appUrl('admin/dashboard.php') ?>" class="btn btn-secondary btn-sm">
                            <i data-lucide="arrow-left" style="width:15px;height:15px;"></i> Kembali ke Dashboard Admin
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

    <!-- User List Card -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i data-lucide="users" style="width:18px;height:18px;color:var(--emerald);"></i>
                Daftar Pengguna Sistem (<?= count($users) ?>)
            </div>
            <div style="max-width:280px;width:100%;">
                <input type="text" id="userSearch" class="form-control" style="min-height:36px;padding:6px 12px;font-size:12.5px;" placeholder="Cari nama atau username..." onkeyup="filterUserTable()">
            </div>
        </div>
        <div class="table-wrap">
            <table id="userTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pengguna</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Dibuat Tanggal</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $u):
                            $initial = strtoupper(substr($u['nama_lengkap'] ?: $u['username'], 0, 1));
                            $isAdminRole = $u['role'] === 'admin';
                            $isAktif = $u['status'] === 'aktif';
                        ?>
                            <tr>
                                <td style="font-weight:700;color:var(--slate-light);"><?= (int)$u['id'] ?></td>
                                <td>
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <div style="width:32px;height:32px;border-radius:8px;background:<?= $isAdminRole ? 'var(--emerald-soft)' : 'var(--slate-pale)' ?>;color:<?= $isAdminRole ? 'var(--emerald-dark)' : 'var(--slate)' ?>;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:13px;border:1px solid var(--border);">
                                            <?= htmlspecialchars($initial) ?>
                                        </div>
                                        <div>
                                            <div style="font-weight:700;color:var(--slate);"><?= htmlspecialchars($u['nama_lengkap']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><code style="background:var(--slate-pale);padding:2px 8px;border-radius:6px;font-size:12px;color:var(--slate);"><?= htmlspecialchars($u['username']) ?></code></td>
                                <td>
                                    <span class="badge <?= $isAdminRole ? 'badge-green' : 'badge-blue' ?>">
                                        <i data-lucide="<?= $isAdminRole ? 'shield-check' : 'user' ?>" style="width:11px;height:11px;"></i>
                                        <?= ucfirst($u['role']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?= $isAktif ? 'badge-green' : 'badge-red' ?>">
                                        <span style="width:6px;height:6px;border-radius:50%;background:currentColor;display:inline-block;"></span>
                                        <?= ucfirst($u['status']) ?>
                                    </span>
                                </td>
                                <td style="font-size:12px;color:var(--slate-light);"><?= htmlspecialchars($u['created_at'] ?? '-') ?></td>
                                <td style="text-align:right;white-space:nowrap;">
                                    <button class="btn btn-secondary btn-sm" onclick='editUser(<?= json_encode($u) ?>)'>
                                        <i data-lucide="edit-3" style="width:13px;height:13px;"></i> Edit
                                    </button>
                                    <a href="?delete=<?= $u['id'] ?>" onclick="return confirm('Hapus akun <?= htmlspecialchars($u['username']) ?>?')" class="btn btn-sm" style="background:#fee2e2;color:var(--red);border-color:#fca5a5;">
                                        <i data-lucide="trash-2" style="width:13px;height:13px;"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align:center;padding:32px;color:var(--slate-light);">Belum ada pengguna terdaftar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- MODAL FORM USER -->
<div class="modal-backdrop" id="userModal" onclick="if(event.target===this) closeUserModal()">
    <div class="modal-dialog">
        <div class="modal-header">
            <div class="modal-title" id="userModalTitle">
                <i data-lucide="user-plus" style="color:var(--emerald);"></i> Tambah Pengguna Baru
            </div>
            <button class="modal-close" onclick="closeUserModal()" aria-label="Tutup"><i data-lucide="x" style="width:18px;height:18px;"></i></button>
        </div>
        <form method="POST" id="userForm">
            <div class="modal-body">
                <input type="hidden" name="action" id="userAction" value="create">
                <input type="hidden" name="id" id="userId" value="">

                <div class="form-grid mb-16">
                    <div class="form-field">
                        <label class="form-label">Username *</label>
                        <input class="form-control" name="username" id="inputUserUsername" placeholder="username" required>
                    </div>
                    <div class="form-field">
                        <label class="form-label">Nama Lengkap *</label>
                        <input class="form-control" name="nama_lengkap" id="inputUserNama" placeholder="Nama Lengkap" required>
                    </div>
                </div>

                <div class="form-field mb-16">
                    <label class="form-label" id="labelPassword">Password *</label>
                    <input class="form-control" name="password" id="inputUserPassword" type="password" placeholder="Masukkan password">
                    <div style="font-size:11px;color:var(--slate-light);margin-top:4px;" id="passwordHelp">
                        Gunakan kombinasi password yang aman.
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-field">
                        <label class="form-label">Role Akses</label>
                        <select class="form-control" name="role" id="inputUserRole">
                            <option value="user">User (Lihat Dashboard & Peta)</option>
                            <option value="admin">Admin (Kelola Data & Pengguna)</option>
                        </select>
                    </div>
                    <div class="form-field">
                        <label class="form-label">Status Akun</label>
                        <select class="form-control" name="status" id="inputUserStatus">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeUserModal()">Batal</button>
                <button type="submit" class="btn btn-primary" id="btnSubmitUserForm">Simpan Pengguna</button>
            </div>
        </form>
    </div>
</div>

<script>
function openUserModal() {
    document.getElementById('userForm').reset();
    document.getElementById('userAction').value = 'create';
    document.getElementById('userId').value = '';
    document.getElementById('inputUserPassword').required = true;
    document.getElementById('labelPassword').innerText = 'Password *';
    document.getElementById('passwordHelp').innerText = 'Gunakan kombinasi password yang aman.';
    document.getElementById('userModalTitle').innerHTML = '<i data-lucide="user-plus" style="color:var(--emerald);"></i> Tambah Pengguna Baru';
    document.getElementById('btnSubmitUserForm').innerText = 'Simpan Pengguna';
    document.getElementById('userModal').classList.add('open');
    if (window.lucide) lucide.createIcons();
}

function editUser(user) {
    document.getElementById('userAction').value = 'update';
    document.getElementById('userId').value = user.id;
    document.getElementById('inputUserUsername').value = user.username || '';
    document.getElementById('inputUserNama').value = user.nama_lengkap || '';
    document.getElementById('inputUserPassword').value = '';
    document.getElementById('inputUserPassword').required = false;
    document.getElementById('labelPassword').innerText = 'Password Baru (Opsional)';
    document.getElementById('passwordHelp').innerText = 'Biarkan kosong jika tidak ingin mengubah password.';
    document.getElementById('inputUserRole').value = user.role || 'user';
    document.getElementById('inputUserStatus').value = user.status || 'aktif';

    document.getElementById('userModalTitle').innerHTML = '<i data-lucide="edit-3" style="color:var(--emerald);"></i> Edit Akun: ' + user.username;
    document.getElementById('btnSubmitUserForm').innerText = 'Perbarui Pengguna';
    document.getElementById('userModal').classList.add('open');
    if (window.lucide) lucide.createIcons();
}

function closeUserModal() {
    document.getElementById('userModal').classList.remove('open');
}

function filterUserTable() {
    const q = document.getElementById('userSearch').value.toLowerCase();
    const rows = document.querySelectorAll('#userTable tbody tr');
    rows.forEach(r => {
        const text = r.innerText.toLowerCase();
        r.style.display = text.includes(q) ? '' : 'none';
    });
}
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>