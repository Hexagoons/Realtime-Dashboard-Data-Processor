<?php

function overview()
{
    $db = database_connect();

    $sql = 'SELECT id, first_name, last_name, email, role FROM users';

    if (isset($_GET['query'])) {
        $query = database_escape($db, $_GET['query']);
        switch (strtolower($query)) {
            case "admin":
                $query = ADMIN;
                break;
            case "researcher":
                $query = RESEARCHER;
                break;
            case "student":
                $query = STUDENT;
                break;
        }
        $sql .= " WHERE first_name LIKE '%{$query}%' OR last_name LIKE '%{$query}%' OR role LIKE '%{$query}%'";
    }

    $sql .= ' ORDER BY last_name';

    $users = database_paginate($db, $sql, $_GET['page'] ?? 1);

    require_page('admin.users', [
        "users" => $users['result'],
        "pageCount" => $users['pageCount'],
    ]);
}

function create()
{
    // Validation
    if (empty($_POST)) {
        alert('error', 'Incorrect data sent.');
        return route_link('/admin/users');
    }

    $request = validate(
        array(
            'first_name'   => $_POST['first_name'] ?? null,
            'last_name'    => $_POST['last_name'] ?? null,
            'password'     => $_POST['password'] ?? null,
            'role'         => $_POST['role'] ?? null,
            'email'        => $_POST['email'] ?? null
        ),
        array(
            'first_name'   => 'name|min:1|max:45',
            'last_name'    => 'name|min:1|max:45',
            'password'     => 'string|min:8|max:20',
            'role'         => 'in_array:0,1,2',
            'email'        => 'string|email'
        )
    );

    if ($request === false)
        return route_link('/admin/users');

    // Check passwords
    if ($_POST['password'] !== $_POST['cpassword']) {
        alert('error', 'Passwords do not match.');
        return route_link('/admin/users');
    }

    extract($request);

    $db = database_connect();

    // Database
    $first_name   = database_escape($db, $first_name);
    $last_name    = database_escape($db, $last_name);
    $password     = database_escape($db, password_hash($password, PASSWORD_BCRYPT));
    $role         = database_escape($db, $role);
    $email        = database_escape($db, $email);

    if (!is_unique_email($db, $email)) {
        alert("error", "Sorry, this email is already being used.");
        return route_link('/admin/users');
    }

    $result = mysqli_query($db, "INSERT INTO users (first_name, last_name, password, role, email)
                                       VALUES ('{$first_name}', '{$last_name}', '{$password}', '{$role}', '{$email}')");

    if (!$result) {
        alert('error', 'Oops, something went wrong during creation of your account.');
        return route_link('/admin/users');
    }

    alert("success", "Your account has successfully been created.");
    return route_link('/admin/users');
}

function delete()
{
    if (!isset($_GET['id'])) {
        return route_link('/dashboard/users');
    }

    $db = database_connect();

    $id = database_escape($db, $_GET['id']);

    $result = mysqli_query($db, "DELETE FROM users WHERE id={$id}");

    alert('success', 'user succesfully deleted');

    return route_link('/admin/users');
}

function edit()
{
    if (!isset($_GET['id'])) {
        return route_link('/admin/users');
    }

    $db = database_connect();

    $id = database_escape($db, $_GET['id']);

    $sql = "SELECT id, first_name, last_name, email, role FROM users WHERE id = {$id}";

    $user = database_query($db, $sql)[0];

    require_page('admin.edit', [
        "user" => $user,
    ]);
}

function update()
{
    if (!isset($_GET['id'])) {
        return route_link('/admin/users');
    }

    // Validation
    if (empty($_POST)) {
        alert('error', 'Incorrect data sent.');
        return route_link('/admin/users');
    }

    $rules = array(
        'first_name' => 'string|min:1|max:255',
        'last_name' => 'string|min:1|max:255',
        'email' => 'string|email',
        'role'  => 'in_array:0,1,2'
    );

    $request = validate(array(
        'first_name' => $_POST['first_name'] ?? null,
        'last_name' => $_POST['last_name'] ?? null,
        'email' => $_POST['email'] ?? null,
        'role'  => $_POST['role'] ?? null,
    ), $rules);

    if ($request === false)
        return route_link('/admin/users');

    extract($request);

    $db = database_connect();

    $first_name = database_escape($db, $first_name);
    $last_name = database_escape($db, $last_name);
    $email = database_escape($db, $email);
    $role = database_escape($db, $role);

    $id = database_escape($db, $_GET['id']);

    $query = "update users SET first_name = '{$first_name}', last_name = '{$last_name}', email = '{$email}', role = '{$role}'";

    //dd($query. " WHERE id = '{$id}'");
    $result = mysqli_query($db, $query . " WHERE id = '{$id}'");

    if (!$result) {
        alert('error', 'Oops, something went wrong during creation of your account.');
        return route_link('/admin/users');
    }

    alert("success", "Your changes have been saved.");
    return route_link('/admin/users');
}
