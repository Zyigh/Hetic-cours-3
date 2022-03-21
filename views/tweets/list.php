<?php foreach ($tweets as $tweet): ?>
<div style="border-bottom: 1px solid black">
    <h2>Auteur: <?= $tweet['author'] ?> </h2>
    <p><?= $tweet['content'] ?></p>
</div>
<?php endforeach; ?>
