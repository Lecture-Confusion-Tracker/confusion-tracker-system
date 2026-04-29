<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Analytics - Lecture Confusion Tracker</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/css/style.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>
<body>
<?php
$page = 'analytics';
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../../backend/auth/includes/db.php';

// Stats
$totalParticipants = (int)$pdo->query("SELECT COUNT(DISTINCT user_id) FROM confusions")->fetchColumn();
$totalPings        = (int)$pdo->query("SELECT COUNT(*) FROM confusions")->fetchColumn();
$totalVotes        = (int)$pdo->query("SELECT COUNT(*) FROM votes")->fetchColumn();

// Peak confusion topic
$peakRow = $pdo->query("
    SELECT topic, COUNT(*) AS cnt FROM confusions
    GROUP BY topic ORDER BY cnt DESC LIMIT 1
")->fetch(PDO::FETCH_ASSOC);
$peakTopic = $peakRow ? htmlspecialchars($peakRow['topic']) : '-';
$peakCount = $peakRow ? (int)$peakRow['cnt'] : 0;

// Engagement score: votes / pings ratio * 100 (capped at 100)
$engagementScore = $totalPings > 0 ? min(100, round(($totalVotes / $totalPings) * 100)) : 0;

// Participation rate: students who submitted vs total students
$totalStudents = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='student'")->fetchColumn();
$participationRate = $totalStudents > 0 ? round(($totalParticipants / $totalStudents) * 100) : 0;

// Query rate: pings per student
$queryRate = $totalParticipants > 0 ? round($totalPings / $totalParticipants, 1) : 0;

// Timeline: pings per day (last 7 days)
try {
    $timeline = $pdo->query("
        SELECT DATE(created_at) AS day, COUNT(*) AS cnt
        FROM confusions
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        GROUP BY day ORDER BY day
    ")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // SQLite fallback
    $timeline = $pdo->query("
        SELECT DATE(created_at) AS day, COUNT(*) AS cnt
        FROM confusions
        WHERE created_at >= date('now', '-7 days')
        GROUP BY day ORDER BY day
    ")->fetchAll(PDO::FETCH_ASSOC);
}
$timelineLabels = array_map(function($r){ return date('M j', strtotime($r['day'])); }, $timeline);
$timelineData   = array_column($timeline, 'cnt');

// Topic breakdown table
$topicBreakdown = $pdo->query("
    SELECT c.topic, co.name AS course_name,
           COUNT(c.id) AS confusion_count,
           COUNT(v.id) AS vote_count
    FROM confusions c
    JOIN courses co ON c.course_id = co.id
    LEFT JOIN votes v ON v.confusion_id = c.id
    GROUP BY c.topic, co.name
    ORDER BY confusion_count DESC
    LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

$maxConfusions = max(array_column($topicBreakdown, 'confusion_count') ?: [1]);
?>

<a class="back-link" href="dashboard.php">
  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
  Back to Dashboard
</a>

<div class="analytics-head">
  <div>
    <h1 class="portal-page-title" style="margin-top:0;">Lecture Analytics</h1>
    <p class="portal-subtitle" style="margin-bottom:0;">
      All sessions &bull; <?= $totalParticipants ?> participant<?= $totalParticipants !== 1 ? 's' : '' ?>
    </p>
  </div>
  <div class="analytics-actions">
    <button class="btn-portal" type="button" onclick="window.print()">Print Report</button>
    <button class="btn-portal primary" type="button" onclick="exportCSV()">Export CSV</button>
  </div>
</div>

<section class="analytics-grid">
  <div class="panel">
    <div class="panel-head">
      <div><div class="panel-title">Confusion Timeline</div></div>
      <div style="display:flex;gap:8px;">
        <span class="chip">Last 7 Days</span>
      </div>
    </div>
    <div class="chart-box" style="height:260px;">
      <canvas id="timelineChart"></canvas>
    </div>
    <?php if (empty($timeline)): ?>
      <p style="text-align:center;color:var(--text-light);padding:16px;font-size:0.875rem;">No data in the last 7 days.</p>
    <?php endif; ?>
    <div style="margin-top:10px;">
      <div class="panel-sub" style="margin:0 0 8px;">Confusion Intensity</div>
      <div class="heatmap-track" style="height:18px;border-radius:999px;">
        <?php
        $segs = [1,2,3,4,5,3,2];
        foreach ($segs as $s) echo '<div class="heatmap-seg p'.$s.'"></div>';
        ?>
      </div>
    </div>
  </div>

  <aside style="display:grid;gap:14px;">
    <div class="stat-card">
      <div class="stat-label">Peak Confusion Topic</div>
      <div class="stat-big" style="font-size:1.1rem;"><?= $peakTopic ?></div>
      <div class="stat-note"><?= $peakCount ?> ping<?= $peakCount !== 1 ? 's' : '' ?> recorded on this topic.</div>
      <div class="stat-mini">
        <div><?= $peakCount > 5 ? 'Review recommended' : 'Within normal range' ?></div>
      </div>
    </div>

    <div class="engage-card">
      <div class="panel-title">Engagement Score</div>
      <div class="radial" style="--p:<?= $engagementScore ?>;">
        <div class="radial-inner">
          <div>
            <div class="radial-num"><?= $engagementScore ?>%</div>
            <div class="radial-sub"><?= $engagementScore >= 60 ? 'Good' : ($engagementScore >= 30 ? 'Fair' : 'Low') ?></div>
          </div>
        </div>
      </div>
      <div class="engage-metrics">
        <div class="metric-row"><span>Participation</span><span><?= $participationRate ?>%</span></div>
        <div class="metric-row"><span>Avg Pings/Student</span><span><?= $queryRate ?></span></div>
        <div class="metric-row"><span>Total Votes</span><span><?= $totalVotes ?></span></div>
      </div>
    </div>
  </aside>
</section>

<section class="panel" style="margin-top:14px;">
  <div class="panel-head">
    <div>
      <div class="panel-title">Topic Breakdown</div>
      <div class="panel-sub">All topics ranked by confusion count</div>
    </div>
    <span class="chip">All Time</span>
  </div>

  <?php if (empty($topicBreakdown)): ?>
    <p style="padding:24px;color:var(--text-light);font-size:0.875rem;">No topics submitted yet.</p>
  <?php else: ?>
  <table class="table">
    <thead>
      <tr>
        <th style="width:35%;">Topic</th>
        <th>Course</th>
        <th>Confusion Rating</th>
        <th style="text-align:right;">Status</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($topicBreakdown as $row):
        $pct = $maxConfusions > 0 ? round(($row['confusion_count'] / $maxConfusions) * 5) : 1;
        $pct = max(1, min(5, $pct));
        $status = $row['confusion_count'] >= 5 ? 'high' : ($row['confusion_count'] >= 2 ? 'medium' : 'clear');
        $statusLabel = $row['confusion_count'] >= 5 ? 'Review' : ($row['confusion_count'] >= 2 ? 'Monitor' : 'Clear');
      ?>
      <tr>
        <td>
          <strong><?= htmlspecialchars($row['topic']) ?></strong>
          <div class="muted"><?= (int)$row['confusion_count'] ?> ping<?= $row['confusion_count'] != 1 ? 's' : '' ?></div>
        </td>
        <td class="muted"><?= htmlspecialchars($row['course_name']) ?></td>
        <td>
          <div class="heatmap-track" style="height:10px;border-radius:999px;grid-template-columns:repeat(5,1fr);">
            <?php for ($i = 1; $i <= 5; $i++): ?>
              <div class="heatmap-seg <?= $i <= $pct ? 'p'.$pct : 'p1' ?>" style="<?= $i > $pct ? 'opacity:0.2' : '' ?>"></div>
            <?php endfor; ?>
          </div>
          <div class="muted" style="margin-top:4px;"><?= (int)$row['vote_count'] ?> vote<?= $row['vote_count'] != 1 ? 's' : '' ?></div>
        </td>
        <td style="text-align:right;">
          <span class="badge-status <?= $status ?>"><?= $statusLabel ?></span>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</section>

</div></main></div>

<script>
var tlLabels = <?= json_encode($timelineLabels) ?>;
var tlData   = <?= json_encode(array_map('intval', $timelineData)) ?>;
var el = document.getElementById('timelineChart');
if (el && window.Chart) {
  new Chart(el, {
    type: 'bar',
    data: {
      labels: tlLabels.length ? tlLabels : ['No data'],
      datasets: [{
        data: tlData.length ? tlData : [0],
        borderRadius: 10,
        backgroundColor: ['#ede9fe','#ddd6fe','#c4b5fd','#7c3aed','#a78bfa','#c4b5fd','#ede9fe']
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { grid: { display: false }, ticks: { color: '#6b7280', font: { weight: '800' } } },
        y: { grid: { color: 'rgba(91,33,182,0.08)' }, ticks: { color: '#6b7280', font: { weight: '800' } } }
      }
    }
  });
}

// CSV export
function exportCSV() {
  var rows = [['Topic','Course','Pings','Votes']];
  document.querySelectorAll('.table tbody tr').forEach(function(tr) {
    var cells = tr.querySelectorAll('td');
    if (cells.length >= 4) {
      rows.push([
        cells[0].querySelector('strong') ? cells[0].querySelector('strong').textContent.trim() : '',
        cells[1].textContent.trim(),
        cells[2].querySelector('.muted') ? cells[2].querySelector('.muted').textContent.trim() : '',
        cells[2].querySelectorAll('.muted')[1] ? cells[2].querySelectorAll('.muted')[1].textContent.trim() : ''
      ]);
    }
  });
  var csv = rows.map(function(r){ return r.map(function(c){ return '"'+c+'"'; }).join(','); }).join('\n');
  var a = document.createElement('a');
  a.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv);
  a.download = 'confusion-analytics.csv';
  a.click();
}
</script>
<script src="../assets/js/main.js"></script></body>
</html>

