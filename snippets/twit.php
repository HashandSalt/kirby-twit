<div class="tweet">
  <p><?= $tweetdata['text'] ?>
  <small> at <?= date('j.n.Y H:i', strtotime($tweetdata['created_at'])) ?></small></p>
</div>
