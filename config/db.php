<?php
return [
    'driver'   => 'pdo_pgsql',
    'database' => getenv('DB_USER'),
    'username' => getenv('DB_USER'),
    'password' => getenv('DB_PASSWORD'),
    'host' => 'postgres'
];
