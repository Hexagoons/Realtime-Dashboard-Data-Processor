<?php

/**
 * @param       $page
 * @param array $parameters
 */
function require_page($page, $parameters = [])
{
    $page = str_replace('.', '/', $page);
    
    // Define path to page file
    $page = pages_path() . "/{$page}.php";

    // If path isn't a file or isn't readable
    if(!is_file($page) || !is_readable($page)) {
        die("Couldn't load page");
    }
    
    // Import variables into the current symbol table
    extract($parameters);
    
    // Require page file
    require ($page);
}

/**
 * @param       $component
 * @param array $parameters
 */
function require_component($component, $parameters = [])
{
    require_page("components.$component", $parameters);
}


/**
 * @param $controller
 *
 * @return int
 */
function dispatch_route($controller)
{
    $controller = explode('@', $controller);
    
    if(count($controller) !== 2) {
        die('Invalid controller reference');
    }
    
    $function = $controller[1];
    $controller = $controller[0];
    
    $controller = str_replace('.', '/', $controller);
    
    // Define path to the controller
    $controller = app_path() . "/controllers/{$controller}.php";
    
    // If controller isn't a file or isn't readable
    if(!is_file($controller) && !is_readable($controller)) {
        // 404 (File not found)
        return 404;
    }
    
    // Require controller
    require ($controller);
    
    // Call the function
    $return = $function();
    
    if($return) {
        // If relocation is defined
        header("Location: $return");
        // Redirect (Moved Temporarily)
        return 302;
    }
    
    // 200 (OK)
    return 200;
}

/**
 * @param      $routes
 * @param null $subDir
 *
 * @return int
 */
function handle_request($routes, $subDir = null)
{
    // Retrieve REQUEST_METHOD
    $method = $_SERVER['REQUEST_METHOD'];
    
    // Retrieve REQUEST_URI
    $uri = $_SERVER['REQUEST_URI'];

    // Remove subdirectory (if any)
    $uri = !is_null($subDir) ? substr($uri, strlen($subDir)) : $uri;
    // Remove GET parameters
    $uri = explode('?', $uri)[0];
    // Remove tail-end slash (if any)
    $uri = ($uri !== '/') ? rtrim($uri,'/') : $uri;
    
    // If user tries to access an dashboard routes
    // when he doesn't have dashboard permissions respond with 404
    if(substr( $uri, 0, 6 ) === "/admin" && !is_role(ADMIN))   {
        return http_response_code(404);
    }
    
    if(substr( $uri, 0, 10 ) === "/dashboard" && !is_logged_in())   {
        return http_response_code(404);
    }
    
    // Search through routes
    $controller = $routes[$method][$uri] ?? null;
    
    // If no route defined respond with 404
    if(is_null($controller)) {
        return http_response_code(404);
    }
    
    // Set response code
    return http_response_code(
        // Try to get require specified page
        dispatch_route($controller)
    );
}

/**
 * @param $route
 *
 * @return string
 */
function route_link($route)
{
    $route = str_replace('.', '/', $route);
    
    return  APP['url'] . "$route";
}

/**
 * @return string
 */
function previous_url()
{
    return $_SESSION['previous_url'] ?? route_link('/');
}