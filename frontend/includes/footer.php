<footer class="footer">
  <div class="footer-inner">
    <div class="footer-logo">
      <div class="logo-icon" style="width:28px;height:28px;font-size:0.875rem;background:var(--gradient);border-radius:6px;display:flex;align-items:center;justify-content:center;color:#fff;">📊</div>
      <span>Lecture Confusion Tracker</span>
    </div>

    <ul class="footer-links">
      <?php
      // Works from any depth: /, /student/, /lecturer/
      $inSub = strpos($_SERVER['PHP_SELF'], '/student/') !== false
            || strpos($_SERVER['PHP_SELF'], '/lecturer/') !== false;
      $fp = $inSub ? '../' : '';
      ?>
      <li><a href="<?= $fp ?>support.php">Support</a></li>
      <li><a href="<?= $fp ?>privacy.php">Privacy</a></li>
      <li><a href="<?= $fp ?>terms.php">Terms</a></li>
    </ul>
  </div>
  <p class="footer-copy">
    &copy; 2026 Lecture Confusion Tracker. All rights reserved.
  </p>
</footer>
