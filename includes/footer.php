  </div><!-- end page-content -->

  <!-- Mobile Bottom Navigation (Native App Navigation Bar) -->
  <nav class="mobile-bottom-nav">
    <?php
    $mNavItems = [
      'dashboard'   => ['icon' => 'layout-dashboard', 'label' => 'Beranda',  'url' => appUrl('pages/dashboard.php')],
      'data_wisata' => ['icon' => 'database',         'label' => 'Wisata',   'url' => appUrl('pages/data_wisata.php')],
      'clustering'  => ['icon' => 'brain-circuit',    'label' => 'Cluster',  'url' => appUrl('pages/clustering.php')],
      'hasil'       => ['icon' => 'bar-chart-3',      'label' => 'Hasil',    'url' => appUrl('pages/hasil.php')],
      'peta'        => ['icon' => 'map',              'label' => 'Peta',     'url' => appUrl('pages/peta.php')],
    ];

    $u = getCurrentUser();
    if ($u && (($u['role'] ?? '') === 'admin')) {
      $mNavItems = [
        'dashboard' => ['icon' => 'shield-check', 'label' => 'Admin',     'url' => appUrl('admin/dashboard.php')],
        'destinasi' => ['icon' => 'settings-2',   'label' => 'Destinasi', 'url' => appUrl('admin/destinasi.php')],
        'users'     => ['icon' => 'users',        'label' => 'Users',     'url' => appUrl('admin/users.php')],
        'public'    => ['icon' => 'globe',        'label' => 'Publik',    'url' => appUrl('pages/dashboard.php')],
      ];
    }

    $currKey = basename($_SERVER['PHP_SELF'], '.php');
    foreach ($mNavItems as $key => $item):
      $active = ($currKey === $key) ? 'active' : '';
    ?>
      <a href="<?= htmlspecialchars($item['url']) ?>" class="mobile-nav-item <?= $active ?>">
        <i data-lucide="<?= $item['icon'] ?>"></i>
        <span><?= htmlspecialchars($item['label']) ?></span>
      </a>
    <?php endforeach; ?>
  </nav>

</div><!-- end main-content -->
</div><!-- end app-layout -->

<script>
  document.addEventListener('DOMContentLoaded', function() {
    if (window.lucide) {
      lucide.createIcons();
    }
  });
</script>
</body>
</html>

