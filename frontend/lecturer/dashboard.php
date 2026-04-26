<?php
$page = 'dashboard';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Lecturer Dashboard • Lecture Confusion Tracker</title>
  <link rel="stylesheet" href="../assets/css/style.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>
<body>
<?php require_once __DIR__ . '/layout.php'; ?>

<?php
$kpiIconA = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="M7 16V9"/><path d="M12 16V5"/><path d="M17 16v-7"/></svg>';
$kpiIconB = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l9 16H3l9-16z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>';
$kpiIconC = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><path d="M20 8v6"/><path d="M23 11h-6"/></svg>';
?>

<div style="display:flex;align-items:flex-end;justify-content:space-between;gap:12px;flex-wrap:wrap;">
  <div>
    <h1 class="portal-page-title">Lecture Hall Overview</h1>
    <p class="portal-subtitle">Real-time engagement metrics for your current course</p>
  </div>
  <div class="portal-live" aria-label="Live session">
    <span class="portal-live-dot" aria-hidden="true"></span>
    Live Session
  </div>
</div>

<section class="portal-kpis">
  <article class="kpi-card">
    <div class="kpi-meta">
      <div class="kpi-label">Total Confusions</div>
      <div class="kpi-value">—</div>
      <div class="kpi-trend">Live</div>
    </div>
    <div class="kpi-icon" aria-hidden="true"><?= $kpiIconA ?></div>
  </article>

  <article class="kpi-card">
    <div class="kpi-meta">
      <div class="kpi-label">Most Difficult Topic</div>
      <div class="kpi-small">—</div>
      <div class="kpi-tag">Trending</div>
    </div>
    <div class="kpi-icon" aria-hidden="true"><?= $kpiIconB ?></div>
  </article>

  <article class="kpi-card">
    <div class="kpi-meta">
      <div class="kpi-label">Active Students</div>
      <div class="kpi-value">—</div>
      <div class="kpi-tag">—</div>
    </div>
    <div class="kpi-icon" aria-hidden="true"><?= $kpiIconC ?></div>
  </article>
</section>

<section class="portal-grid">
  <div class="panel">
    <div class="panel-head">
      <div>
        <div class="panel-title">Confusion Breakdown</div>
        <div class="panel-sub">Frequency of pings across active modules</div>
      </div>
      <span class="chip">Current Session</span>
    </div>

    <div class="chart-box">
      <canvas id="breakdownChart" aria-label="Confusion Breakdown Chart"></canvas>
    </div>
  </div>

  <aside class="panel">
    <div class="panel-head">
      <div>
        <div class="panel-title">Trending Items</div>
        <div class="panel-sub">Live pings needing attention</div>
      </div>
      <span class="chip">Live Session</span>
    </div>

    <ul class="trending-list">
      <li class="trend-item">
        <div class="trend-ico">?</div>
        <div>
          <div class="trend-title">Top confusion item will appear here</div>
          <div class="trend-meta">
            <span class="trend-pill high">High Priority</span>
            <span>Live</span>
          </div>
        </div>
      </li>
      <li class="trend-item">
        <div class="trend-ico">Σ</div>
        <div>
          <div class="trend-title">Trending confusion item will appear here</div>
          <div class="trend-meta">
            <span class="trend-pill concept">Conceptual</span>
            <span>Live</span>
          </div>
        </div>
      </li>
      <li class="trend-item">
        <div class="trend-ico">⌁</div>
        <div>
          <div class="trend-title">Another trending item will appear here</div>
          <div class="trend-meta">
            <span class="trend-pill study">Study Guide</span>
            <span>Live</span>
          </div>
        </div>
      </li>
    </ul>

    <a class="panel-foot-link" href="analytics.php">View All Active Inquiries</a>
  </aside>
</section>

<section class="heatmap">
  <div class="heatmap-title">Lecture Timeline Heatmap</div>
  <div class="heatmap-sub">Moment-by-moment confusion density across the lecture</div>
  <div class="heatmap-track" aria-label="Timeline heatmap">
    <div class="heatmap-seg p1"></div>
    <div class="heatmap-seg p2"></div>
    <div class="heatmap-seg p3"></div>
    <div class="heatmap-seg p4"></div>
    <div class="heatmap-seg p5"></div>
    <div class="heatmap-seg p6"></div>
  </div>
  <div class="heatmap-axis">
    <span>00:00</span>
    <span>15:00</span>
    <span>30:00 (Break)</span>
    <span>45:00</span>
    <span>60:00 (End)</span>
  </div>
</section>

</div><!-- portal-page -->
</main>
</div><!-- portal -->

<button class="portal-fab" type="button" aria-label="New confusion ping" onclick="return false;">+</button>

<script>
  const ctx = document.getElementById('breakdownChart');
  if (ctx && window.Chart) {
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Pointer Logic', 'Recursion', 'Memory Mgmt', 'BST Rotations', 'Linked Lists'],
        datasets: [{
          label: 'Confusions',
          data: [18, 34, 22, 41, 27],
          borderRadius: 10,
          backgroundColor: ['#ddd6fe', '#c4b5fd', '#a78bfa', '#7c3aed', '#5b21b6'],
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false }, tooltip: { enabled: true } },
        scales: {
          x: { grid: { display: false }, ticks: { color: '#6b7280', font: { weight: '700' } } },
          y: { grid: { color: 'rgba(91,33,182,0.08)' }, ticks: { color: '#6b7280', font: { weight: '700' } } }
        }
      }
    });
  }
</script>
</body>
</html>