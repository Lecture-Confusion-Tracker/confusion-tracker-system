<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Lecturer Dashboard • Lecture Confusion Tracker</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/css/style.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>
<body>
<?php
$page = 'dashboard';
require_once __DIR__ . '/layout.php';

$totalConfusions = (int)$pdo->query("SELECT COUNT(*) FROM confusions")->fetchColumn();

$hardestRow   = $pdo->query("SELECT topic, COUNT(*) AS cnt FROM confusions GROUP BY topic ORDER BY cnt DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$hardestTopic = $hardestRow ? htmlspecialchars($hardestRow['topic']) : 'N/A';
$hardestCount = $hardestRow ? (int)$hardestRow['cnt'] : 0;

$activeStudents = (int)$pdo->query("SELECT COUNT(DISTINCT user_id) FROM confusions")->fetchColumn();

$chartRows   = $pdo->query("SELECT topic, COUNT(*) AS cnt FROM confusions GROUP BY topic ORDER BY cnt DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
$chartLabels = array_column($chartRows, 'topic');
$chartData   = array_column($chartRows, 'cnt');

$trending = $pdo->query("
    SELECT c.id, c.topic, c.tag, COUNT(v.id) AS vote_count
    FROM confusions c
    LEFT JOIN votes v ON v.confusion_id = c.id
    GROUP BY c.id ORDER BY vote_count DESC, c.created_at DESC LIMIT 3
")->fetchAll(PDO::FETCH_ASSOC);

try {
    $heatmap = $pdo->query("SELECT HOUR(created_at) AS hr, COUNT(*) AS cnt FROM confusions GROUP BY hr ORDER BY hr")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $heatmap = $pdo->query("SELECT CAST(strftime('%H', created_at) AS INTEGER) AS hr, COUNT(*) AS cnt FROM confusions GROUP BY hr ORDER BY hr")->fetchAll(PDO::FETCH_ASSOC);
}
$heatMax = max(array_column($heatmap, 'cnt') ?: [1]);

$kpiIconA = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="M7 16V9"/><path d="M12 16V5"/><path d="M17 16v-7"/></svg>';
$kpiIconB = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l9 16H3l9-16z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>';
$kpiIconC = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><path d="M20 8v6"/><path d="M23 11h-6"/></svg>';
?>

<div style="display:flex;align-items:flex-end;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:24px;">
  <div>
    <h1 class="portal-page-title">Lecture Hall Overview</h1>
    <p class="portal-subtitle">Confusion analytics across all your courses</p>
  </div>
  <?php if ($totalConfusions > 0): ?>
  <div class="portal-live"><span class="portal-live-dot"></span><?= $totalConfusions ?> total pings</div>
  <?php endif; ?>
</div>

<section class="portal-kpis">
  <article class="kpi-card">
    <div class="kpi-meta">
      <div class="kpi-label">Total Confusions</div>
      <div class="kpi-value"><?= $totalConfusions ?: '0' ?></div>
      <div class="kpi-trend">All time</div>
    </div>
    <div class="kpi-icon"><?= $kpiIconA ?></div>
  </article>
  <article class="kpi-card">
    <div class="kpi-meta">
      <div class="kpi-label">Most Difficult Topic</div>
      <div class="kpi-small"><?= $hardestTopic ?></div>
      <div class="kpi-tag"><?= $hardestCount ?> pings</div>
    </div>
    <div class="kpi-icon"><?= $kpiIconB ?></div>
  </article>
  <article class="kpi-card">
    <div class="kpi-meta">
      <div class="kpi-label">Active Students</div>
      <div class="kpi-value"><?= $activeStudents ?: '0' ?></div>
      <div class="kpi-tag">Unique submitters</div>
    </div>
    <div class="kpi-icon"><?= $kpiIconC ?></div>
  </article>
</section>

<section class="portal-grid">
  <div class="panel">
    <div class="panel-head">
      <div><div class="panel-title">Confusion Breakdown</div><div class="panel-sub">Top topics by pings</div></div>
      <span class="chip">All Courses</span>
    </div>
    <div class="chart-box"><canvas id="breakdownChart"></canvas></div>
    <?php if (empty($chartRows)): ?>
      <p style="text-align:center;color:var(--text-light);padding:24px 0;font-size:0.875rem;">No data yet — students need to submit first.</p>
    <?php endif; ?>
  </div>

  <aside class="panel">
    <div class="panel-head">
      <div><div class="panel-title">Trending Topics</div><div class="panel-sub">Most upvoted</div></div>
      <span class="chip">By Votes</span>
    </div>
    <?php if (empty($trending)): ?>
      <p style="padding:24px;color:var(--text-light);font-size:0.875rem;">No data yet.</p>
    <?php else: ?>
    <ul class="trending-list">
      <?php $pills=['high','concept','study']; $icons=['?','Σ','⌁'];
      foreach ($trending as $i => $t): ?>
      <li class="trend-item">
        <div class="trend-ico"><?= $icons[$i%3] ?></div>
        <div>
          <div class="trend-title"><?= htmlspecialchars($t['topic']) ?></div>
          <div class="trend-meta">
            <?php if ($t['tag']): ?><span class="trend-pill <?= $pills[$i%3] ?>"><?= htmlspecialchars($t['tag']) ?></span><?php endif; ?>
            <span><?= (int)$t['vote_count'] ?> votes</span>
          </div>
        </div>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
    <a class="panel-foot-link" href="analytics.php">View Full Analytics →</a>
  </aside>
</section>

<section class="heatmap">
  <div class="heatmap-title">Confusion Activity Heatmap</div>
  <div class="heatmap-sub">Submissions by hour of day</div>
  <?php if (!empty($heatmap)): ?>
  <div class="heatmap-track" style="grid-template-columns:repeat(<?= count($heatmap) ?>,1fr);">
    <?php foreach ($heatmap as $h):
      $p = max(1, min(5, $heatMax > 0 ? round(($h['cnt']/$heatMax)*5) : 1));
    ?><div class="heatmap-seg p<?= $p ?>" title="<?= $h['hr'] ?>:00 — <?= $h['cnt'] ?> pings"></div>
    <?php endforeach; ?>
  </div>
  <div class="heatmap-axis" style="display:flex;justify-content:space-between;">
    <?php foreach ($heatmap as $h): ?><span><?= str_pad($h['hr'],2,'0',STR_PAD_LEFT) ?>:00</span><?php endforeach; ?>
  </div>
  <?php else: ?>
    <p style="color:var(--text-light);font-size:0.875rem;margin-top:12px;">No activity yet.</p>
  <?php endif; ?>
</section>

</div></main></div>

<script src="../assets/js/main.js"></script>
<script>
var labels = <?= json_encode($chartLabels) ?>;
var data   = <?= json_encode(array_map('intval', $chartData)) ?>;
var ctx = document.getElementById('breakdownChart');
if (ctx && window.Chart && labels.length) {
  new Chart(ctx, {
    type: 'bar',
    data: { labels: labels, datasets: [{ label: 'Confusions', data: data, borderRadius: 10,
      backgroundColor: ['#ddd6fe','#c4b5fd','#a78bfa','#7c3aed','#5b21b6'] }] },
    options: { responsive: true, maintainAspectRatio: false,
      plugins: { legend: { display: false } },
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
