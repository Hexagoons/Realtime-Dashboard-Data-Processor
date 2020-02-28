<!doctype html>
<html lang="nl">
<head>
    <?php require_component('metadata'); ?>

    <title>Dashboard</title>
</head>
<body>
<?php require_component('header'); ?>

<div class="row">
    <h4>My Account</h4>
</div>

<div class="row">
    <div class="col s12 m6 left-align">
        <form method="POST">
            <div class="input-field">
                <input id="first_name" type="text" name="first_name" required <?= input_value('first_name', $user) ?>
                       readonly>
                <label for="first_name">Firstname</label>
            </div>
            <div class="input-field">
                <input id="last_name" type="text" name="last_name" required <?= input_value('last_name', $user) ?>
                       readonly>
                <label for="last_name">Lastname</label>
            </div>
            <div class="input-field">
                <input id="email" type="email" name="email" required <?= input_value('email', $user) ?> readonly>
                <label for="email">Email</label>
            </div>
            <div class="input-field m-0">
                <input id="change_email" type="text" name="change_email">
                <label for="change_email">Change Email</label>

                <div class="row">
                    <h4>Change Password</h4>
                </div>

                <div class="input-field m-0">
                    <input id="current_password" type="password" name="current_password">
                    <label for="current_password">Current password</label>
                </div>
                <div class="input-field m-0">
                    <input id="new_password" type="password" name="new_password">
                    <label for="new_password">New password</label>
                </div>
                <div class="input-field m-0">
                    <input id="new_password_confirm" type="password" name="new_password_confirm">
                    <label for="new_password_confirm">New password confirmation</label>
                </div>
                <div class="input-field">
                    <input type="submit" name="submit" value="Update" class="btn half-width">
                </div>
            </div>
        </form>
    </div>
</div>
<?php require_component('footer'); ?>
</body>
</html>