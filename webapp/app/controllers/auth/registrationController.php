<?php
function get()
{
    require_page('register');
}

function register()
{
    // Validation
    if(empty($_POST)) {
        alert('error', 'Incorrect data sent.');
        return route_link('/register');
    }
    
    $request = validate(
        array(
            'first_name'   => $_POST[ 'first_name' ] ?? null,
            'last_name'    => $_POST[ 'last_name' ] ?? null,
            'password'     => $_POST[ 'password' ] ?? null,
            'role'         => $_POST[ 'role' ] ?? null,
            'email'        => $_POST[ 'email' ] ?? null
        ),
        array(
            'first_name'   => 'name|min:1|max:45',
            'last_name'    => 'name|min:1|max:45',
            'password'     => 'string|min:8|max:20',
            'role'         => 'in_array:0,1,2',
            'email'        => 'string|email'
        )
    );
    
    if($request === false)
        return route_link('/register');
    
    // Check passwords
    if($_POST[ 'password' ] !== $_POST[ 'cpassword' ]) {
        alert('error', 'Passwords do not match.');
        return route_link('/register');
    }
    
    extract($request);

    $db = database_connect();

    // Database
    $first_name   = database_escape($db, $first_name);
    $last_name    = database_escape($db, $last_name);
    $password     = database_escape($db, password_hash($password, PASSWORD_BCRYPT));
    $role         = database_escape($db, $role);
    $email        = database_escape($db, $email);
    
    if(!is_unique_email($db, $email)) {
        alert("error", "Sorry, this email is already being used.");
        
        return route_link('/register');
    }
    
    $result = mysqli_query($db,"INSERT INTO users (first_name, last_name, password, role, email)
                                       VALUES ('{$first_name}', '{$last_name}', '{$password}', '{$role}', '{$email}')");
    
    if(!$result) {
        alert('error', 'Oops, something went wrong during creation of your account.');
        return route_link('/register');
    }

    alert("success", "Your account has successfully been created.");
    
    return route_link('/register');
}
