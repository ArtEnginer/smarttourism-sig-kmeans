<?php
require_once '../includes/header.php';
require_once '../includes/api_helper.php';

$user = getCurrentUser();
$userId = (int) ($user['id'] ?? 0);
$userKeys = [];

try {
  $pdo = getDatabaseConnection();
  ensureApiKeyTableExists($pdo);

  if ($userId <= 0 && isset($user['username'])) {
    $uStmt = $pdo->prepare("SELECT id FROM tb_users WHERE username = ? LIMIT 1");
    $uStmt->execute([$user['username']]);
    $userId = (int) $uStmt->fetchColumn();
  }

  if ($userId > 0) {
    $kStmt = $pdo->prepare("SELECT id, name, api_key, status, created_at, last_used_at FROM tb_api_keys WHERE user_id = ? ORDER BY id DESC");
    $kStmt->execute([$userId]);
    $userKeys = $kStmt->fetchAll(PDO::FETCH_ASSOC);
  }
} catch (Throwable $e) {
  $userKeys = [];
}

$baseUrl = rtrim(appUrl(''), '/');
?>
<div class="page-content">

  <!-- Hero Banner -->
  <div class="card mb-24" style="background: linear-gradient(135deg, #059669 0%, #047857 60%, #065f46 100%); color: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(5, 150, 105, 0.25); position: relative;">
    <div class="card-body" style="padding: 36px 40px; position: relative; z-index: 2;">
      <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px; flex-wrap: wrap;">
        <span style="background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(8px); padding: 4px 14px; border-radius: 99px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid rgba(255, 255, 255, 0.3);">
          <i data-lucide="zap" style="width: 12px; height: 12px; display: inline-block; vertical-align: -1px; margin-right: 4px;"></i> REST API v1.0
        </span>
        <span style="background: rgba(239, 68, 68, 0.25); border: 1px solid rgba(248, 113, 113, 0.4); padding: 4px 12px; border-radius: 99px; font-size: 11px; font-weight: 700; color: #fef2f2;">
          <i data-lucide="lock" style="width: 11px; height: 11px; display: inline-block; vertical-align: -1px; margin-right: 3px;"></i> Strict API Key Required
        </span>
        <span style="background: rgba(255, 255, 255, 0.15); padding: 4px 12px; border-radius: 99px; font-size: 11px; font-weight: 600;">
          CORS Enabled
        </span>
        <span style="background: rgba(255, 255, 255, 0.15); padding: 4px 12px; border-radius: 99px; font-size: 11px; font-weight: 600;">
          JSON Format
        </span>
      </div>

      <h1 style="font-size: 28px; font-weight: 800; margin-bottom: 10px; line-height: 1.25; letter-spacing: -0.5px; color:#ffffff;">
        Portal Developer &amp; Dokumentasi REST API
      </h1>
      <p style="font-size: 14px; opacity: 0.92; max-width: 680px; line-height: 1.6; margin-bottom: 24px;">
        Integrasikan data destinasi pariwisata, peta lokasi SIG, statistik wilayah, dan hasil kalkulasi <strong>K-Means Clustering</strong> Kabupaten Magelang secara mudah dan real-time ke dalam aplikasi web, Android, iOS, atau sistem Anda.
      </p>

      <div style="display: flex; gap: 12px; flex-wrap: wrap;">
        <a href="#playground" class="btn" style="background: white; color: #047857; font-weight: 700; border: none; padding: 10px 20px; border-radius: 12px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); transition: all 0.2s;">
          <i data-lucide="play" style="width: 16px; height: 16px;"></i> Live API Playground
        </a>
        <a href="#key-manager" class="btn" style="background: rgba(255,255,255,0.15); color: white; border: 1px solid rgba(255,255,255,0.3); font-weight: 600; padding: 10px 20px; border-radius: 12px; display: inline-flex; align-items: center; gap: 8px; backdrop-filter: blur(4px);">
          <i data-lucide="key" style="width: 16px; height: 16px;"></i> Kelola API Key Saya
        </a>
        <a href="#reference" class="btn" style="background: rgba(255,255,255,0.15); color: white; border: 1px solid rgba(255,255,255,0.3); font-weight: 600; padding: 10px 20px; border-radius: 12px; display: inline-flex; align-items: center; gap: 8px; backdrop-filter: blur(4px);">
          <i data-lucide="book-open" style="width: 16px; height: 16px;"></i> Spesifikasi Endpoint
        </a>
      </div>
    </div>
  </div>

  <!-- API Key Manager Section -->
  <div id="key-manager" class="card mb-24" style="border: 1px solid var(--emerald-border); border-radius: 16px; background: white; box-shadow: var(--shadow-sm);">
    <div class="card-header" style="background: #f0fdf4; border-bottom: 1px solid var(--emerald-border); padding: 18px 24px; border-top-left-radius: 16px; border-top-right-radius: 16px; display: flex; justify-content: space-between; align-items: center;">
      <div style="display: flex; align-items: center; gap: 10px;">
        <div style="background: var(--emerald); color: white; width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(5,150,105,0.2);">
          <i data-lucide="key-round" style="width: 18px; height: 18px;"></i>
        </div>
        <div>
          <h2 style="font-size: 17px; font-weight: 700; color: var(--ink); margin: 0;">Manajemen API Key Developer</h2>
          <span style="font-size: 12px; color: var(--muted);">Buat token autentikasi untuk pengembang pihak ketiga</span>
        </div>
      </div>
      <button class="btn btn-primary btn-sm" onclick="showCreateKeyModal()" style="display: inline-flex; align-items: center; gap: 6px; border-radius: 10px;">
        <i data-lucide="plus-circle" style="width: 15px; height: 15px;"></i> Buat API Key Baru
      </button>
    </div>

    <div class="card-body" style="padding: 24px;">
      <!-- Key List -->
      <div id="keys-container">
        <?php if (empty($userKeys)): ?>
          <div style="text-align: center; padding: 32px 16px; background: #fafafa; border: 2px dashed #e2e8f0; border-radius: 14px;">
            <i data-lucide="shield-alert" style="width: 40px; height: 40px; color: #94a3b8; margin-bottom: 10px;"></i>
            <h4 style="font-size: 15px; font-weight: 700; color: var(--ink); margin-bottom: 4px;">Belum Ada API Key Active</h4>
            <p style="font-size: 13px; color: var(--muted); max-width: 420px; margin: 0 auto 16px;">Anda belum membuat API Key. Klik tombol di bawah untuk membuat API Key baru dan mulai menggunakan layanan API secara penuh.</p>
            <button class="btn btn-primary btn-sm" onclick="showCreateKeyModal()">
              <i data-lucide="key" style="width: 14px; height: 14px;"></i> Buat API Key Pertama
            </button>
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table" style="width: 100%; border-collapse: separate; border-spacing: 0; font-size: 13px;">
              <thead>
                <tr style="background: #f8fafc; color: var(--muted); text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">
                  <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); border-top-left-radius: 8px;">Nama Aplikasi / Deskripsi</th>
                  <th style="padding: 12px 16px; border-bottom: 1px solid var(--line);">API Token Key</th>
                  <th style="padding: 12px 16px; border-bottom: 1px solid var(--line);">Status</th>
                  <th style="padding: 12px 16px; border-bottom: 1px solid var(--line);">Dibuat Tanggal</th>
                  <th style="padding: 12px 16px; border-bottom: 1px solid var(--line);">Terakhir Dipakai</th>
                  <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); text-align: right; border-top-right-radius: 8px;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($userKeys as $kRecord): ?>
                  <tr style="border-bottom: 1px solid var(--line);">
                    <td style="padding: 14px 16px; font-weight: 700; color: var(--ink);">
                      <?= htmlspecialchars($kRecord['name']) ?>
                    </td>
                    <td style="padding: 14px 16px;">
                      <div style="display: flex; align-items: center; gap: 8px;">
                        <code style="background: #f1f5f9; padding: 4px 8px; border-radius: 6px; font-family: monospace; font-size: 12px; color: var(--emerald-d); border: 1px solid #cbd5e1;">
                          <?= htmlspecialchars($kRecord['api_key']) ?>
                        </code>
                        <button class="btn btn-secondary btn-sm" onclick="copyToClipboard('<?= htmlspecialchars($kRecord['api_key']) ?>', this)" style="padding: 4px 8px; font-size: 11px;" title="Salin API Key">
                          <i data-lucide="copy" style="width: 13px; height: 13px;"></i>
                        </button>
                      </div>
                    </td>
                    <td style="padding: 14px 16px;">
                      <?php if ($kRecord['status'] === 'active'): ?>
                        <span class="badge" style="background: #dcfce7; color: #15803d; border: 1px solid #86efac; padding: 4px 10px; border-radius: 99px; font-size: 11px; font-weight: 700;">
                          <span style="width: 6px; height: 6px; background: #16a34a; border-radius: 50%; display: inline-block; margin-right: 4px;"></span> Aktif
                        </span>
                      <?php else: ?>
                        <span class="badge" style="background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; padding: 4px 10px; border-radius: 99px; font-size: 11px; font-weight: 700;">
                          Dicabut
                        </span>
                      <?php endif; ?>
                    </td>
                    <td style="padding: 14px 16px; color: var(--muted); font-size: 12px;">
                      <?= date('d M Y H:i', strtotime($kRecord['created_at'])) ?>
                    </td>
                    <td style="padding: 14px 16px; color: var(--muted); font-size: 12px;">
                      <?= $kRecord['last_used_at'] ? date('d M Y H:i', strtotime($kRecord['last_used_at'])) : '<em style="color:#94a3b8">Belum pernah</em>' ?>
                    </td>
                    <td style="padding: 14px 16px; text-align: right;">
                      <?php if ($kRecord['status'] === 'active'): ?>
                        <button class="btn btn-danger btn-sm" onclick="revokeKey(<?= (int)$kRecord['id'] ?>)" style="padding: 4px 10px; font-size: 11px; border-radius: 8px;">
                          <i data-lucide="trash-2" style="width: 13px; height: 13px;"></i> Cabut Key
                        </button>
                      <?php else: ?>
                        <span style="color: #94a3b8; font-size: 12px;">-</span>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>

      <!-- Quick Auth Usage Note -->
      <div style="margin-top: 20px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
        <div>
          <div style="font-weight: 700; font-size: 12.5px; color: var(--ink); margin-bottom: 6px; display: flex; align-items: center; gap: 6px;">
            <i data-lucide="shield-check" style="width: 15px; height: 15px; color: var(--emerald);"></i> Metode Header HTTP (Rekomendasi)
          </div>
          <code style="background: #ffffff; padding: 6px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 12px; color: #0f172a; display: block;">
            X-API-Key: smt_your_api_key_here
          </code>
        </div>
        <div>
          <div style="font-weight: 700; font-size: 12.5px; color: var(--ink); margin-bottom: 6px; display: flex; align-items: center; gap: 6px;">
            <i data-lucide="link" style="width: 15px; height: 15px; color: var(--emerald);"></i> Metode Query Parameter URL
          </div>
          <code style="background: #ffffff; padding: 6px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 12px; color: #0f172a; display: block;">
            <?= $baseUrl ?>/api/v1/destinasi.php?api_key=smt_xxxx
          </code>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Create API Key -->
  <div id="modalCreateKey" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: white; border-radius: 20px; width: 100%; max-width: 480px; padding: 28px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); border: 1px solid #e2e8f0; animation: modalPop 0.2s ease-out;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
        <h3 style="font-size: 18px; font-weight: 800; color: var(--ink); margin: 0; display: flex; align-items: center; gap: 8px;">
          <i data-lucide="key" style="color: var(--emerald); width: 20px; height: 20px;"></i> Buat API Key Baru
        </h3>
        <button onclick="hideCreateKeyModal()" style="background: none; border: none; font-size: 20px; color: #94a3b8; cursor: pointer; padding: 4px;">&times;</button>
      </div>

      <form id="formKey" onsubmit="handleCreateKey(event)">
        <div style="margin-bottom: 18px;">
          <label style="display: block; font-size: 13px; font-weight: 700; color: var(--ink); margin-bottom: 6px;">Nama Aplikasi / Client</label>
          <input type="text" id="appNameInput" placeholder="Contoh: Aplikasi Android Tourist Guide" class="input" style="width: 100%; padding: 12px 14px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 14px;" required>
          <small style="color: var(--muted); font-size: 11.5px; margin-top: 4px; display: block;">Berikan nama pengenal agar mudah membedakan API Key.</small>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px;">
          <button type="button" onclick="hideCreateKeyModal()" class="btn btn-secondary" style="padding: 10px 18px; border-radius: 10px;">Batal</button>
          <button type="submit" id="btnSubmitKey" class="btn btn-primary" style="padding: 10px 20px; border-radius: 10px;">
            <i data-lucide="check" style="width: 16px; height: 16px;"></i> Generate Token API
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Live API Playground / Tester -->
  <div id="playground" class="card mb-24" style="border: 1px solid var(--emerald-border); border-radius: 16px; background: white; box-shadow: var(--shadow-md); overflow: hidden;">
    <div class="card-header" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: white; padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
      <div style="display: flex; align-items: center; gap: 12px;">
        <div style="background: var(--emerald); color: white; width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(5,150,105,0.4);">
          <i data-lucide="terminal" style="width: 20px; height: 20px;"></i>
        </div>
        <div>
          <h2 style="font-size: 18px; font-weight: 800; color: white; margin: 0;">Interactive API Playground</h2>
          <span style="font-size: 12px; color: #94a3b8;">Uji coba endpoint API secara live langsung dari browser Anda</span>
        </div>
      </div>
      <span style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15); padding: 4px 12px; border-radius: 99px; font-size: 12px; color: #38bdf8; font-weight: 600;">
        Live Request Execution
      </span>
    </div>

    <div class="card-body" style="padding: 24px;">
      <div style="display: grid; grid-template-columns: 340px 1fr; gap: 24px;">
        
        <!-- Request Builder Panel -->
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px;">
          <h3 style="font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: var(--emerald-d); margin-bottom: 16px; display: flex; align-items: center; gap: 6px;">
            <i data-lucide="sliders" style="width: 16px; height: 16px;"></i> Request Builder
          </h3>

          <div style="margin-bottom: 16px;">
            <label style="display: block; font-size: 12px; font-weight: 700; color: var(--ink); margin-bottom: 6px;">Pilih Endpoint API</label>
            <select id="endpointSelect" class="input" onchange="updatePlaygroundFields()" style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 13px; background: white; font-weight: 600;">
              <option value="destinasi_list">GET /api/v1/destinasi.php (Daftar Wisata)</option>
              <option value="destinasi_detail">GET /api/v1/destinasi.php?id={id} (Detail Single)</option>
              <option value="clustering">GET /api/v1/clustering.php (Hasil K-Means)</option>
              <option value="stats">GET /api/v1/stats.php (Ringkasan Statistik)</option>
              <option value="keys">GET /api/v1/keys.php (API Keys User)</option>
            </select>
          </div>

          <!-- Dynamic Parameter Form Fields -->
          <div id="dynamicParams">
            <!-- Fields generated dynamically by JS -->
          </div>

          <div style="margin-top: 16px; margin-bottom: 20px;">
            <label style="display: block; font-size: 12px; font-weight: 700; color: var(--ink); margin-bottom: 6px;">API Key (Opsional)</label>
            <select id="apiKeySelect" class="input" style="width: 100%; padding: 9px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 12.5px; background: white;">
              <option value="">-- Tanpa API Key (Publik) --</option>
              <?php foreach ($userKeys as $kRec): ?>
                <?php if ($kRec['status'] === 'active'): ?>
                  <option value="<?= htmlspecialchars($kRec['api_key']) ?>">
                    <?= htmlspecialchars($kRec['name']) ?> (<?= substr($kRec['api_key'], 0, 10) ?>...)
                  </option>
                <?php endif; ?>
              <?php endforeach; ?>
            </select>
          </div>

          <button id="btnRunTest" class="btn btn-primary" onclick="executeApiTest()" style="width: 100%; padding: 12px; border-radius: 10px; font-weight: 700; font-size: 14px; box-shadow: 0 4px 14px rgba(5,150,105,0.3); display: flex; align-items: center; justify-content: center; gap: 8px;">
            <i data-lucide="play" style="width: 16px; height: 16px;"></i> Send Request
          </button>
        </div>

        <!-- Response & Code Preview Panel -->
        <div style="display: flex; flex-direction: column; gap: 16px;">
          <!-- Request URL Preview -->
          <div style="background: #0f172a; border-radius: 10px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between; gap: 12px; border: 1px solid #1e293b;">
            <div style="display: flex; align-items: center; gap: 10px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
              <span style="background: #059669; color: white; padding: 2px 8px; border-radius: 6px; font-size: 11px; font-weight: 800;">GET</span>
              <code id="urlPreview" style="color: #38bdf8; font-family: monospace; font-size: 12.5px; overflow: hidden; text-overflow: ellipsis;">
                <?= $baseUrl ?>/api/v1/destinasi.php
              </code>
            </div>
            <button class="btn btn-sm" onclick="copyToClipboard(document.getElementById('urlPreview').innerText, this)" style="background: rgba(255,255,255,0.1); color: white; border: none; padding: 4px 8px; border-radius: 6px; font-size: 11px;">
              <i data-lucide="copy" style="width: 13px; height: 13px;"></i> Copy URL
            </button>
          </div>

          <!-- Code Snippets Generator Tabs -->
          <div style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 12px; overflow: hidden;">
            <div style="background: #f1f5f9; border-bottom: 1px solid #cbd5e1; padding: 8px 12px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
              <div style="display: flex; gap: 4px;">
                <button class="snippet-tab active" onclick="switchSnippetTab('curl', this)">cURL</button>
                <button class="snippet-tab" onclick="switchSnippetTab('js', this)">JavaScript (Fetch)</button>
                <button class="snippet-tab" onclick="switchSnippetTab('php', this)">PHP</button>
                <button class="snippet-tab" onclick="switchSnippetTab('python', this)">Python</button>
              </div>
              <button class="btn btn-sm btn-secondary" onclick="copyActiveSnippet(this)" style="font-size: 11px; padding: 4px 10px; border-radius: 6px;">
                <i data-lucide="copy" style="width: 12px; height: 12px;"></i> Copy Snippet
              </button>
            </div>
            <pre id="snippetCode" style="margin: 0; padding: 14px 16px; background: #090d16; color: #a7f3d0; font-family: 'Consolas', 'Fira Code', monospace; font-size: 12px; line-height: 1.5; max-height: 140px; overflow-y: auto; white-space: pre-wrap; word-break: break-all;"></pre>
          </div>

          <!-- Response Viewer -->
          <div style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 12px; overflow: hidden; flex-grow: 1; display: flex; flex-direction: column;">
            <div style="background: #f8fafc; border-bottom: 1px solid #cbd5e1; padding: 10px 16px; display: flex; justify-content: space-between; align-items: center;">
              <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-weight: 700; font-size: 12.5px; color: var(--ink);">Response Output</span>
                <span id="responseStatus" class="badge" style="background: #e2e8f0; color: #475569; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 6px;">
                  Ready
                </span>
                <span id="responseTime" style="font-size: 11px; color: var(--muted); font-weight: 600;"></span>
              </div>
              <button class="btn btn-sm btn-secondary" onclick="copyToClipboard(document.getElementById('jsonOutput').innerText, this)" style="font-size: 11px; padding: 4px 10px; border-radius: 6px;">
                <i data-lucide="copy" style="width: 12px; height: 12px;"></i> Copy JSON
              </button>
            </div>
            <pre id="jsonOutput" style="margin: 0; padding: 16px; background: #090d16; color: #38bdf8; font-family: 'Consolas', 'Fira Code', monospace; font-size: 12.5px; line-height: 1.5; height: 320px; overflow: auto; white-space: pre-wrap; word-break: break-all;">Klik "Send Request" untuk menjalankan pengujian API live...</pre>
          </div>

        </div>

      </div>
    </div>
  </div>

  <!-- Complete API Endpoint Reference Documentation -->
  <div id="reference" class="card mb-24" style="border: 1px solid var(--emerald-border); border-radius: 16px; background: white; box-shadow: var(--shadow-sm);">
    <div class="card-header" style="background: #f0fdf4; border-bottom: 1px solid var(--emerald-border); padding: 20px 24px; border-top-left-radius: 16px; border-top-right-radius: 16px;">
      <h2 style="font-size: 18px; font-weight: 800; color: var(--ink); margin: 0; display: flex; align-items: center; gap: 8px;">
        <i data-lucide="book-open" style="color: var(--emerald); width: 20px; height: 20px;"></i> Referensi Spesifikasi Endpoint API
      </h2>
      <span style="font-size: 12.5px; color: var(--muted); margin-top: 4px; display: block;">Rincian struktur request parameter, tipe data, dan contoh respon JSON untuk semua endpoint.</span>
    </div>

    <div class="card-body" style="padding: 24px;">

      <!-- Accordion Endpoint 1 -->
      <div class="endpoint-card mb-20" style="border: 1px solid #e2e8f0; border-radius: 14px; overflow: hidden;">
        <div style="background: #f8fafc; padding: 16px 20px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
          <div style="display: flex; align-items: center; gap: 12px;">
            <span style="background: #059669; color: white; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 800;">GET</span>
            <code style="font-size: 14px; font-weight: 700; color: #0f172a;">/api/v1/destinasi.php</code>
          </div>
          <span style="font-size: 12.5px; color: var(--muted); font-weight: 600;">Daftar Wisata &amp; Detail Destinasi</span>
        </div>
        <div style="padding: 20px;">
          <p style="font-size: 13.5px; color: var(--ink); margin-bottom: 16px; line-height: 1.6;">
            Mengambil daftar destinasi pariwisata Kabupaten Magelang beserta koordinat lat/lng SIG, ulasan, rating, serta statistik pengunjung. Mendukung pencarian teks, filter kategori, pengurutan, dan pagination.
          </p>

          <h4 style="font-size: 13px; font-weight: 700; text-transform: uppercase; color: var(--emerald-d); margin-bottom: 10px;">Query Parameters</h4>
          <div class="table-responsive mb-16">
            <table class="table" style="width: 100%; font-size: 12.5px; border-collapse: collapse;">
              <thead>
                <tr style="background: #f1f5f9; color: #475569; text-align: left;">
                  <th style="padding: 8px 12px;">Parameter</th>
                  <th style="padding: 8px 12px;">Tipe</th>
                  <th style="padding: 8px 12px;">Wajib</th>
                  <th style="padding: 8px 12px;">Default</th>
                  <th style="padding: 8px 12px;">Deskripsi</th>
                </tr>
              </thead>
              <tbody>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                  <td style="padding: 8px 12px;"><code>id</code></td>
                  <td style="padding: 8px 12px;">Integer</td>
                  <td style="padding: 8px 12px;">Opsional</td>
                  <td style="padding: 8px 12px;">-</td>
                  <td style="padding: 8px 12px;">Jika diisi, mengembalikan detail 1 destinasi wisata spesifik.</td>
                </tr>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                  <td style="padding: 8px 12px;"><code>kategori</code></td>
                  <td style="padding: 8px 12px;">String</td>
                  <td style="padding: 8px 12px;">Opsional</td>
                  <td style="padding: 8px 12px;">-</td>
                  <td style="padding: 8px 12px;">Filter berdasarkan kategori (e.g. <code>Budaya</code>, <code>Alam</code>, <code>Desa Wisata</code>, <code>Religi</code>, <code>Taman</code>).</td>
                </tr>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                  <td style="padding: 8px 12px;"><code>search</code></td>
                  <td style="padding: 8px 12px;">String</td>
                  <td style="padding: 8px 12px;">Opsional</td>
                  <td style="padding: 8px 12px;">-</td>
                  <td style="padding: 8px 12px;">Pencarian nama destinasi wisata (partial keyword match).</td>
                </tr>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                  <td style="padding: 8px 12px;"><code>sort_by</code></td>
                  <td style="padding: 8px 12px;">String</td>
                  <td style="padding: 8px 12px;">Opsional</td>
                  <td style="padding: 8px 12px;"><code>id</code></td>
                  <td style="padding: 8px 12px;">Urutkan data: <code>id</code>, <code>nama</code>, <code>rating</code>, <code>pengunjung</code>, <code>ulasan</code>, <code>daya_tarik</code>.</td>
                </tr>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                  <td style="padding: 8px 12px;"><code>order</code></td>
                  <td style="padding: 8px 12px;">String</td>
                  <td style="padding: 8px 12px;">Opsional</td>
                  <td style="padding: 8px 12px;"><code>asc</code></td>
                  <td style="padding: 8px 12px;">Arah pengurutan: <code>asc</code> (A-Z / terkecil) atau <code>desc</code> (Z-A / terbesar).</td>
                </tr>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                  <td style="padding: 8px 12px;"><code>page</code></td>
                  <td style="padding: 8px 12px;">Integer</td>
                  <td style="padding: 8px 12px;">Opsional</td>
                  <td style="padding: 8px 12px;"><code>1</code></td>
                  <td style="padding: 8px 12px;">Nomor halaman pagination data.</td>
                </tr>
                <tr>
                  <td style="padding: 8px 12px;"><code>limit</code></td>
                  <td style="padding: 8px 12px;">Integer</td>
                  <td style="padding: 8px 12px;">Opsional</td>
                  <td style="padding: 8px 12px;"><code>10</code></td>
                  <td style="padding: 8px 12px;">Jumlah baris data per halaman (maksimum 100).</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Accordion Endpoint 2 -->
      <div class="endpoint-card mb-20" style="border: 1px solid #e2e8f0; border-radius: 14px; overflow: hidden;">
        <div style="background: #f8fafc; padding: 16px 20px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
          <div style="display: flex; align-items: center; gap: 12px;">
            <span style="background: #059669; color: white; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 800;">GET</span>
            <code style="font-size: 14px; font-weight: 700; color: #0f172a;">/api/v1/clustering.php</code>
          </div>
          <span style="font-size: 12.5px; color: var(--muted); font-weight: 600;">Hasil K-Means Clustering</span>
        </div>
        <div style="padding: 20px;">
          <p style="font-size: 13.5px; color: var(--ink); margin-bottom: 16px; line-height: 1.6;">
            Mengembalikan hasil perhitungan algoritma <strong>K-Means Clustering</strong> pada atribut destinasi (Daya Tarik, Aksesibilitas, Fasilitas, Sarana, Ulasan). Menyediakan nilai Vektor Centroid, pengelompokan prioritas (Tinggi, Sedang, Rendah), dan total iterasi konvergensi.
          </p>

          <h4 style="font-size: 13px; font-weight: 700; text-transform: uppercase; color: var(--emerald-d); margin-bottom: 10px;">Query Parameters</h4>
          <div class="table-responsive mb-16">
            <table class="table" style="width: 100%; font-size: 12.5px; border-collapse: collapse;">
              <thead>
                <tr style="background: #f1f5f9; color: #475569; text-align: left;">
                  <th style="padding: 8px 12px;">Parameter</th>
                  <th style="padding: 8px 12px;">Tipe</th>
                  <th style="padding: 8px 12px;">Wajib</th>
                  <th style="padding: 8px 12px;">Default</th>
                  <th style="padding: 8px 12px;">Deskripsi</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td style="padding: 8px 12px;"><code>k</code></td>
                  <td style="padding: 8px 12px;">Integer</td>
                  <td style="padding: 8px 12px;">Opsional</td>
                  <td style="padding: 8px 12px;"><code>3</code></td>
                  <td style="padding: 8px 12px;">Jumlah kelompok/klaster yang diinginkan (min 2, max 5).</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Accordion Endpoint 3 -->
      <div class="endpoint-card mb-20" style="border: 1px solid #e2e8f0; border-radius: 14px; overflow: hidden;">
        <div style="background: #f8fafc; padding: 16px 20px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
          <div style="display: flex; align-items: center; gap: 12px;">
            <span style="background: #059669; color: white; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 800;">GET</span>
            <code style="font-size: 14px; font-weight: 700; color: #0f172a;">/api/v1/stats.php</code>
          </div>
          <span style="font-size: 12.5px; color: var(--muted); font-weight: 600;">Ringkasan Statistik Pariwisata</span>
        </div>
        <div style="padding: 20px;">
          <p style="font-size: 13.5px; color: var(--ink); margin-bottom: 8px; line-height: 1.6;">
            Mengembalikan agregasi statistik pariwisata Kabupaten Magelang meliputi total objek wisata, total pengunjung, rata-rata rating/ulasan, destinasi terpopuler, sebaran per kategori, serta distribusi klastering.
          </p>
        </div>
      </div>

      <!-- Status Codes Reference -->
      <div style="margin-top: 24px; background: #fafafa; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px;">
        <h4 style="font-size: 14px; font-weight: 700; color: var(--ink); margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
          <i data-lucide="alert-circle" style="width: 16px; height: 16px; color: var(--emerald);"></i> Kode Respon HTTP (Standard Response Codes)
        </h4>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
          <div style="background: white; border: 1px solid #e2e8f0; padding: 10px 14px; border-radius: 8px;">
            <span style="color: #16a34a; font-weight: 800; font-size: 13px;">200 OK</span>
            <p style="font-size: 11.5px; color: var(--muted); margin: 2px 0 0;">Request berhasil dieksekusi.</p>
          </div>
          <div style="background: white; border: 1px solid #e2e8f0; padding: 10px 14px; border-radius: 8px;">
            <span style="color: #16a34a; font-weight: 800; font-size: 13px;">201 Created</span>
            <p style="font-size: 11.5px; color: var(--muted); margin: 2px 0 0;">Resource baru (e.g. API Key) berhasil dibuat.</p>
          </div>
          <div style="background: white; border: 1px solid #e2e8f0; padding: 10px 14px; border-radius: 8px;">
            <span style="color: #d97706; font-weight: 800; font-size: 13px;">400 Bad Request</span>
            <p style="font-size: 11.5px; color: var(--muted); margin: 2px 0 0;">Parameter request tidak valid atau kurang.</p>
          </div>
          <div style="background: white; border: 1px solid #e2e8f0; padding: 10px 14px; border-radius: 8px;">
            <span style="color: #dc2626; font-weight: 800; font-size: 13px;">401 Unauthorized</span>
            <p style="font-size: 11.5px; color: var(--muted); margin: 2px 0 0;">API Key tidak ada, tidak valid, atau dicabut.</p>
          </div>
          <div style="background: white; border: 1px solid #e2e8f0; padding: 10px 14px; border-radius: 8px;">
            <span style="color: #dc2626; font-weight: 800; font-size: 13px;">404 Not Found</span>
            <p style="font-size: 11.5px; color: var(--muted); margin: 2px 0 0;">Resource destinasi atau data tidak ditemukan.</p>
          </div>
        </div>
      </div>

    </div>
  </div>

