<?php
$page = 'analytics';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Analytics • Lecture Confusion Tracker</title>
  <link rel="stylesheet" href="../assets/css/style.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>
<body>
<?php require_once __DIR__ . '/layout.php'; ?>

<a class="back-link" href="dashboard.php">← Back to Dashboard</a>

<div class="analytics-head">
  <div>
    <h1 class="portal-page-title" style="margin-top:0;">Lecture Analytics</h1>
    <p class="portal-subtitle" style="margin-bottom:0;">Session summary • Participants: —</p>
  </div>
  <div class="analytics-actions">
    <button class="btn-portal" type="button" onclick="return false;">Share Report</button>
    <button class="btn-portal primary" type="button" onclick="return false;">Export Data</button>
  </div>
</div>

<section class="analytics-grid">
  <div class="panel">
    <div class="panel-head">
      <div>
        <div class="panel-title">Confusion Timeline</div>
      </div>
      <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <span class="chip">Real-time</span>
        <span class="chip">Normalized</span>
      </div>
    </div>

    <div class="chart-box" style="height:260px;">
      <canvas id="timelineChart" aria-label="Confusion timeline chart"></canvas>
    </div>

    <div style="margin-top:10px;">
      <div class="panel-sub" style="margin:0 0 8px;">Lecture Heatmap Intensity</div>
      <div class="heatmap-track" style="height:18px;border-radius:999px;grid-template-columns: 1fr 0.7fr 0.9fr 0.8fr 1fr 0.7fr;">
        <div class="heatmap-seg p1"></div>
        <div class="heatmap-seg p2"></div>
        <div class="heatmap-seg p3"></div>
        <div class="heatmap-seg p4"></div>
        <div class="heatmap-seg p3"></div>
        <div class="heatmap-seg p1"></div>
      </div>
    </div>
  </div>

  <aside style="display:grid; gap:14px;">
    <div class="stat-card">
      <div class="stat-label">Peak Confusion</div>
      <div class="stat-big">—</div>
      <div class="stat-note">Recorded during the most confusing segment.</div>
      <div class="stat-mini">
        <div>Action recommended: review required</div>
      </div>
    </div>

    <div class="engage-card">
      <div class="panel-title">Engagement Score</div>
      <div class="radial" style="--p: 0;">
        <div class="radial-inner">
          <div>
            <div class="radial-num">—</div>
            <div class="radial-sub">—</div>
          </div>
        </div>
      </div>
      <div class="engage-metrics">
        <div class="metric-row"><span>Participation</span><span>—</span></div>
        <div class="metric-row"><span>Query Rate</span><span>—</span></div>
      </div>
    </div>
  </aside>
</section>

<section class="panel" style="margin-top:14px;">
  <div class="panel-head">
    <div>
      <div class="panel-title">Topic Breakdown</div>
      <div class="panel-sub">Modules with their confusion intensity</div>
    </div>
    <span class="chip">Session Summary</span>
  </div>

  <table class="table" aria-label="Topic breakdown table">
    <thead>
      <tr>
        <th style="width:40%;">Topic</th>
        <th>Duration</th>
        <th>Confusion Rating</th>
        <th style="text-align:right;">Status</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><strong>Topic will appear here</strong><div class="muted">Segment</div></td>
        <td class="muted">—</td>
        <td>
          <div class="heatmap-track" style="height:10px;border-radius:999px;grid-template-columns: 1fr 1fr 1fr;">
            <div class="heatmap-seg p1"></div>
            <div class="heatmap-seg p2"></div>
            <div class="heatmap-seg p1"></div>
          </div>
          <div class="muted" style="margin-top:6px;">—</div>
        </td>
        <td style="text-align:right;"><span class="badge-status clear">—</span></td>
      </tr>
    </tbody>
  </table>
</section>

</div><!-- portal-page -->
</main>
</div><!-- portal -->

<button class="portal-fab" type="button" aria-label="New confusion ping" onclick="return false;">+</button>

<script>
  const el = document.getElementById('timelineChart');
  if (el && window.Chart) {
    new Chart(el, {
      type: 'bar',
      data: {
        labels: ['00:00','10:00','20:00','30:00','40:00','50:00','60:00'],
        datasets: [{
          data: [0, 0, 0, 0, 0, 0, 0],
          borderRadius: 10,
          backgroundColor: ['#ede9fe','#ddd6fe','#c4b5fd','#7c3aed','#a78bfa','#c4b5fd','#ede9fe']
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          x: { grid: { display: false }, ticks: { color: '#6b7280', font: { weight: '800' } } },
          y: { grid: { color: 'rgba(91,33,182,0.08)' }, ticks: { color: '#6b7280', font: { weight: '800' } } }
        }
      }
    });
  }
</script>
</body>
</html>