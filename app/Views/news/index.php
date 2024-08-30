<?php if ($all_news !== []): ?>

    <?php foreach ($all_news as $new): ?>

        <h3><?= esc($new['title']) ?></h3>

        <div class="main">
            <?= esc($new['body']) ?>
        </div>
        <p><a href="/news/<?= esc($new['slug'], 'url') ?>">View article</a></p>

    <?php endforeach ?>

<?php else: ?>
    <h3>No latest news</h3>
<?php endif ?>