</div>

<style>
  .snippet-tab {
    background: transparent;
    border: none;
    padding: 6px 12px;
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.15s;
  }
  .snippet-tab:hover {
    color: #0f172a;
    background: #e2e8f0;
  }
  .snippet-tab.active {
    background: #0f172a;
    color: #ffffff;
  }
  @keyframes modalPop {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
  }
</style>

<script>
  const API_BASE_URL = <?= json_encode($baseUrl) ?>;
  let activeSnippetType = 'curl';

  const endpointConfig = {
    destinasi_list: {
      url: '/api/v1/destinasi.php',
      params: [
        { name: 'kategori', label: 'Kategori Wisata', type: 'select', options: ['', 'Budaya', 'Alam', 'Desa Wisata', 'Religi', 'Taman'] },
        { name: 'search', label: 'Cari Nama Wisata', type: 'text', placeholder: 'Contoh: Borobudur' },
        { name: 'sort_by', label: 'Urutkan Berdasarkan', type: 'select', options: ['id', 'nama', 'rating', 'pengunjung', 'ulasan'] },
        { name: 'order', label: 'Arah Urutan', type: 'select', options: ['asc', 'desc'] },
        { name: 'limit', label: 'Limit Per Page', type: 'number', value: 10 }
      ]
    },
    destinasi_detail: {
      url: '/api/v1/destinasi.php',
      params: [
        { name: 'id', label: 'ID Destinasi Wisata', type: 'number', value: 1, required: true }
      ]
    },
    clustering: {
      url: '/api/v1/clustering.php',
      params: [
        { name: 'k', label: 'Jumlah Cluster (k)', type: 'select', options: [3, 2, 4, 5] }
      ]
    },
    stats: {
      url: '/api/v1/stats.php',
      params: []
    },
    keys: {
      url: '/api/v1/keys.php',
      params: []
    }
  };

  function updatePlaygroundFields() {
    const key = document.getElementById('endpointSelect').value;
    const config = endpointConfig[key];
    const container = document.getElementById('dynamicParams');
    container.innerHTML = '';

    if (!config || !config.params.length) {
      container.innerHTML = '<div style="font-size:12px; color:#64748b; font-style:italic; padding:6px 0;">Tidak ada parameter wajib untuk endpoint ini.</div>';
      updateUrlAndCode();
      return;
    }

    config.params.forEach(p => {
      const wrapper = document.createElement('div');
      wrapper.style.marginBottom = '12px';

      const label = document.createElement('label');
      label.style.display = 'block';
      label.style.fontSize = '12px';
      label.style.fontWeight = '700';
      label.style.color = 'var(--ink)';
      label.style.marginBottom = '4px';
      label.innerText = p.label;

      let input;
      if (p.type === 'select') {
        input = document.createElement('select');
        input.className = 'input param-input';
        input.style.width = '100%';
        input.style.padding = '8px 10px';
        input.style.borderRadius = '8px';
        input.style.fontSize = '12.5px';
        input.style.border = '1px solid #cbd5e1';
        input.style.background = 'white';

        p.options.forEach(opt => {
          const o = document.createElement('option');
          o.value = opt;
          o.innerText = opt === '' ? '-- Semua Kategori --' : opt;
          input.appendChild(o);
        });
      } else {
        input = document.createElement('input');
        input.type = p.type;
        input.className = 'input param-input';
        input.placeholder = p.placeholder || '';
        input.value = p.value !== undefined ? p.value : '';
        input.style.width = '100%';
        input.style.padding = '8px 10px';
        input.style.borderRadius = '8px';
        input.style.fontSize = '12.5px';
        input.style.border = '1px solid #cbd5e1';
      }

      input.setAttribute('data-param', p.name);
      input.addEventListener('input', updateUrlAndCode);
      input.addEventListener('change', updateUrlAndCode);

      wrapper.appendChild(label);
      wrapper.appendChild(input);
      container.appendChild(wrapper);
    });

    updateUrlAndCode();
  }

  function getFullRequestUrl() {
    const key = document.getElementById('endpointSelect').value;
    const config = endpointConfig[key];
    if (!config) return API_BASE_URL + '/api/v1/destinasi.php';

    const params = new URLSearchParams();
    const inputs = document.querySelectorAll('#dynamicParams .param-input');
    inputs.forEach(inp => {
      const pName = inp.getAttribute('data-param');
      const val = inp.value.trim();
      if (val !== '') {
        params.append(pName, val);
      }
    });

    const apiKey = document.getElementById('apiKeySelect').value;

    let fullPath = API_BASE_URL + config.url;
    const queryString = params.toString();
    if (queryString) {
      fullPath += '?' + queryString;
    }
    return { fullUrl: fullPath, apiKey };
  }

  function updateUrlAndCode() {
    const { fullUrl, apiKey } = getFullRequestUrl();
    document.getElementById('urlPreview').innerText = fullUrl;
    renderSnippetCode(fullUrl, apiKey);
  }

  document.getElementById('apiKeySelect').addEventListener('change', updateUrlAndCode);

  function switchSnippetTab(type, btn) {
    activeSnippetType = type;
    document.querySelectorAll('.snippet-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    updateUrlAndCode();
  }

  function renderSnippetCode(url, apiKey) {
    const snippetEl = document.getElementById('snippetCode');
    const headerStr = apiKey ? ` -H "X-API-Key: ${apiKey}"` : '';

    let code = '';
    if (activeSnippetType === 'curl') {
      code = `curl -X GET "${url}"${headerStr} \\\n  -H "Accept: application/json"`;
    } else if (activeSnippetType === 'js') {
      const headers = apiKey ? `, {\n    headers: { 'X-API-Key': '${apiKey}' }\n  }` : '';
      code = `fetch('${url}'${headers})\n  .then(res => res.json())\n  .then(data => console.log(data))\n  .catch(err => console.error(err));`;
    } else if (activeSnippetType === 'php') {
      const headerPhp = apiKey ? `\ncurl_setopt($ch, CURLOPT_HTTPHEADER, ['X-API-Key: ${apiKey}']);` : '';
      code = `<?php\n$ch = curl_init('${url}');\ncurl_setopt($ch, CURLOPT_RETURNTRANSFER, true);${headerPhp}\n$response = curl_exec($ch);\n$data = json_decode($response, true);\nprint_r($data);`;
    } else if (activeSnippetType === 'python') {
      const headersPy = apiKey ? `, headers={'X-API-Key': '${apiKey}'}` : '';
      code = `import requests\n\nresponse = requests.get('${url}'${headersPy})\ndata = response.json()\nprint(data)`;
    }

    snippetEl.innerText = code;
  }

  async function executeApiTest() {
    const { fullUrl, apiKey } = getFullRequestUrl();
    const btn = document.getElementById('btnRunTest');
    const outputEl = document.getElementById('jsonOutput');
    const statusEl = document.getElementById('responseStatus');
    const timeEl = document.getElementById('responseTime');

    btn.disabled = true;
    btn.innerHTML = '<i data-lucide="loader-2" style="width:16px;height:16px;animation:spin 0.8s linear infinite;"></i> Running...';
    lucide.createIcons();

    outputEl.innerText = 'Mengirim HTTP GET Request ke server...';
    statusEl.innerText = 'Pending';
    statusEl.style.background = '#e2e8f0';
    statusEl.style.color = '#475569';
    timeEl.innerText = '';

    const startTime = performance.now();

    try {
      const headers = { 'Accept': 'application/json' };
      if (apiKey) {
        headers['X-API-Key'] = apiKey;
      }

      const res = await fetch(fullUrl, { method: 'GET', headers });
      const endTime = performance.now();
      const duration = Math.round(endTime - startTime);

      timeEl.innerText = duration + ' ms';
      statusEl.innerText = `${res.status} ${res.statusText || (res.ok ? 'OK' : 'Error')}`;
      if (res.ok) {
        statusEl.style.background = '#dcfce7';
        statusEl.style.color = '#15803d';
      } else {
        statusEl.style.background = '#fee2e2';
        statusEl.style.color = '#b91c1c';
      }

      const json = await res.json();
      outputEl.innerText = JSON.stringify(json, null, 2);
    } catch (err) {
      const endTime = performance.now();
      timeEl.innerText = Math.round(endTime - startTime) + ' ms';
      statusEl.innerText = 'Fetch Failed';
      statusEl.style.background = '#fee2e2';
      statusEl.style.color = '#b91c1c';
      outputEl.innerText = 'Error Executing Request:\n' + err.message;
    } finally {
      btn.disabled = false;
      btn.innerHTML = '<i data-lucide="play" style="width:16px;height:16px;"></i> Send Request';
      lucide.createIcons();
    }
  }

  function showCreateKeyModal() {
    document.getElementById('modalCreateKey').style.display = 'flex';
  }

  function hideCreateKeyModal() {
    document.getElementById('modalCreateKey').style.display = 'none';
  }

  async function handleCreateKey(e) {
    e.preventDefault();
    const name = document.getElementById('appNameInput').value.trim();
    if (!name) return;

    const btn = document.getElementById('btnSubmitKey');
    btn.disabled = true;

    try {
      const res = await fetch(API_BASE_URL + '/api/v1/keys.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name })
      });

      const data = await res.json();
      if (res.ok && data.status === 'success') {
        alert('API Key baru berhasil dibuat!\nKey: ' + data.data.api_key);
        location.reload();
      } else {
        alert('Gagal membuat API Key: ' + (data.message || 'Error server'));
      }
    } catch (err) {
      alert('Terjadi kesalahan koneksi: ' + err.message);
    } finally {
      btn.disabled = false;
    }
  }

  async function revokeKey(id) {
    if (!confirm('Apakah Anda yakin ingin mencabut (revoke) API Key ini? Aplikasi yang menggunakan token ini tidak akan dapat mengakses API lagi.')) {
      return;
    }

    try {
      const res = await fetch(API_BASE_URL + '/api/v1/keys.php?id=' + id, {
        method: 'DELETE'
      });
      const data = await res.json();
      if (res.ok && data.status === 'success') {
        alert('API Key berhasil dicabut.');
        location.reload();
      } else {
        alert('Gagal mencabut API Key: ' + (data.message || 'Error server'));
      }
    } catch (err) {
      alert('Terjadi kesalahan koneksi: ' + err.message);
    }
  }

  function copyToClipboard(text, btnEl) {
    navigator.clipboard.writeText(text).then(() => {
      const origHtml = btnEl.innerHTML;
      btnEl.innerHTML = '<i data-lucide="check" style="width:13px;height:13px;"></i> Copied!';
      lucide.createIcons();
      setTimeout(() => {
        btnEl.innerHTML = origHtml;
        lucide.createIcons();
      }, 1500);
    }).catch(err => {
      alert('Gagal menyalin text');
    });
  }

  function copyActiveSnippet(btnEl) {
    const code = document.getElementById('snippetCode').innerText;
    copyToClipboard(code, btnEl);
  }

  document.addEventListener('DOMContentLoaded', () => {
    updatePlaygroundFields();
    if (window.lucide) {
      lucide.createIcons();
    }
  });
</script>

<style>
  @keyframes spin {
    to { transform: rotate(360deg); }
  }
</style>

<?php require_once '../includes/footer.php'; ?>
