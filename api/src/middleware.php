<?php
use \App\Middleware\LoggedMiddleware;
use \App\Middleware\AdminMiddleware;

$logged = new LoggedMiddleware($container);
$admin = new AdminMiddleware($container);

