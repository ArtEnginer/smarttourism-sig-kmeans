<?php
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();
require_once __DIR__ . '/../../includes/database.php';

$pdo = getDatabaseConnection();
$message = '';

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
        } else {
            $stmt = $pdo->prepare('INSERT INTO tb_users (username, password_hash, nama_lengkap, role, status) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $nama, $role, $status]);
            $message = 'Pengguna baru berhasil ditambahkan.';
        }
    }

    if ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        $role = ($_POST['role'] ?? 'user') === 'admin' ? 'admin' : 'user';
        $status = ($_POST['status'] ?? 'aktif') === 'nonaktif' ? 'nonaktif' : 'aktif';
        if ($id > 0) {
            $stmt = $pdo->prepare('UPDATE tb_users SET role = ?, status = ? WHERE id = ?');
            $stmt->execute([$role, $status, $id]);
            $message = 'Pengguna berhasil diperbarui.';
        }
    }
}

$users = $pdo->query('SELECT id, username, nama_lengkap, role, status, created_at FROM tb_users ORDER BY id DESC')->fetchAll();
?>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<div class="page-content">
    <div class="page-hero">
        <div>
            <div class="page-hero-title">Kelola Pengguna</div>
            <div class="page-hero-subtitle">Tambah pengguna baru, ubah role, dan aktif/nonaktifkan akun dari satu layar.</div>
        </div>
        <div class="page-hero-actions">
            <a href="dashboard.php" class="btn btn-secondary btn-sm">Kembali ke Admin</a>
        </div>
    </div>

    <div class="panel mb-24">
        <div class="panel-body">
            <?php if ($message): ?>
                <div class="notice notice-success"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <form method="POST" class="form-grid">
                <input type="hidden" name="action" value="create">
                <div class="form-field">
                    <label class="form-label">Username</label>
                    <input class="form-control" name="username" placeholder="Username baru" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Nama lengkap</label>
                    <input class="form-control" name="nama_lengkap" placeholder="Nama lengkap" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Password awal</label>
                    <input class="form-control" name="password" type="password" placeholder="Password awal" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Role</label>
                    <select class="form-control" name="role">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-field">
                    <label class="form-label">Status</label>
                    <select class="form-control" name="status">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">Tambah Pengguna</button>
                </div>
            </form>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Nama</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th>Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= (int)$user['id'] ?></td>
                                <td><?= htmlspecialchars($user['username']) ?></td>
                                <td><?= htmlspecialchars($user['nama_lengkap']) ?></td>
                                <td>
                                    <form method="POST" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="id" value="<?= (int)$user['id'] ?>">
                                        <select class="form-control" style="min-width:110px;padding:8px 10px;" name="role">
                                            <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                                            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                        </select>
                                </td>
                                <td>
                                    <select class="form-control" style="min-width:120px;padding:8px 10px;" name="status">
                                        <option value="aktif" <?= $user['status'] === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                                        <option value="nonaktif" <?= $user['status'] === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                                    </select>
                                </td>
                                <td><?= htmlspecialchars($user['created_at'] ?? '-') ?></td>
                                <td><button class="btn btn-sm btn-primary" type="submit">Simpan</button></td>
                                </form>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>