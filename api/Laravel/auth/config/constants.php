<?php


return [
    "IS_DELETED_NO" => 0,
    "IS_DELETED_YES" => 1,
    "INACTIVE_USER" => 0,
    "ACTIVE_USER" => 1,
    "USERNAME_MAX_LENGTH" => 20,
    "MANDATORY_FIELDS" => [
        "SIGNUP_FIELDS" => ['firstname', 'lastname', 'email', 'username', 'password'],
        "LOGIN_FIELDS" => ['username', 'password']
    ]
];
