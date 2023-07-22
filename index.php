<?php
require 'vendor/autoload.php';

// use Dotenv\Dotenv;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

echo $_ENV['DB_HOST'];

