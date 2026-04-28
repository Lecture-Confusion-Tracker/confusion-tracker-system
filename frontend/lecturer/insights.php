<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Lecture Insights • Lecture Confusion Tracker</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body>
<?php
$page = 'insights';
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../../backend/auth/includes/db.php';

// Top confusing topics overall
$topTopics = $pdo->query("
    SELECT c.topic, c.tag, co.name AS course_name,
           COUNT(c.id) AS ping_count,
           COUNT(v.id) AS vote_count
    FROM confusions c
    JOIN courses co ON c.course_id = co.id
    LEFT JOIN votes v ON v.confusion_id = c.id
    GROUP BY c.topic, c.tag, co.name
    ORDER BY ping_count DESC
    LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

// Auto summary: most repeated keywords
$allTopics = $pdo->query("SELECT topic FROM confusions")->fetchAll(PDO::FETCH_COLUMN);
$wordFreq  = [];
foreach ($allTopics as $t) {
    foreach (explode(' ', strtolower(preg_replace('/[^a-zA-Z0-9 ]/', '', $t))) as $word) {
        if (strlen($word) > 3) $wordFreq[$word] = ($wordFreq[$word] ?? 0) + 1;
    }
}
arsort($wordFreq);
$topKeywords = array_slice(array_keys($wordFreq), 0, 5);

// Per-course breakdown
$courseBreakdown = $pdo->query("
    SELECT co.name, COUNT(c.id) AS total
    FROM confusions c
    JOIN courses co ON c.course_id = co.id
    GROUP BY co.name ORDER BY total DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<h1 class="portal-page-title">Lecture Insights</h1>
<p class="portal-subtitle">Patterns and trends across all your courses</p>

<?php if (!empty($topKeywords)): ?>
<div class="panel" style="margin-bottom:20px;">
  <div class="panel-head">
    <div class="panel-title">🧠 Auto Summary</div>
    <span class="chip">Keyword Analysis</span>
  </div>
  <div style="padding:16px 20px;">
    <p style="color:var(--text-muted);margin-bottom:12px;font-size:0.9375rem;">
      Most confusion around:
    </p>
    <div style="display:flex;flex-wrap:wrap;gap:8px;">
      <?php foreach ($topKeywords as $kw): ?>
        <span class="badge" style="font-size:0.875rem;padding:6px 14px;"><?= htmlspecialchars(ucfirst($kw)) ?></span>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
  <!-- Top topics -->
  <div class="panel">
    <div class="panel-head">
      <div class="panel-title">Top Confusing Topics</div>
      <span class="chip">Ranked</span>
    </div>
    <?php if (empty($topTopics)): ?>
      <p style="padding:20px;color:var(--text-light);font-size:0.875rem;">No data yet.</p>
    <?php else: ?>
    <ul class="trending-list">
      <?php foreach ($topTopics as $i => $t): ?>
      <li class="trend-item">
        <div class="trend-ico" style="font-size:0.875rem;font-weight:800;"><?= $i+1 ?></div>
        <div style="flex:1;">
          <div class="trend-title"><?= htmlspecialchars($t['topic']) ?></div>
          <div class="trend-meta">
            <?php if ($t['tag']): ?>
              <span class="trend-pill concept"><?= htmlspecialchars($t['tag']) ?></span>
            <?php endif; ?>
            <span><?= $t['ping_count'] ?> pings · <?= $t['vote_count'] ?> votes</span>
            <span style="color:var(--text-light);"><?= htmlspecialchars($t['course_name']) ?></span>
          </div>
        </div>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
  </div>

  <!-- Per-course -->
  <div class="panel">
    <div class="panel-head">
      <div class="panel-title">Confusion by Course</div>
      <span class="chip">All Time</span>
    </div>
    <?php if (empty($courseBreakdown)): ?>
      <p style="padding:20px;color:var(--text-light);font-size:0.875rem;">No data yet.</p>
    <?php else: ?>
    <?php $maxC = max(array_column($courseBreakdown,'total') ?: [1]); ?>
    <div style="padding:16px 20px;display:flex;flex-direction:column;gap:14px;">
      <?php foreach ($courseBreakdown as $c): ?>
      <div>
        <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
          <span style="font-size:0.875rem;font-weight:600;color:var(--text);"><?= htmlspecialchars($c['name']) ?></span>
          <span style="font-size:0.875rem;color:var(--text-muted);"><?= $c['total'] ?> pings</span>
        </div>
        <div style="height:8px;background:var(--border-purple);border-radius:4px;overflow:hidden;">
          <div style="height:100%;width:<?= round(($c['total']/$maxC)*100) ?>%;background:var(--gradient);border-radius:4px;"></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</div>

</div></main></div>
<script src="../assets/js/main.js"></script></body>
</html>

