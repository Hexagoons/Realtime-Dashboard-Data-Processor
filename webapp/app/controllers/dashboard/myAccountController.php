<?php

function show()
{
    $db = database_connect();
    $id = database_escape($db, $_SESSION["user"]["id"]);
    $sql = 'SELECT first_name, last_name, email, role FROM users WHERE id = ' . $id;
    $user = database_query($db, $sql);

    require_page('dashboard.myAccount', [
        'user' => $user[0]
    ]);
}

function update()
{
    // Validation
    if (empty($_POST)) {
        alert('error', 'Incorrect data sent.');
        return route_link('/dashboard/my-account');
    }

    $rules = array(
        'email' => 'string|email',
        'change_email' => 'nullable|string|email',
        'current_password' => 'nullable|string|min:8|max:20',
        'new_password' => 'nullable|string|min:8|max:20'
    );

    if (isset($_POST['current_password']) && !empty($_POST['current_password']) &&
        isset($_POST['new_password']) && !empty($_POST['new_password'])) {
        $rules['current_password'] = 'string|min:8|max:20';
        $rules['new_password'] = 'string|min:8|max:20';
    }

    $request = validate(array(
        'current_password' => $_POST['current_password'] ?? null,
        'new_password' => $_POST['new_password'] ?? null,
        'email' => $_POST['email'] ?? null,
        'change_email' => $_POST['change_email'] ?? null
    ), $rules);

    if ($request === false)
        return route_link('/dashboard/my-account');

    // Check passwords
    if (!empty($_POST['current_password'])) {
        if ($_POST['current_password'] == $_POST['new_password']) {
            alert('error', 'Passwords are the same.');
            return route_link('/dashboard/my-account');
        }
    }


    if ($_POST['new_password'] !== $_POST['new_password_confirm']) {
        alert('error', 'Passwords do not match.');
        return route_link('/dashboard/my-account');
    }

    extract($request);

    $db = database_connect();

    $email = database_escape($db, $email);
    $new_email = database_escape($db, $change_email);

    $query = 'update users SET';


    if (isset($new_email) && !empty($new_email) && !is_unique_email($db, $new_email)) {
        alert("error", "Sorry, this email is already being used.");
        return route_link('/dashboard/my-account');
    }

    if (isset($new_email) && !empty($new_email))
        $query .= " email = '{$new_email}'";

    if (isset($_POST['new_password']) && !empty($_POST['new_password'])) {
        if ($query != 'update users SET')
            $query .= ",";
        $hashed = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
        $query .= " password = '{$hashed}'";
    }
    if ($query == 'update users SET')
        return route_link('/dashboard/my-account');

    $result = mysqli_query($db, $query . " WHERE id = '{$_SESSION['user']['id']}'");
    //dd($query . " WHERE id = '{$_SESSION['user']['id']}'");
    if (!$result) {
        alert('error', 'Oops, something went wrong during creation of your account.');
        return route_link('/dashboard/my-account');
    }

    alert('Success! ', 'Changes have been saved.');
    return route_link('/dashboard/my-account');
}
