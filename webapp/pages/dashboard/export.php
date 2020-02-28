<!doctype html>
<html lang="nl">

<head>
    <?php require_component('metadata'); ?>

    <title>Dashboard</title>
</head>

<body>
    <?php require_component('header'); ?>


    <div class="row">
        <h3>Export</h3>
        <h5><?= ucfirst(strtolower($station['name'])) ?> <span class="new badge" style="float: none; padding: 4px 7px; vertical-align: middle;"><?= ucfirst(strtolower($station['country'])) ?>
        </h5>
    </div>

    <div class="row">
        <div class="col l10 w-100">
            <form action="/dashboard/export/xml" method="get">
                <input type="hidden" value="<?= $id ?>" name="id">
                <div class="row">
                    <div class="col s6">
                        <input type="text" class="datepicker" placeholder="From" name="from" id="from" required>
                        <input type="text" class="timepicker" name="from-time" placeholder="hh:mm" required value="<?= (\Carbon\Carbon::now())->subMinutes(2)->format('H:i') ?>">
                    </div>
                    <div class="col s6">
                        <input type="text" class="datepicker" placeholder="Till" name="till" id="till" required>
                        <input type="text" class="timepicker" name="till-time" placeholder="hh:mm" required value="<?= (\Carbon\Carbon::now())->subMinutes(1)->format('H:i') ?>">
                    </div>
                    <div class="col s12">
                        <input type="submit" value="Export" class="btn">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php require_component('footer'); ?>
</body>

</html>