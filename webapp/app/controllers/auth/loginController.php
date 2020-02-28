<?php

function get()
{
    require_page('login');
}

function submit()
{
    // Validation
    if(empty($_POST)) {
        alert('error', 'Invalid credentials.');
        return route_link('/');
    }
    
    $request = validate(
        array(
            'email'    => $_POST[ 'email' ] ?? null,
            'password' => $_POST[ 'password' ] ?? null,
        ),
        array(
            'email'    => 'string|max:255|min:1|email',
            'password' => 'string',
        )
    );
    
    if($request === false)
        return route_link('/');
    
    extract($request);

    // Database
	$db = database_connect();

	$email = database_escape($db, $email);

	$query  = "SELECT id, concat(first_name, ' ', last_name) as `full_name`, role, password FROM users WHERE email = '$email';";
	$result = database_query($db, $query);

	if(count($result) == 0) {
		alert('error', 'Invalid combination of e-mail and password.');
		return route_link('/');
	}

	$user = $result[0];
	
	if (!password_verify($password, $user['password'])) {
        alert('error', 'Invalid combination of e-mail and password.');
        return route_link('/');
    }

	// Session
	$_SESSION["user"] = [];
	$_SESSION["user"]["id"]   = $user['id'];
    $_SESSION["user"]["name"] = $user['full_name'];
	$_SESSION["user"]["role"] = $user['role'];
    $_SESSION["user"]["token"] = hash('sha256', $_SESSION['user']['id'] . $_SESSION['user']['name'] . $_SESSION['user']['role']);

	// Redirect to dashboard or homepage
    return route_link('/dashboard');
}

function logout()
{
    unset($_SESSION['user']);
    
    alert('success', "You have been successfully logged out.");
    
    return route_link('/');
}