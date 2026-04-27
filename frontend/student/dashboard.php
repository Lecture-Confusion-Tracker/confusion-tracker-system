<?php include '../includes/header.php'; ?>

<main class="container" style="padding:40px 0;">

  <h2 style="margin-bottom:20px;">Student Dashboard</h2>

  <!-- Top controls -->
  <div style="display:flex; justify-content:space-between; margin-bottom:20px; flex-wrap:wrap; gap:10px;">
    
    <select class="form-select" style="max-width:200px;">
      <option>Select Course</option>
      <option>Web Development</option>
      <option>Database Systems</option>
    </select>

    <div>
      <button class="btn btn-outline" onclick="filterPosts('recent')">Recent</button>
      <button class="btn btn-primary" onclick="filterPosts('votes')">Most Voted</button>
      <a href="add_confusion.php" class="btn btn-primary">+ Add Confusion</a>
    </div>
  </div>

  <!-- Confusion Cards -->
  <div id="confusion-list">

    <!-- Card -->
    <div class="feature-card confusion-card" data-votes="5" data-time="2">
      <h3>Recursion Concept</h3>
      <p>I don’t understand how recursion stops in base case.</p>

      <div style="display:flex; justify-content:space-between; margin-top:10px;">
        <span class="badge">Concept</span>
        <button class="btn btn-outline upvote-btn">👍 5</button>
      </div>
    </div>

    <div class="feature-card confusion-card" data-votes="10" data-time="1">
      <h3>Loops</h3>
      <p>Difference between while and do-while is confusing.</p>

      <div style="display:flex; justify-content:space-between; margin-top:10px;">
        <span class="badge">Logic</span>
        <button class="btn btn-outline upvote-btn">👍 10</button>
      </div>
    </div>

  </div>

</main>

<script>
// Upvote
document.querySelectorAll('.upvote-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    let count = parseInt(btn.innerText.replace('👍',''));
    count++;
    btn.innerText = "👍 " + count;
  });
});

// Filter
function filterPosts(type){
  let cards = Array.from(document.querySelectorAll('.confusion-card'));

  cards.sort((a,b)=>{
    if(type === 'votes'){
      return b.dataset.votes - a.dataset.votes;
    } else {
      return a.dataset.time - b.dataset.time;
    }
  });

  let container = document.getElementById('confusion-list');
  container.innerHTML = '';
  cards.forEach(c => container.appendChild(c));
}
</script>