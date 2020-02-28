<div class="col l2">
    <h6>Welcome, <?= $_SESSION['user']['name'] ?? '' ?></h6>
    <hr>
    <div class="collection">
        <a href="<?= route_link('/dashboard'); ?>" class="collection-item">List</a>
        <a href="<?= route_link('/dashboard/top-10'); ?>?category=temp" class="collection-item">Top 10 in<br> Temperature</a>
        <a href="<?= route_link('/dashboard/top-10'); ?>?category=wdsp" class="collection-item">Top 10 in<br> Wind speed</a>
        <a href="<?= route_link('/dashboard/top-10'); ?>?category=sndp" class="collection-item">Top 10 in<br> Snow drop</a>
    </div>
</div>
