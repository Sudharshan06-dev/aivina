<?php

return [
    "errors" => [
        'signup_failed' => ['title'=>'Error!', "message" => "Invalid details entered. Please try again"],
        "username_exists" => ['title'=>'Error!', "message" => "Username already exists. Please try a different one"],
        "username_maxlength_exceeded" => ['title'=>'Error!', "message" => "Username should be less than 20 characters. Please try again"],
        "email_exists" => ['title' => 'Error!', 'message' => 'This email is already registered. Please log in instead.'],
        "username_password_invalid" => ['title' => 'Error!', 'message' => 'Username or password is invalid. Please try again'],
        "login_failed" => ['title' => 'Error!', 'message' => 'Login failed, Invalid data'],
    ],

    "success" => [
        'signup_successfully' => ['title' => 'Success!', 'message' => 'Registration successful! Please log in to get started.'],
    ]
];
