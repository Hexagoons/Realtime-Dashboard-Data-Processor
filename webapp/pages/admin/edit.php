<!doctype html>
<html lang="nl">

<head>
    <?php require_component('metadata'); ?>

    <title>Users</title>
</head>

<body>
    <?php require_component('header'); ?>

    <div class="row">
        <h3>Users</h3>
    </div>

    <div class="row">
        <div class="col s12">
            <div class="col s12 m5 left-align">
                <form method="POST">
                    <h5>Edit user</h5>

                    <?php require_component('forms.editUser', ['user' => $user]); ?>
                </form>

            </div>
        </div>
    </div>

    <?php require_component('footer'); ?>
</body>

</html>