<?php

/*
|--------------------------------------------------------------------------
| Register Routes
|--------------------------------------------------------------------------
|
| This is where you can register routes for this application. These routes are used by
| the public/index.php file to handle requests. The array needs to be structured as follows:
|
| [ Request Method => [ Route => Controller@Function ]
|
*/
return [
    "GET" => [
        "/" => "homeController@get",
        "/logout" => "auth.loginController@logout",
        "/register" => "auth.registrationController@get",
        "/order" => "ordersController@overview",

        // Dashboard
        "/dashboard" => "dashboard.dashboardController@overview",
        "/dashboard/top-10" => "dashboard.dashboardController@top10",
        "/dashboard/stations" => "dashboard.dashboardController@show",
        "/dashboard/my-account" => "dashboard.myAccountController@show",
        "/dashboard/export" => "dashboard.dashboardController@export",
        "/dashboard/export/xml" => "dashboard.dashboardController@xml",
        
        // Admin
        "/admin/users" => "admin.adminController@overview",
        "/admin/users/edit" => "admin.adminController@edit"
    ],
    "POST" => [
        "/login" => "auth.loginController@submit",
        "/dashboard/my-account" => "dashboard.myAccountController@update",
        "/admin/users" => "admin.adminController@create",
        "/admin/users/delete" => "admin.adminController@delete",
        "/admin/users/edit" => "admin.adminController@update"
    ]
];