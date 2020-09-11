<?php
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseFactory;
use Illuminate\Database\Capsule\Manager;
use Respect\Validation\Validator;

$capsule = new Manager();
$capsule->addConnection($container->get('database'));
$capsule->setAsGlobal();
$capsule->bootEloquent();

Validator::with('App\\Validation\\Rules\\');
