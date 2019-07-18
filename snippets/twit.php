<div class="tweet">
  <p><?= $tweetdata['full_text'] ?>
  <small> at <?= date('j.n.Y H:i', strtotime($tweetdata['created_at'])) ?></small></p>
</div>
