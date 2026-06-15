<?php

use CodeIgniter\CLI\CLI;

$code    = isset($code) ? $code : '404';
$message = isset($message) ? $message : 'Page Not Found';

CLI::error('ERROR: ' . $code);
CLI::write($message);
CLI::newLine();
