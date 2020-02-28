<div class="input-field">
    <input id="first_name" type="text" name="first_name" required <?= input_value('first_name', $user ?? null) ?>>
    <label for="first_name">Firstname</label>
</div>
<div class="input-field">
    <input id="last_name" type="text" name="last_name" required <?= input_value('last_name', $user ?? null) ?>>
    <label for="last_name">Lastname</label>
</div>
<div class="input-field">
    <input id="email" type="email" name="email" required <?= input_value('email', $user ?? null) ?>>
    <label for="email">Email</label>
</div>
<?php if (!isset($user)) : ?>
    <div class="input-field">
        <input id="password" type="password" name="password" required>
        <label for="password">Password</label>
    </div>
    <div class="input-field">
        <input id="cpassword" type="password" name="cpassword" required>
        <label for="cpassword">Confirm password</label>
    </div>
    <?php endif; ?>
    <div class="input-field">
        <select class="browser-default" name="role" required>
            <?= select_options('role', $user['role'] ?? null, [
                ['id' => ADMIN, 'name' => 'Admin'],
                ['id' => RESEARCHER, 'name' => 'Researcher'],
                ['id' => STUDENT, 'name' => 'Student']
            ], 'Role') ?>
        </select>
    </div>
<div class="col s12 center-align">
    <input type="submit" name="submit" value="<?= (!isset($user)) ? 'Create' : 'Update' ?>" class="btn half-width">
</div>